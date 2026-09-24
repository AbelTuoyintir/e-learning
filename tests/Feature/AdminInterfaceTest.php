<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\Quiz;

class AdminInterfaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard_and_see_metrics(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $student = Student::create([
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'status' => 'active'
        ]);

        $course = Course::create([
            'title' => 'Advanced Machine Learning',
            'description' => 'Comprehensive AI course',
            'duration' => 20,
            'price' => 100,
            'category' => 'Technology'
        ]);

        $quiz = Quiz::create([
            'course_id' => $course->id,
            'quiz_type' => 'module_assessment',
            'title' => 'ML Basics Quiz',
            'description' => 'Test basic concepts',
            'time_limit' => 30,
            'time_per_question' => 30,
            'question_limit' => 60,
            'passing_score' => 70,
            'max_attempts' => 4,
            'difficulty' => 'medium'
        ]);

        $response = $this->actingAs($admin, 'web')->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Admin Control Center');
        $response->assertSee('Active Students');
        $response->assertSee('Pass Rate');
        $response->assertSee('Average Score');
        $response->assertSee('AI Interactions');
    }

    public function test_admin_can_access_student_roster(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        Student::create([
            'firstname' => 'Alice',
            'lastname' => 'Smith',
            'email' => 'alice@example.com',
            'password' => bcrypt('password'),
            'status' => 'active'
        ]);

        $response = $this->actingAs($admin, 'web')->get(route('students.index'));

        $response->assertStatus(200);
        $response->assertSee('Manage Students');
        $response->assertSee('Alice Smith');
    }

    public function test_admin_can_access_quizzes_management(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $response = $this->actingAs($admin, 'web')->get(route('quizzes.index'));

        $response->assertStatus(200);
        $response->assertSee('Quiz Management');
    }
}
