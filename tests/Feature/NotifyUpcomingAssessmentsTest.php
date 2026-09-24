<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotifyUpcomingAssessmentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_notifies_students_of_upcoming_quizzes()
    {
        // 1. Create a course and a student
        $course = Course::create(['title' => 'Math 101']);
        $student = Student::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password')
        ]);

        // 2. Enroll student
        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id
        ]);

        // 3. Create a quiz due in 12 hours
        $quiz = Quiz::create([
            'title' => 'Calculus Quiz',
            'course_id' => $course->id,
            'quiz_type' => 'module_assessment',
            'due_at' => Carbon::now()->addHours(12)->toDateTimeString(),
        ]);

        // 4. Create another quiz due in 36 hours (should NOT notify)
        $farQuiz = Quiz::create([
            'title' => 'Algebra Quiz',
            'course_id' => $course->id,
            'quiz_type' => 'module_assessment',
            'due_at' => Carbon::now()->addHours(36)->toDateTimeString(),
        ]);

        // 5. Run the command
        $this->artisan('app:notify-upcoming-assessments')
            ->expectsOutput('Successfully sent 1 upcoming assessment notifications.')
            ->assertExitCode(0);

        // 6. Verify notification exists for Calculus Quiz
        $this->assertDatabaseHas('notifications', [
            'student_id' => $student->id,
            'title' => 'Upcoming Assessment',
        ]);

        $notification = Notification::where('student_id', $student->id)->first();
        $this->assertStringContainsString('Calculus Quiz', $notification->message);
        $this->assertEquals($quiz->id, $notification->data['quiz_id']);

        // 7. Verify no duplicate notification
        $this->artisan('app:notify-upcoming-assessments')
            ->expectsOutput('Successfully sent 0 upcoming assessment notifications.')
            ->assertExitCode(0);
    }
}
