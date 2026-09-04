<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Student;

class AdminInterfaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect();
    }

    public function test_admin_user_can_access_dashboard()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin, 'web')->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Admin Control Center');
        $response->assertSee('Student Roster');
    }

    public function test_admin_user_can_access_students_index()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Student::create([
            'firstname' => 'Alice',
            'lastname' => 'Smith',
            'email' => 'alice@example.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin, 'web')->get(route('students.index'));

        $response->assertStatus(200);
        $response->assertSee('Alice');
        $response->assertSee('Smith');
        $response->assertSee('alice@example.com');
    }
}
