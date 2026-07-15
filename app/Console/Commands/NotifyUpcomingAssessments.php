<?php

namespace App\Console\Commands;

use App\Models\Quiz;
use App\Models\Enrollment;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyUpcomingAssessments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-upcoming-assessments';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Notify students about upcoming assessments due in 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for upcoming assessments...');

        $now = Carbon::now();
        $tomorrow = Carbon::now()->addDay();

        $upcomingQuizzes = Quiz::whereBetween('due_at', [$now, $tomorrow])
            ->where('is_active', true)
            ->get();

        if ($upcomingQuizzes->isEmpty()) {
            $this->info('No upcoming assessments found.');
            return;
        }

        foreach ($upcomingQuizzes as $quiz) {
            $this->info("Processing quiz: {$quiz->title}");

            // Resolve course ID for enrollment check
            $courseId = null;
            if ($quiz->course_id) {
                $courseId = $quiz->course_id;
            } elseif ($quiz->module?->course_id) {
                $courseId = $quiz->module->course_id;
            } elseif ($quiz->topic?->module?->course_id) {
                $courseId = $quiz->topic->module->course_id;
            }

            if (!$courseId) {
                $this->warn("Could not resolve course for quiz ID: {$quiz->id}");
                continue;
            }

            $studentIds = Enrollment::where('course_id', $courseId)
                ->pluck('student_id')
                ->unique();

            foreach ($studentIds as $studentId) {
                // Prevent duplicate notifications for the same quiz
                $alreadyNotified = Notification::where('student_id', $studentId)
                    ->where('title', 'Upcoming Assessment')
                    ->where('data->quiz_id', $quiz->id)
                    ->exists();

                if ($alreadyNotified) {
                    continue;
                }

                Notification::create([
                    'student_id' => $studentId,
                    'title' => 'Upcoming Assessment',
                    'message' => "The assessment '{$quiz->title}' is due on " . $quiz->due_at->format('M d, Y h:i A') . ". Don't forget to complete it!",
                    'type' => 'info',
                    'data' => [
                        'quiz_id' => $quiz->id,
                        'due_at' => $quiz->due_at,
                    ],
                ]);
            }

            $this->info("Notified " . count($studentIds) . " students for '{$quiz->title}'.");
        }

        $this->info('Finished notifying students about upcoming assessments.');
    }
}
