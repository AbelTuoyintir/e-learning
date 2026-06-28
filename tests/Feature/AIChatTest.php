<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\AIChatSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AIChatTest extends TestCase
{
    use RefreshDatabase;

    protected $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->student = Student::create([
            'firstname' => 'Test',
            'lastname' => 'Student',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_student_can_chat_with_ai()
    {
        Http::fake([
            'api.openai.com/*' => Http::response(['choices' => [['message' => ['content' => 'AI Response']]]], 200),
        ]);

        config(['services.openai.key' => 'test-key']);

        $response = $this->actingAs($this->student, 'student')
            ->postJson(route('student.ai.chat'), [
                'question' => 'What is photosynthesis?'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'response' => 'AI Response',
                'provider' => 'OpenAI'
            ]);

        $this->assertDatabaseHas('ai_chat_sessions', [
            'student_id' => $this->student->id,
            'question' => 'What is photosynthesis?',
            'response' => 'AI Response',
            'provider' => 'OpenAI'
        ]);
    }

    public function test_student_can_view_chat_history()
    {
        AIChatSession::create([
            'student_id' => $this->student->id,
            'question' => 'How are you?',
            'response' => 'I am fine.',
            'provider' => 'OpenAI'
        ]);

        $response = $this->actingAs($this->student, 'student')
            ->getJson(route('student.ai.history'));

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment([
                'question' => 'How are you?',
                'response' => 'I am fine.'
            ]);
    }
}
