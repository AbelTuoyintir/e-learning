<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Topic;
use App\Models\Module;
use App\Models\Course;
use App\Models\TopicProgress;
use App\Models\LearningHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopicProgressTest extends TestCase
{
    use RefreshDatabase;

    protected $student;
    protected $topic;

    protected function setUp(): void
    {
        parent::setUp();
        $this->student = Student::create([
            'firstname' => 'Test',
            'lastname' => 'Student',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
        ]);

        $course = Course::create(['title' => 'Test Course', 'description' => 'Test Description']);
        $module = Module::create(['course_id' => $course->id, 'title' => 'Test Module']);
        $this->topic = Topic::create(['module_id' => $module->id, 'title' => 'Test Topic']);
    }

    public function test_student_can_update_topic_progress()
    {
        $response = $this->actingAs($this->student, 'student')
            ->postJson(route('student.topic-progress.update'), [
                'topic_id' => $this->topic->id,
                'status' => 'Completed'
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('topic_progress', [
            'student_id' => $this->student->id,
            'topic_id' => $this->topic->id,
            'status' => 'Completed'
        ]);

        $this->assertDatabaseHas('learning_history', [
            'student_id' => $this->student->id,
            'activity_type' => 'topic_status_updated',
            'related_id' => $this->topic->id,
            'related_type' => 'topic',
        ]);
    }
}
