<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Module;
use App\Models\ModuleProgress;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Quiz;
use App\Models\Result;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranscriptCertificateTest extends TestCase
{
    use RefreshDatabase;

    protected $student;
    protected $course;
    protected $module;

    protected function setUp(): void
    {
        parent::setUp();

        $this->student = Student::create([
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'email' => 'jane.doe@example.com',
            'password' => bcrypt('password')
        ]);

        $this->course = Course::create([
            'title' => 'Web Development 101'
        ]);

        $this->module = Module::create([
            'title' => 'HTML Basics',
            'course_id' => $this->course->id
        ]);
    }

    public function test_student_can_download_transcript()
    {
        $this->actingAs($this->student, 'student');

        // Create a quiz and result
        $quiz = Quiz::create([
            'title' => 'HTML Quiz',
            'course_id' => $this->course->id,
            'module_id' => $this->module->id,
            'quiz_type' => 'module_assessment',
        ]);

        Result::create([
            'student_id' => $this->student->id,
            'quiz_id' => $quiz->id,
            'score' => 10,
            'total_possible_points' => 10,
            'percentage' => 100,
            'passed' => true,
            'attempt_number' => 1,
            'completed_at' => now()
        ]);

        $response = $this->get(route('download.transcript'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_unenrolled_student_cannot_download_certificate()
    {
        $this->actingAs($this->student, 'student');

        $response = $this->get(route('download.certificate', $this->course));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'You must be enrolled in this course to download the certificate.');
    }

    public function test_enrolled_student_cannot_download_certificate_without_completing_all_modules()
    {
        $this->actingAs($this->student, 'student');

        Enrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id
        ]);

        // Module exists but no ModuleProgress exists yet
        $response = $this->get(route('download.certificate', $this->course));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'You must complete all modules in this course to download your certificate.');
    }

    public function test_enrolled_student_can_download_certificate_after_completing_all_modules()
    {
        $this->actingAs($this->student, 'student');

        Enrollment::create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id
        ]);

        // Complete the module progress
        ModuleProgress::create([
            'student_id' => $this->student->id,
            'module_id' => $this->module->id,
            'status' => 'Completed',
            'attempts_since_retake' => 1
        ]);

        $response = $this->get(route('download.certificate', $this->course));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}