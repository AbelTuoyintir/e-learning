<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Course;

class AdminPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard_with_metrics(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $student = Student::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);

        $course = Course::create([
            'title' => 'Sample Computer Science Course',
            'description' => 'Course description',
            'duration' => 10,
            'price' => 0,
            'category' => 'Technology',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin, 'web')
                         ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas([
            'activeStudents',
            'courseCompletionRate',
            'modulePassRate',
            'averageScore',
            'aiUsageStats',
            'courseCount',
            'moduleCount',
            'mostDifficultTopics',
        ]);
        $response->assertSee('Admin Control Center');
        $response->assertSee('Active Students');
        $response->assertSee('AI Engine');
    }

    public function test_admin_can_access_student_roster(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $student = Student::create([
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'email' => 'jane.smith@example.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin, 'web')
                         ->get(route('students.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.students');
        $response->assertSee('Jane Smith');
        $response->assertSee('jane.smith@example.com');
    }
}
