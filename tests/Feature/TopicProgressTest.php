<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Module;
use App\Models\LearningHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TopicProgressTest extends TestCase
{
    use RefreshDatabase;

    public function test_topic_progress_update_records_learning_history()
    {
        $student = Student::create([
            'firstname' => 'Test',
            'lastname' => 'Student',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
        ]);

        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Test Description',
        ]);

        $module = Module::create([
            'course_id' => $course->id,
            'title' => 'Test Module',
        ]);

        $topic = Topic::create([
            'module_id' => $module->id,
            'title' => 'Test Topic',
            'order' => 1,
        ]);

        $response = $this->actingAs($student, 'student')
            ->postJson(route('student.topic-progress'), [
                'topic_id' => $topic->id,
                'status' => 'Completed',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('topic_progress', [
            'student_id' => $student->id,
            'topic_id' => $topic->id,
            'status' => 'Completed',
        ]);

        $this->assertDatabaseHas('learning_history', [
            'student_id' => $student->id,
            'activity_type' => 'topic_status_updated',
            'related_id' => $topic->id,
            'related_type' => 'topic',
        ]);
    }
}
