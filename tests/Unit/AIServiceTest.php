<?php

namespace Tests\Unit;

use App\Services\AIService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AIServiceTest extends TestCase
{
    public function test_ask_uses_openai_if_api_key_is_present()
    {
        Http::fake([
            'api.openai.com/*' => Http::response(['choices' => [['message' => ['content' => 'OpenAI response']]]], 200),
        ]);

        config(['services.openai.key' => 'test-key']);

        $service = new AIService();

        $response = $service->ask('Hello');

        $this->assertEquals('OpenAI response', $response);
    }

    public function test_ask_falls_back_to_ollama_on_openai_failure()
    {
        Http::fake([
            'api.openai.com/*' => Http::response([], 500),
            'api.ollama.cloud/*' => Http::response(['response' => 'Ollama response'], 200),
        ]);

        config(['services.openai.key' => 'test-key']);

        $service = new AIService();

        $response = $service->ask('Hello');

        $this->assertEquals('Ollama response', $response);
    }
}
