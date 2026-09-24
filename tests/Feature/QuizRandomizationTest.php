<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizRandomizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_quiz_loads_all_questions_if_60_or_fewer()
    {
        $course = Course::create(['title' => 'Test Course']);
        $student = Student::create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);
        Enrollment::create(['student_id' => $student->id, 'course_id' => $course->id]);

        $quiz = Quiz::create([
            'title' => 'Small Quiz',
            'course_id' => $course->id,
            'quiz_type' => 'module_assessment'
        ]);

        // Create 45 questions
        for ($i = 1; $i <= 45; $i++) {
            Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => "Question $i",
                'option_a' => 'A', 'option_b' => 'B', 'option_c' => 'C', 'option_d' => 'D',
                'correct_option' => 'A'
            ]);
        }

        $this->actingAs($student, 'student');
        $response = $this->get(route('quiz.start', $quiz));

        $response->assertStatus(200);
        $questions = $response->viewData('questions');
        $this->assertCount(45, $questions);
    }

    public function test_quiz_loads_exactly_60_questions_if_more_than_60()
    {
        $course = Course::create(['title' => 'Test Course']);
        $student = Student::create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);
        Enrollment::create(['student_id' => $student->id, 'course_id' => $course->id]);

        $quiz = Quiz::create([
            'title' => 'Large Quiz',
            'course_id' => $course->id,
            'quiz_type' => 'module_assessment'
        ]);

        // Create 100 questions
        for ($i = 1; $i <= 100; $i++) {
            Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => "Question $i",
                'option_a' => 'A', 'option_b' => 'B', 'option_c' => 'C', 'option_d' => 'D',
                'correct_option' => 'A'
            ]);
        }

        $this->actingAs($student, 'student');
        $response = $this->get(route('quiz.start', $quiz));

        $response->assertStatus(200);
        $questions = $response->viewData('questions');
        $this->assertCount(60, $questions);
    }
}
