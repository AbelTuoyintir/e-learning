<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StudentRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }

    public function test_student_can_sign_up_and_is_authenticated(): void
    {
        $response = $this->post(route('student.register.submit'), [
            'firstname' => 'Taylor',
            'middlename' => 'A',
            'lastname' => 'Student',
            'email' => 'taylor@example.com',
            'phone' => '5551234567',
            'program' => 'Computer Science',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $student = Student::where('email', 'taylor@example.com')->firstOrFail();

        $response->assertRedirect(route('students.dashboard'));
        $this->assertAuthenticatedAs($student, 'student');
        $this->assertTrue(Hash::check('password123', $student->password));
        $this->assertSame('Computer Science', $student->Program);
    }

    public function test_student_signup_rejects_duplicate_emails(): void
    {
        Student::create([
            'firstname' => 'Existing',
            'lastname' => 'Student',
            'email' => 'existing@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->from(route('student.register'))->post(route('student.register.submit'), [
            'firstname' => 'Another',
            'lastname' => 'Student',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('student.register'));
        $response->assertSessionHasErrors('email');
        $this->assertSame(1, Student::count());
    }

    public function test_admin_can_open_student_creation_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin, 'web')
            ->get(route('admin.students.create'))
            ->assertOk()
            ->assertSee('Add Student')
            ->assertSee(route('admin.students.store'));
    }

    public function test_admin_can_create_a_student(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Mail::fake();
        Http::fake();

        $response = $this->actingAs($admin, 'web')->post(route('admin.students.store'), [
            'firstname' => 'Morgan',
            'lastname' => 'Learner',
            'email' => 'morgan@example.com',
            'phone' => '5551234567',
            'program' => 'Information Technology',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('students', [
            'firstname' => 'Morgan',
            'email' => 'morgan@example.com',
            'Program' => 'Information Technology',
        ]);

        $student = Student::where('email', 'morgan@example.com')->firstOrFail();
        $this->assertNotEmpty($student->index_number);
    }

    public function test_guest_cannot_open_admin_student_creation_form(): void
    {
        $this->get(route('admin.students.create'))
            ->assertRedirect(route('login'));
    }
}