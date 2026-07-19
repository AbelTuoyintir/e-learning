<?php

namespace Tests\Unit;

use App\Services\AIService;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AIServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_ask_uses_openai_if_api_key_is_present()
    {
        Http::fake([
            'api.openai.com/*' => Http::response(['choices' => [['message' => ['content' => 'OpenAI response']]]], 200),
        ]);

        config(['services.openai.key' => 'test-key']);

        $service = new AIService();

        $response = $service->ask('Hello');

        $this->assertEquals('OpenAI response', $response['text']);
        $this->assertEquals('OpenAI', $response['provider']);
    }

    public function test_ask_falls_back_to_ollama_on_openai_failure()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([], 500),
            'ollama.com/api/*' => Http::response(['response' => 'Ollama response'], 200),
        ]);

        config([
            'services.openai.key' => 'test-key',
            'services.ollama.url' => 'https://ollama.com'
        ]);

        $service = new AIService();

        $response = $service->ask('Hello');

        $this->assertEquals('Ollama response', $response['text']);
        $this->assertEquals('Ollama', $response['provider']);
    }

    public function test_ask_injects_context_into_prompt()
    {
        Http::fake([
            'api.openai.com/*' => Http::response(['choices' => [['message' => ['content' => 'Contextual response']]]], 200),
        ]);

        config(['services.openai.key' => 'test-key']);

        $course = Course::create(['title' => 'Biology 101']);
        $module = Module::create(['title' => 'Cell Structure', 'course_id' => $course->id]);

        $service = new AIService();

        $service->ask('Tell me more', [
            'course_id' => $course->id,
            'module_id' => $module->id
        ]);

        Http::assertSent(function ($request) {
            $prompt = $request['messages'][1]['content'];
            return str_contains($prompt, 'Biology 101') && str_contains($prompt, 'Cell Structure');
        });
    }
}
