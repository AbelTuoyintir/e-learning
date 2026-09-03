<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\Module;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminInterfaceTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        config(['auth.defaults.guard' => 'web']);

        // Create an admin user with role = 'admin'
        $this->adminUser = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }

    public function test_guest_cannot_access_admin_dashboard_or_students()
    {
        $responseDashboard = $this->get(route('admin.dashboard'));
        $responseDashboard->assertRedirect();

        $responseStudents = $this->get(route('students.index'));
        $responseStudents->assertRedirect();
    }

    public function test_authenticated_admin_can_access_dashboard_and_view_metrics()
    {
        // Seed some data
        Student::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);

        Course::create(['title' => 'Sample Course']);

        $this->actingAs($this->adminUser, 'web');

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertSee('Welcome back, System Administrator!');
        $response->assertSee('Admin Control Center');
        $response->assertSee('Active Students');
        $response->assertSee('Course Completion Rate');
        $response->assertSee('Module Pass Rate');
        $response->assertSee('AI Tutor Sessions');
    }

    public function test_authenticated_admin_can_access_students_management()
    {
        $student = Student::create([
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'program' => 'Computer Science',
            'status' => 'active',
        ]);

        $this->actingAs($this->adminUser, 'web');

        $response = $this->get(route('students.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.students');
        $response->assertSee('Jane Smith');
        $response->assertSee('jane@example.com');
        $response->assertSee('Computer Science');
    }
}
