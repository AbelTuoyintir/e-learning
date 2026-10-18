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

    public function test_quiz_grading_calculated_over_question_limit_not_entire_question_bank()
    {
        $course = Course::create(['title' => 'Test Course']);
        $student = Student::create([
            'firstname' => 'Grading',
            'lastname' => 'Student',
            'email' => 'grading@example.com',
            'password' => bcrypt('password')
        ]);
        Enrollment::create(['student_id' => $student->id, 'course_id' => $course->id]);

        // Admin sets 300 questions in bank and 120 question limit
        $quiz = Quiz::create([
            'title' => 'Large Bank Quiz',
            'course_id' => $course->id,
            'quiz_type' => 'module_assessment',
            'question_limit' => 120
        ]);

        $questionsCreated = [];
        for ($i = 1; $i <= 300; $i++) {
            $questionsCreated[] = Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => "Question $i",
                'option_a' => 'Option A',
                'option_b' => 'Option B',
                'option_c' => 'Option C',
                'option_d' => 'Option D',
                'correct_option' => 'A',
                'points' => 1
            ]);
        }

        $this->actingAs($student, 'student');

        // Start quiz to get 120 questions
        $startResponse = $this->get(route('quiz.start', $quiz));
        $startResponse->assertStatus(200);
        $questionsPresented = $startResponse->viewData('questions');
        $this->assertCount(120, $questionsPresented);

        $lockedIds = $questionsPresented->pluck('id')->all();

        // Answer all 120 questions correctly
        $answers = [];
        foreach ($lockedIds as $qid) {
            $answers[$qid] = 'A';
        }

        $submitResponse = $this->post(route('quiz.submit', $quiz), [
            'locked_question_ids' => $lockedIds,
            'answers' => $answers,
        ]);

        $submitResponse->assertRedirect(route('quiz.results', $quiz->id));

        // Retrieve result
        $result = \App\Models\Result::where('student_id', $student->id)->where('quiz_id', $quiz->id)->first();
        $this->assertNotNull($result);
        $this->assertEquals(120, $result->total_possible_points);
        $this->assertEquals(120, $result->score);
        $this->assertEquals(100.0, $result->percentage);

        // Check results view
        $resultsViewResponse = $this->get(route('quiz.results', $quiz));
        $resultsViewResponse->assertStatus(200);
        $sessionResult = $resultsViewResponse->viewData('sessionResult');
        $this->assertEquals(120, $sessionResult['total']);
        $this->assertEquals(120, $sessionResult['score']);
        $this->assertEquals(100.0, $sessionResult['percentage']);
    }
}
