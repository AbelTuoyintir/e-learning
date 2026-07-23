<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Module;
use App\Models\ModuleProgress;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Student;
use App\Models\Topic;
use App\Models\TopicProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ModuleRetakeResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_module_attempts_limit_retake_required_and_reunlock_flow()
    {
        // 1. Setup Database: Course, Module, Topic, Quiz, Questions
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
            'title' => 'Introduction to PHP',
            'course_id' => $course->id,
        ]);

        $topic = Topic::create([
            'title' => 'PHP Basics',
            'module_id' => $module->id,
        ]);

        $quiz = Quiz::create([
            'title' => 'PHP Basics Assessment',
            'course_id' => $course->id,
            'module_id' => $module->id,
            'time_limit' => 30,
            'passing_score' => 70,
            'quiz_type' => 'module_assessment',
        ]);

        // Add a question to the quiz
        $question = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'What is PHP?',
            'option_a' => 'Hypertext Preprocessor',
            'option_b' => 'Personal Home Page',
            'option_c' => 'Preprocessed Hypertext',
            'option_d' => 'None of the above',
            'correct_option' => 'A',
            'points' => 10,
            'type' => 'mcq',
        ]);

        config(['auth.defaults.guard' => 'student']);
        $this->actingAs($student, 'student');

        // Mark the topic as completed initially
        TopicProgress::create([
            'student_id' => $student->id,
            'topic_id' => $topic->id,
            'status' => 'Completed',
        ]);

        // Fail attempt 1
        $this->get("/quiz/{$quiz->id}");
        $response = $this->post("/quiz/{$quiz->id}/submit", [
            'answers' => [
                $question->id => 'B', // Incorrect answer
            ],
        ]);
        $response->assertRedirect();

        $moduleProgress = ModuleProgress::where('student_id', $student->id)
            ->where('module_id', $module->id)
            ->first();

        $this->assertEquals(1, $moduleProgress->attempts_since_retake);
        $this->assertEquals('Not Started', $moduleProgress->status);

        // Fail attempt 2
        $this->get("/quiz/{$quiz->id}");
        $this->post("/quiz/{$quiz->id}/submit", ['answers' => [$question->id => 'B']]);
        // Fail attempt 3
        $this->get("/quiz/{$quiz->id}");
        $this->post("/quiz/{$quiz->id}/submit", ['answers' => [$question->id => 'B']]);

        $moduleProgress->refresh();
        $this->assertEquals(3, $moduleProgress->attempts_since_retake);

        // Fail attempt 4 (final attempt)
        $this->get("/quiz/{$quiz->id}");
        $response = $this->post("/quiz/{$quiz->id}/submit", [
            'answers' => [
                $question->id => 'B',
            ],
        ]);

        $moduleProgress->refresh();
        $this->assertEquals('Retake Required', $moduleProgress->status);

        // Check that the topic progress was reset to 'In Progress'
        $topicProgress = TopicProgress::where('student_id', $student->id)
            ->where('topic_id', $topic->id)
            ->first();
        $this->assertEquals('In Progress', $topicProgress->status);

        // Attempt 5 should be blocked
        $response = $this->get("/quiz/{$quiz->id}");
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Module retake required. Please review all module topics before attempting the assessment again.');

        // Direct submission of attempt 5 should also be blocked
        $response = $this->post("/quiz/{$quiz->id}/submit", [
            'answers' => [
                $question->id => 'A',
            ],
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Module retake required. Please review all module topics before attempting the assessment again.');

        // 5. Complete all module topics again
        $response = $this->postJson('/student/topic-progress', [
            'topic_id' => $topic->id,
            'status' => 'Completed',
        ]);
        $response->assertStatus(200);

        $moduleProgress->refresh();
        $this->assertEquals('In Progress', $moduleProgress->status);
        $this->assertEquals(0, $moduleProgress->attempts_since_retake);

        // Now we should be able to start the quiz again!
        $response = $this->get("/quiz/{$quiz->id}");
        $response->assertStatus(200);
    }
}
