<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $openaiApiKey;
    protected $ollamaBaseUrl;

    public function __construct()
    {
        $this->openaiApiKey = config('services.openai.key');
        $this->ollamaBaseUrl = config('services.ollama.url', 'https://api.ollama.cloud');
    }

    public function ask($prompt, $context = [])
    {
        if ($this->openaiApiKey) {
            try {
                return $this->askOpenAI($prompt, $context);
            } catch (\Exception $e) {
                Log::error('OpenAI Error: ' . $e->getMessage());
                return $this->askOllama($prompt, $context);
            }
        }

        return $this->askOllama($prompt, $context);
    }

    protected function askOpenAI($prompt, $context)
    {
        $response = Http::withToken($this->openaiApiKey)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an AI academic tutor. Help the student understand concepts, provide examples, and explain mistakes. Do not complete assignments for them directly.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        if ($response->failed()) {
            throw new \Exception('OpenAI API request failed');
        }

        return $response->json()['choices'][0]['message']['content'];
    }

    protected function askOllama($prompt, $context)
    {
        // Fallback to Ollama
        $response = Http::post($this->ollamaBaseUrl . '/api/generate', [
            'model' => 'llama2',
            'prompt' => $prompt,
            'stream' => false,
        ]);

        if ($response->failed()) {
            return "I'm sorry, I'm having trouble connecting to my AI engines. Please try again later.";
        }

        return $response->json()['response'];
    }
}
