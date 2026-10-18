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

    public function test_quiz_respects_custom_admin_question_limit()
    {
        $course = Course::create(['title' => 'Test Course']);
        $student = Student::create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test_custom@example.com',
            'password' => bcrypt('password')
        ]);
        Enrollment::create(['student_id' => $student->id, 'course_id' => $course->id]);

        // Admin sets question_limit to 25
        $quiz = Quiz::create([
            'title' => 'Custom Limit Quiz',
            'course_id' => $course->id,
            'quiz_type' => 'module_assessment',
            'question_limit' => 25
        ]);

        // Question bank contains 80 questions
        for ($i = 1; $i <= 80; $i++) {
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
        $this->assertCount(25, $questions);
    }

    public function test_student_is_graded_on_questions_attempted_not_full_bank()
    {
        $course = Course::create(['title' => 'Test Course']);
        $student = Student::create([
            'firstname' => 'Test',
            'lastname' => 'Grading',
            'email' => 'grading@example.com',
            'password' => bcrypt('password')
        ]);
        Enrollment::create(['student_id' => $student->id, 'course_id' => $course->id]);

        // Admin sets question_limit to 120 for a 300 question bank
        $quiz = Quiz::create([
            'title' => '300 Bank 120 Limit Quiz',
            'course_id' => $course->id,
            'quiz_type' => 'module_assessment',
            'question_limit' => 120
        ]);

        for ($i = 1; $i <= 300; $i++) {
            Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => "Question $i",
                'option_a' => 'A', 'option_b' => 'B', 'option_c' => 'C', 'option_d' => 'D',
                'correct_option' => 'A',
                'points' => 1
            ]);
        }

        $this->actingAs($student, 'student');
        $response = $this->get(route('quiz.start', $quiz));
        $response->assertStatus(200);

        $servedQuestions = $response->viewData('questions');
        $this->assertCount(120, $servedQuestions);

        $answers = [];
        $lockedQuestionIds = [];
        foreach ($servedQuestions as $q) {
            $answers[$q->id] = 'A'; // All correct
            $lockedQuestionIds[] = $q->id;
        }

        $submitResponse = $this->post(route('quiz.submit', $quiz), [
            'answers' => $answers,
            'locked_question_ids' => $lockedQuestionIds
        ]);

        $submitResponse->assertRedirect(route('quiz.results', $quiz->id));

        $this->assertDatabaseHas('results', [
            'student_id' => $student->id,
            'quiz_id' => $quiz->id,
            'score' => 120,
            'total_possible_points' => 120,
            'percentage' => 100.0,
        ]);
    }
}
