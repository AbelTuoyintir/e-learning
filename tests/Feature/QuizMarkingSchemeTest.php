<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\Module;
use App\Models\Topic;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Enrollment;
use App\Models\Result;

class QuizMarkingSchemeTest extends TestCase
{
    use RefreshDatabase;

    private function createStudent(array $overrides = []): Student
    {
        return Student::create(array_merge([
            'firstname' => 'Test',
            'lastname' => 'Student',
            'email' => 'student' . uniqid() . '@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ], $overrides));
    }

    public function test_marking_scheme_inheritance_hierarchy(): void
    {
        $course = Course::create([
            'title' => 'Test Course Hierarchy',
            'marking_scheme' => 'penalty',
        ]);

        $module = Module::create([
            'course_id' => $course->id,
            'title' => 'Test Module Hierarchy',
            'marking_scheme' => 'wicked',
        ]);

        $topic = Topic::create([
            'module_id' => $module->id,
            'title' => 'Test Topic Hierarchy',
            'marking_scheme' => 'standard',
        ]);

        // Explicit Quiz marking_scheme takes highest priority
        $quiz1 = Quiz::create([
            'title' => 'Quiz 1',
            'course_id' => $course->id,
            'module_id' => $module->id,
            'topic_id' => $topic->id,
            'time_limit' => 30,
            'time_per_question' => 30,
            'question_limit' => 60,
            'difficulty' => 'easy',
            'quiz_type' => 'topic_assessment',
            'marking_scheme' => 'penalty',
        ]);
        $this->assertEquals('penalty', $quiz1->effective_marking_scheme);

        // Inherit from topic when quiz scheme is null
        $quiz2 = Quiz::create([
            'title' => 'Quiz 2',
            'course_id' => $course->id,
            'module_id' => $module->id,
            'topic_id' => $topic->id,
            'time_limit' => 30,
            'time_per_question' => 30,
            'question_limit' => 60,
            'difficulty' => 'easy',
            'quiz_type' => 'topic_assessment',
            'marking_scheme' => null,
        ]);
        $this->assertEquals('standard', $quiz2->effective_marking_scheme);

        // Inherit from module when topic scheme is null
        $topicNoScheme = Topic::create([
            'module_id' => $module->id,
            'title' => 'Test Topic No Scheme',
            'marking_scheme' => null,
        ]);
        $quiz3 = Quiz::create([
            'title' => 'Quiz 3',
            'course_id' => $course->id,
            'module_id' => $module->id,
            'topic_id' => $topicNoScheme->id,
            'time_limit' => 30,
            'time_per_question' => 30,
            'question_limit' => 60,
            'difficulty' => 'easy',
            'quiz_type' => 'topic_assessment',
            'marking_scheme' => null,
        ]);
        $this->assertEquals('wicked', $quiz3->effective_marking_scheme);

        // Inherit from course when module scheme is null
        $moduleNoScheme = Module::create([
            'course_id' => $course->id,
            'title' => 'Test Module No Scheme',
            'marking_scheme' => null,
        ]);
        $quiz4 = Quiz::create([
            'title' => 'Quiz 4',
            'course_id' => $course->id,
            'module_id' => $moduleNoScheme->id,
            'time_limit' => 30,
            'time_per_question' => 30,
            'question_limit' => 60,
            'difficulty' => 'easy',
            'quiz_type' => 'topic_assessment',
            'marking_scheme' => null,
        ]);
        $this->assertEquals('penalty', $quiz4->effective_marking_scheme);

        // Default to standard when no scheme set anywhere
        $courseDefault = Course::create([
            'title' => 'Default Course',
            'marking_scheme' => null,
        ]);
        $quizDefault = Quiz::create([
            'title' => 'Default Quiz',
            'course_id' => $courseDefault->id,
            'time_limit' => 30,
            'time_per_question' => 30,
            'question_limit' => 60,
            'difficulty' => 'easy',
            'quiz_type' => 'topic_assessment',
            'marking_scheme' => null,
        ]);
        $this->assertEquals('standard', $quizDefault->effective_marking_scheme);
    }

    public function test_standard_marking_scheme_grading(): void
    {
        $student = $this->createStudent();
        $course = Course::create(['title' => 'Standard Scheme Course', 'marking_scheme' => 'standard']);
        Enrollment::create(['student_id' => $student->id, 'course_id' => $course->id, 'enrolled_at' => now()]);

        $quiz = Quiz::create([
            'title' => 'Standard Quiz',
            'course_id' => $course->id,
            'time_limit' => 30,
            'time_per_question' => 30,
            'question_limit' => 60,
            'difficulty' => 'easy',
            'quiz_type' => 'topic_assessment',
            'marking_scheme' => 'standard',
        ]);

        $q1 = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'Q1',
            'option_a' => 'Opt A', 'option_b' => 'Opt B', 'option_c' => 'Opt C', 'option_d' => 'Opt D',
            'correct_option' => 'A',
            'points' => 1,
            'type' => 'mcq',
        ]);
        $q2 = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'Q2',
            'option_a' => 'Opt A', 'option_b' => 'Opt B', 'option_c' => 'Opt C', 'option_d' => 'Opt D',
            'correct_option' => 'A',
            'points' => 1,
            'type' => 'mcq',
        ]);
        $q3 = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'Q3',
            'option_a' => 'Opt A', 'option_b' => 'Opt B', 'option_c' => 'Opt C', 'option_d' => 'Opt D',
            'correct_option' => 'A',
            'points' => 1,
            'type' => 'mcq',
        ]);

        // Q1 correct (A), Q2 wrong (B), Q3 skipped ('')
        $answers = [
            $q1->id => 'A',
            $q2->id => 'B',
            $q3->id => '',
        ];

        config(['auth.defaults.guard' => 'student']);

        $response = $this->actingAs($student, 'student')
            ->post(route('quiz.submit', $quiz->id), ['answers' => $answers]);

        $response->assertRedirect();

        $result = Result::where('student_id', $student->id)->where('quiz_id', $quiz->id)->latest()->first();
        $this->assertNotNull($result);
        // Correct (+1), Wrong (0), Skipped (0) => Total score = 1
        $this->assertEquals(1.0, (float)$result->score);
    }

    public function test_penalty_marking_scheme_grading(): void
    {
        $student = $this->createStudent();
        $course = Course::create(['title' => 'Penalty Scheme Course', 'marking_scheme' => 'penalty']);
        Enrollment::create(['student_id' => $student->id, 'course_id' => $course->id, 'enrolled_at' => now()]);

        $quiz = Quiz::create([
            'title' => 'Penalty Quiz',
            'course_id' => $course->id,
            'time_limit' => 30,
            'time_per_question' => 30,
            'question_limit' => 60,
            'difficulty' => 'easy',
            'quiz_type' => 'topic_assessment',
            'marking_scheme' => 'penalty',
        ]);

        $q1 = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'Q1',
            'option_a' => 'Opt A', 'option_b' => 'Opt B', 'option_c' => 'Opt C', 'option_d' => 'Opt D',
            'correct_option' => 'A',
            'points' => 1,
            'type' => 'mcq',
        ]);
        $q2 = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'Q2',
            'option_a' => 'Opt A', 'option_b' => 'Opt B', 'option_c' => 'Opt C', 'option_d' => 'Opt D',
            'correct_option' => 'A',
            'points' => 1,
            'type' => 'mcq',
        ]);
        $q3 = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'Q3',
            'option_a' => 'Opt A', 'option_b' => 'Opt B', 'option_c' => 'Opt C', 'option_d' => 'Opt D',
            'correct_option' => 'A',
            'points' => 1,
            'type' => 'mcq',
        ]);

        // Q1 correct (A), Q2 wrong (B), Q3 skipped ('')
        $answers = [
            $q1->id => 'A',
            $q2->id => 'B',
            $q3->id => '',
        ];

        config(['auth.defaults.guard' => 'student']);

        $response = $this->actingAs($student, 'student')
            ->post(route('quiz.submit', $quiz->id), ['answers' => $answers]);

        $response->assertRedirect();

        $result = Result::where('student_id', $student->id)->where('quiz_id', $quiz->id)->latest()->first();
        $this->assertNotNull($result);
        // Correct (+1), Wrong (-1), Skipped (0) => Total score = 0
        $this->assertEquals(0.0, (float)$result->score);
    }

    public function test_wicked_marking_scheme_grading(): void
    {
        $student = $this->createStudent();
        $course = Course::create(['title' => 'Wicked Scheme Course', 'marking_scheme' => 'wicked']);
        Enrollment::create(['student_id' => $student->id, 'course_id' => $course->id, 'enrolled_at' => now()]);

        $quiz = Quiz::create([
            'title' => 'Wicked Quiz',
            'course_id' => $course->id,
            'time_limit' => 30,
            'time_per_question' => 30,
            'question_limit' => 60,
            'difficulty' => 'easy',
            'quiz_type' => 'topic_assessment',
            'marking_scheme' => 'wicked',
        ]);

        $q1 = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'Q1',
            'option_a' => 'Opt A', 'option_b' => 'Opt B', 'option_c' => 'Opt C', 'option_d' => 'Opt D',
            'correct_option' => 'A',
            'points' => 1,
            'type' => 'mcq',
        ]);
        $q2 = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'Q2',
            'option_a' => 'Opt A', 'option_b' => 'Opt B', 'option_c' => 'Opt C', 'option_d' => 'Opt D',
            'correct_option' => 'A',
            'points' => 1,
            'type' => 'mcq',
        ]);
        $q3 = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'Q3',
            'option_a' => 'Opt A', 'option_b' => 'Opt B', 'option_c' => 'Opt C', 'option_d' => 'Opt D',
            'correct_option' => 'A',
            'points' => 1,
            'type' => 'mcq',
        ]);

        // Q1 correct (A), Q2 wrong (B), Q3 skipped ('')
        $answers = [
            $q1->id => 'A',
            $q2->id => 'B',
            $q3->id => '',
        ];

        config(['auth.defaults.guard' => 'student']);

        $response = $this->actingAs($student, 'student')
            ->post(route('quiz.submit', $quiz->id), ['answers' => $answers]);

        $response->assertRedirect();

        $result = Result::where('student_id', $student->id)->where('quiz_id', $quiz->id)->latest()->first();
        $this->assertNotNull($result);
        // Correct (+1), Wrong (-1), Skipped (-0.5) => Total score = -0.5
        $this->assertEquals(-0.5, (float)$result->score);
    }
}
