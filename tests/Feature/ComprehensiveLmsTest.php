<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Student;
use App\Models\Course;
use App\Models\Module;
use App\Models\Topic;
use App\Models\TopicProgress;
use App\Models\ModuleProgress;
use App\Models\Enrollment;
use App\Models\Result;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class ComprehensiveLmsTest extends TestCase
{
    use RefreshDatabase;

    protected $student;
    protected $course;
    protected $module;
    protected $quiz;

    protected function setUp(): void
    {
        parent::setUp();

        // Create student
        $this->student = Student::create([
            'firstname' => 'Test',
            'lastname' => 'Student',
            'email' => 'teststudent@example.com',
            'password' => bcrypt('password')
        ]);

        // Create course and module
        $this->course = Course::create(['title' => 'Introduction to Laravel']);
        $this->module = Module::create([
            'title' => 'MVC Architecture',
            'course_id' => $this->course->id
        ]);

        // Create quiz
        $this->quiz = Quiz::create([
            'title' => 'MVC Architecture Assessment',
            'course_id' => $this->course->id,
            'module_id' => $this->module->id,
            'quiz_type' => 'module_assessment',
            'passing_score' => 70,
            'max_attempts' => 4
        ]);
    }

    public function test_unenrolled_student_cannot_access_quizzes_or_materials()
    {
        $this->actingAs($this->student, 'student');

        // Access quiz start page
        $responseQuiz = $this->get(route('quiz.start', $this->quiz));
        $responseQuiz->assertRedirect(route('students.courses'));
        $responseQuiz->assertSessionHas('error', 'You must enroll in this course before viewing its quizzes or materials.');

        // Access materials page
        $responseMaterials = $this->get(route('students.course.materials', $this->course));
        $responseMaterials->assertRedirect(route('students.enrolledcourses'));
        $responseMaterials->assertSessionHas('error', 'You are not enrolled in this course.');
    }

    public function test_enrolled_student_can_access_quizzes_and_materials()
    {
        // Enroll student
        Enrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'payment_status' => 'free'
        ]);

        // Create at least 1 question for the quiz so it loads
        Question::create([
            'quiz_id' => $this->quiz->id,
            'question_text' => 'What does MVC stand for?',
            'option_a' => 'Model View Controller',
            'option_b' => 'Model View Choice',
            'option_c' => 'Main View Controller',
            'option_d' => 'Metadata View Control',
            'correct_option' => 'A',
            'points' => 10,
            'type' => 'MCQ'
        ]);

        $this->actingAs($this->student, 'student');

        // Access quiz start page
        $responseQuiz = $this->get(route('quiz.start', $this->quiz));
        $responseQuiz->assertStatus(200);

        // Access materials page
        $responseMaterials = $this->get(route('students.course.materials', $this->course));
        $responseMaterials->assertStatus(200);
    }

    public function test_automatic_mcq_grading_and_results_calculation()
    {
        // Enroll student
        Enrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'payment_status' => 'free'
        ]);

        // Create three questions
        $q1 = Question::create([
            'quiz_id' => $this->quiz->id,
            'question_text' => 'Q1',
            'option_a' => 'A1', 'option_b' => 'B1', 'option_c' => 'C1', 'option_d' => 'D1',
            'correct_option' => 'A', 'points' => 10, 'type' => 'MCQ'
        ]);

        $q2 = Question::create([
            'quiz_id' => $this->quiz->id,
            'question_text' => 'Q2',
            'option_a' => 'A2', 'option_b' => 'B2', 'option_c' => 'C2', 'option_d' => 'D2',
            'correct_option' => 'B', 'points' => 10, 'type' => 'MCQ'
        ]);

        $q3 = Question::create([
            'quiz_id' => $this->quiz->id,
            'question_text' => 'Q3',
            'option_a' => 'A3', 'option_b' => 'B3', 'option_c' => 'C3', 'option_d' => 'D3',
            'correct_option' => 'C', 'points' => 20, 'type' => 'MCQ'
        ]);

        $this->actingAs($this->student, 'student');

        // Submit quiz: Correct answers for Q1 & Q3, Incorrect for Q2 (Total possible points: 40)
        // Score should be 10 + 20 = 30 points. Percentage = 75%. Passing score is 70% so Passed should be true.
        $response = $this->post(route('quiz.submit', $this->quiz), [
            'answers' => [
                $q1->id => 'A',
                $q2->id => 'A', // wrong (correct is B)
                $q3->id => 'C'
            ]
        ]);

        $response->assertRedirect(route('quiz.results', $this->quiz->id));

        // Check result record in DB
        $result = Result::where('student_id', $this->student->id)->where('quiz_id', $this->quiz->id)->first();
        $this->assertNotNull($result);
        $this->assertEquals(30, $result->score);
        $this->assertEquals(40, $result->total_possible_points);
        $this->assertEquals(75.0, $result->percentage);
        $this->assertEquals(1, $result->passed);
        $this->assertEquals('C', $result->grade);

        // Check learning history
        $this->assertDatabaseHas('learning_history', [
            'student_id' => $this->student->id,
            'activity_type' => 'assessment_taken',
            'related_id' => $this->quiz->id,
            'related_type' => 'quiz',
        ]);
    }

    public function test_module_attempt_policy_locks_after_four_failed_attempts()
    {
        // Enroll student
        Enrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'payment_status' => 'free'
        ]);

        // Create 1 question
        $q = Question::create([
            'quiz_id' => $this->quiz->id,
            'question_text' => 'Question?',
            'option_a' => 'A', 'option_b' => 'B', 'option_c' => 'C', 'option_d' => 'D',
            'correct_option' => 'A', 'points' => 10, 'type' => 'MCQ'
        ]);

        $this->actingAs($this->student, 'student');

        // Setup topics
        $topic1 = Topic::create(['title' => 'Topic 1', 'module_id' => $this->module->id]);
        $topic2 = Topic::create(['title' => 'Topic 2', 'module_id' => $this->module->id]);

        // Complete the topics so they can make attempts first
        TopicProgress::create(['student_id' => $this->student->id, 'topic_id' => $topic1->id, 'status' => 'Completed']);
        TopicProgress::create(['student_id' => $this->student->id, 'topic_id' => $topic2->id, 'status' => 'Completed']);

        // Set module progress
        $moduleProgress = ModuleProgress::create([
            'student_id' => $this->student->id,
            'module_id' => $this->module->id,
            'status' => 'In Progress',
            'attempts_since_retake' => 0
        ]);

        // Fail 4 times
        for ($attempt = 1; $attempt <= 4; $attempt++) {
            // Fail attempt
            $response = $this->post(route('quiz.submit', $this->quiz), [
                'answers' => [
                    $q->id => 'B' // wrong
                ]
            ]);
            $response->assertRedirect(route('quiz.results', $this->quiz->id));
        }

        // Verify module status is now 'Retake Required'
        $moduleProgress->refresh();
        $this->assertEquals('Retake Required', $moduleProgress->status);
        $this->assertEquals(4, $moduleProgress->attempts_since_retake);

        // Verify TopicProgress has been reset to 'In Progress' for re-learning
        $tp1 = TopicProgress::where('student_id', $this->student->id)->where('topic_id', $topic1->id)->first();
        $tp2 = TopicProgress::where('student_id', $this->student->id)->where('topic_id', $topic2->id)->first();
        $this->assertEquals('In Progress', $tp1->status);
        $this->assertEquals('In Progress', $tp2->status);

        // Accessing the quiz again should be blocked
        $responseAccess = $this->get(route('quiz.start', $this->quiz));
        $responseAccess->assertRedirect();
        $responseAccess->assertSessionHas('error', 'Module retake required. Please review all module topics before attempting the assessment again.');
    }

    public function test_marking_all_topics_completed_unlocks_locked_module()
    {
        // Enroll student
        Enrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'payment_status' => 'free'
        ]);

        $topic1 = Topic::create(['title' => 'Topic 1', 'module_id' => $this->module->id]);
        $topic2 = Topic::create(['title' => 'Topic 2', 'module_id' => $this->module->id]);

        // Module starts locked ('Retake Required' status)
        $moduleProgress = ModuleProgress::create([
            'student_id' => $this->student->id,
            'module_id' => $this->module->id,
            'status' => 'Retake Required',
            'attempts_since_retake' => 4
        ]);

        $this->actingAs($this->student, 'student');

        // Complete topic 1
        $response1 = $this->postJson(route('student.topic.progress'), [
            'topic_id' => $topic1->id,
            'status' => 'Completed'
        ]);
        $response1->assertStatus(200);

        // Verify module still locked
        $moduleProgress->refresh();
        $this->assertEquals('Retake Required', $moduleProgress->status);
        $this->assertEquals(4, $moduleProgress->attempts_since_retake);

        // Complete topic 2
        $response2 = $this->postJson(route('student.topic.progress'), [
            'topic_id' => $topic2->id,
            'status' => 'Completed'
        ]);
        $response2->assertStatus(200);

        // Verify module is unlocked (status is 'In Progress' and attempts reset to 0)
        $moduleProgress->refresh();
        $this->assertEquals('In Progress', $moduleProgress->status);
        $this->assertEquals(0, $moduleProgress->attempts_since_retake);

        // Verify unlocked notification is created
        $this->assertDatabaseHas('notifications', [
            'student_id' => $this->student->id,
            'title' => 'Module Unlocked',
            'type' => 'success'
        ]);
    }
}
