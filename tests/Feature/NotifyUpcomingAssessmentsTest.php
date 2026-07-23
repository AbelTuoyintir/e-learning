<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\Quiz;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class NotifyUpcomingAssessmentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_notifies_enrolled_students_of_upcoming_quizzes_and_avoids_duplicates()
    {
        // 1. Create Course, Admin User, and Student
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);

        $student = Student::create([
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'email' => 'jane@student.com',
            'password' => Hash::make('password'),
        ]);

        $course = Course::create([
            'title' => 'Computer Science',
            'description' => 'Test Course Description',
            'user_id' => $admin->id,
        ]);

        // Enroll Student
        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        // 2. Create Quiz due in 5 hours (within 24 hours window)
        $quiz = Quiz::create([
            'title' => 'Upcoming Midterm',
            'course_id' => $course->id,
            'time_limit' => 60,
            'due_at' => now()->addHours(5),
            'quiz_type' => 'topic_quiz',
        ]);

        // 3. Run the Artisan command
        Artisan::call('app:notify-upcoming-assessments');

        // 4. Assert Notification was created
        $this->assertDatabaseHas('notifications', [
            'student_id' => $student->id,
            'title' => 'Upcoming Assessment',
            'type' => 'info',
        ]);

        $notification = Notification::where('student_id', $student->id)->first();
        $this->assertNotNull($notification->data);
        $this->assertEquals($quiz->id, $notification->data['quiz_id']);

        $initialNotificationCount = Notification::count();
        $this->assertEquals(1, $initialNotificationCount);

        // 5. Run the Artisan command again and assert no duplicate notifications are created
        Artisan::call('app:notify-upcoming-assessments');

        $finalNotificationCount = Notification::count();
        $this->assertEquals(1, $finalNotificationCount, 'Should not create duplicate notification for the same quiz and student.');
    }
}
