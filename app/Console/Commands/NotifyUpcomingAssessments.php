<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Quiz;
use App\Models\Enrollment;
use App\Models\Notification;
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
    protected $description = 'Identify quizzes due within 24 hours and notify enrolled students';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting upcoming assessments notification check...');

        // Get quizzes due within 24 hours
        $now = now();
        $twentyFourHoursFromNow = now()->addHours(24);

        $quizzes = Quiz::whereNotNull('due_at')
            ->whereBetween('due_at', [$now, $twentyFourHoursFromNow])
            ->get();

        $this->info("Found {$quizzes->count()} quizzes due within 24 hours.");

        foreach ($quizzes as $quiz) {
            $courseId = $this->resolveQuizCourseId($quiz);

            if (!$courseId) {
                $this->warn("Could not resolve course ID for quiz ID {$quiz->id}. Skipping.");
                continue;
            }

            // Get students enrolled in this course
            $studentIds = Enrollment::where('course_id', $courseId)
                ->pluck('student_id')
                ->unique();

            $this->info("Quiz '{$quiz->title}' (ID: {$quiz->id}) is due on {$quiz->due_at}. Notifying {$studentIds->count()} students.");

            foreach ($studentIds as $studentId) {
                // Check if already notified
                $alreadyNotified = Notification::where('student_id', $studentId)
                    ->where('data->quiz_id', $quiz->id)
                    ->exists();

                if ($alreadyNotified) {
                    $this->line("Student ID {$studentId} already notified for Quiz ID {$quiz->id}. Skipping.");
                    continue;
                }

                // Create notification
                Notification::create([
                    'student_id' => $studentId,
                    'title' => 'Upcoming Assessment',
                    'message' => "The assessment '{$quiz->title}' is due within 24 hours (on " . $quiz->due_at->format('M d, Y h:i A') . "). Make sure to complete it on time!",
                    'type' => 'info',
                    'is_read' => false,
                    'data' => [
                        'quiz_id' => $quiz->id,
                        'due_at' => $quiz->due_at->toIso8601String(),
                    ],
                ]);
            }
        }

        $this->info('Upcoming assessments notification check complete.');
        return Command::SUCCESS;
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
