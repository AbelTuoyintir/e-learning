<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class QuizRandomizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_loads_all_questions_when_quiz_has_60_or_fewer_questions()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);

        $student = Student::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@student.com',
            'password' => Hash::make('password'),
        ]);

        $course = Course::create([
            'title' => 'Software Engineering',
            'description' => 'Course Description',
            'user_id' => $admin->id,
        ]);

        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        $module = Module::create([
            'title' => 'Module 1',
            'course_id' => $course->id,
        ]);

        $quiz = Quiz::create([
            'title' => 'Small Quiz',
            'course_id' => $course->id,
            'module_id' => $module->id,
            'time_limit' => 30,
            'quiz_type' => 'module_assessment',
        ]);

        // Create 10 questions
        for ($i = 1; $i <= 10; $i++) {
            Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => "Question {$i}",
                'option_a' => 'A',
                'option_b' => 'B',
                'option_c' => 'C',
                'option_d' => 'D',
                'correct_option' => 'A',
                'points' => 1,
            ]);
        }

        config(['auth.defaults.guard' => 'student']);
        $this->actingAs($student, 'student');

        $response = $this->get("/quiz/{$quiz->id}");
        $response->assertStatus(200);

        // Verify that 10 questions are rendered
        $questions = $response->viewData('questions');
        $this->assertCount(10, $questions);
    }

    public function test_it_loads_exactly_60_questions_when_quiz_has_more_than_60_questions()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);

        $student = Student::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@student.com',
            'password' => Hash::make('password'),
        ]);

        $course = Course::create([
            'title' => 'Software Engineering',
            'description' => 'Course Description',
            'user_id' => $admin->id,
        ]);

        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        $module = Module::create([
            'title' => 'Module 1',
            'course_id' => $course->id,
        ]);

        $quiz = Quiz::create([
            'title' => 'Large Quiz',
            'course_id' => $course->id,
            'module_id' => $module->id,
            'time_limit' => 30,
            'quiz_type' => 'module_assessment',
        ]);

        // Create 65 questions
        for ($i = 1; $i <= 65; $i++) {
            Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => "Question {$i}",
                'option_a' => 'A',
                'option_b' => 'B',
                'option_c' => 'C',
                'option_d' => 'D',
                'correct_option' => 'A',
                'points' => 1,
            ]);
        }

        config(['auth.defaults.guard' => 'student']);
        $this->actingAs($student, 'student');

        $response = $this->get("/quiz/{$quiz->id}");
        $response->assertStatus(200);

        // Verify that exactly 60 questions are rendered
        $questions = $response->viewData('questions');
        $this->assertCount(60, $questions);
    }
}
