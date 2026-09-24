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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class ModuleRetakeResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_topics_reset_when_module_status_becomes_retake_required()
    {
        // 1. Setup data
        $student = Student::create([
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password')
        ]);

        $course = Course::create(['title' => 'CS 101']);
        $module = Module::create(['title' => 'Logic', 'course_id' => $course->id]);
        $topic1 = Topic::create(['title' => 'Truth Tables', 'module_id' => $module->id]);
        $topic2 = Topic::create(['title' => 'Gates', 'module_id' => $module->id]);

        Enrollment::create(['student_id' => $student->id, 'course_id' => $course->id]);

        $quiz = Quiz::create([
            'title' => 'Logic Assessment',
            'course_id' => $course->id,
            'module_id' => $module->id,
            'quiz_type' => 'module_assessment',
            'passing_score' => 70
        ]);

        Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => 'Sample Question?',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'A',
            'points' => 10,
            'type' => 'MCQ'
        ]);

        // 2. Mark topics as Completed
        TopicProgress::create(['student_id' => $student->id, 'topic_id' => $topic1->id, 'status' => 'Completed']);
        TopicProgress::create(['student_id' => $student->id, 'topic_id' => $topic2->id, 'status' => 'Completed']);

        // 3. Set attempts to 3
        ModuleProgress::create([
            'student_id' => $student->id,
            'module_id' => $module->id,
            'status' => 'In Progress',
            'attempts_since_retake' => 3
        ]);

        // 4. Fail the 4th attempt
        $this->actingAs($student, 'student');

        $response = $this->post(route('quiz.submit', $quiz), [
            'answers' => [
                1 => 'B', // Incorrect answer
            ]
        ]);

        // 5. Verify Module status is 'Retake Required'
        $moduleProgress = ModuleProgress::where('student_id', $student->id)->where('module_id', $module->id)->first();
        $this->assertEquals('Retake Required', $moduleProgress->status);
        $this->assertEquals(4, $moduleProgress->attempts_since_retake);

        // 6. Verify TopicProgress is reset to 'In Progress'
        $tp1 = TopicProgress::where('student_id', $student->id)->where('topic_id', $topic1->id)->first();
        $tp2 = TopicProgress::where('student_id', $student->id)->where('topic_id', $topic2->id)->first();

        $this->assertEquals('In Progress', $tp1->status);
        $this->assertEquals('In Progress', $tp2->status);
    }
}
