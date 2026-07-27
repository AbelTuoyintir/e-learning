<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quiz;
use App\Models\Notification;
use App\Models\Enrollment;
use Carbon\Carbon;

class NotifyUpcomingAssessments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-upcoming-assessments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify students about assessments due within 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now()->toDateTimeString();
        $oneDayLater = Carbon::now()->addDay()->toDateTimeString();

        $upcomingQuizzes = Quiz::whereNotNull('due_at')
            ->where('due_at', '>', $now)
            ->where('due_at', '<=', $oneDayLater)
            ->get();

        $count = 0;

        foreach ($upcomingQuizzes as $quiz) {
            $courseId = $this->resolveQuizCourseId($quiz);

            if (!$courseId) continue;

            $enrolledStudents = Enrollment::where('course_id', $courseId)
                ->pluck('student_id');

            foreach ($enrolledStudents as $studentId) {
                // Prevent duplicate notifications for the same quiz
                $exists = Notification::where('student_id', $studentId)
                    ->where('title', 'Upcoming Assessment')
                    ->where('data->quiz_id', $quiz->id)
                    ->exists();

                if (!$exists) {
                    Notification::create([
                        'student_id' => $studentId,
                        'title' => 'Upcoming Assessment',
                        'message' => "The assessment '{$quiz->title}' is due on " . $quiz->due_at->format('M d, Y h:i A') . ". Don't forget to complete it!",
                        'type' => 'warning',
                        'data' => ['quiz_id' => $quiz->id]
                    ]);
                    $count++;
                }
            }
        }

        $this->info("Successfully sent {$count} upcoming assessment notifications.");
    }

    private function resolveQuizCourseId(Quiz $quiz): ?int
    {
        if ($quiz->course_id) {
            return (int) $quiz->course_id;
        }

        if ($quiz->module?->course_id) {
            return (int) $quiz->module->course_id;
        }

        if ($quiz->topic?->module?->course_id) {
            return (int) $quiz->topic->module->course_id;
        }

        return null;
    }
}
