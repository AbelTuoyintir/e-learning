<?php

namespace App\Console\Commands;

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
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify students about assessments due within the next 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $upcomingQuizzes = \App\Models\Quiz::where('due_at', '>', now())
            ->where('due_at', '<=', now()->addDay())
            ->get();

        foreach ($upcomingQuizzes as $quiz) {
            $enrolledStudentIds = \App\Models\Enrollment::where('course_id', $quiz->course_id)
                ->pluck('student_id');

            foreach ($enrolledStudentIds as $studentId) {
                // Check if already notified to avoid duplicates
                $exists = \App\Models\Notification::where('student_id', $studentId)
                    ->where('title', 'Upcoming Assessment')
                    ->where('message', 'like', "%'{$quiz->title}'%")
                    ->where('created_at', '>', now()->subDay())
                    ->exists();

                if (!$exists) {
                    \App\Models\Notification::create([
                        'student_id' => $studentId,
                        'title' => 'Upcoming Assessment',
                        'message' => "Reminder: The assessment '{$quiz->title}' is due on {$quiz->due_at->format('M d, Y H:i')}.",
                        'type' => 'warning',
                    ]);
                }
            }
        }

        $this->info('Upcoming assessment notifications sent successfully.');
    }
}
