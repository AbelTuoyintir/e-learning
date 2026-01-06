<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = \App\Models\Student::all();

        foreach ($students as $student) {
            \App\Models\Notification::create([
                'student_id' => $student->id,
                'title' => 'Welcome to the Student Portal!',
                'message' => 'Welcome to our quiz platform. Start exploring courses and taking quizzes.',
                'type' => 'success',
                'is_read' => false,
            ]);

            \App\Models\Notification::create([
                'student_id' => $student->id,
                'title' => 'New Course Available',
                'message' => 'A new mathematics course has been added. Check it out in the Courses section.',
                'type' => 'info',
                'is_read' => false,
            ]);

            \App\Models\Notification::create([
                'student_id' => $student->id,
                'title' => 'Quiz Reminder',
                'message' => 'Don\'t forget to complete your pending quizzes before the deadline.',
                'type' => 'warning',
                'is_read' => true,
            ]);
        }
    }
}
