<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Module;
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
        $enrichedPrompt = $this->buildContextPrompt($prompt, $context);

        if ($this->openaiApiKey) {
            try {
                return $this->askOpenAI($enrichedPrompt, $context);
            } catch (\Exception $e) {
                Log::error('OpenAI Error: ' . $e->getMessage());
                return $this->askOllama($enrichedPrompt, $context);
            }
        }

        return $this->askOllama($enrichedPrompt, $context);
    }

    protected function buildContextPrompt($prompt, $context)
    {
        $contextInfo = "";

        if (!empty($context['course_id'])) {
            $course = Course::find($context['course_id']);
            if ($course) {
                $contextInfo .= "Course: {$course->title}. ";
            }
        }

        if (!empty($context['module_id'])) {
            $module = Module::find($context['module_id']);
            if ($module) {
                $contextInfo .= "Module: {$module->title}. ";
            }
        }

        if ($contextInfo) {
            return "Context - {$contextInfo}\nQuestion: {$prompt}";
        }

        return $prompt;
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

        return [
            'text' => $response->json()['choices'][0]['message']['content'],
            'provider' => 'OpenAI'
        ];
    }

    protected function askOllama($prompt, $context)
    {
        // Fallback to Ollama
        try {
            $ollamaModel = config('services.ollama.model', env('OLLAMA_MODEL', 'llama2'));

            $url = rtrim($this->ollamaBaseUrl, '/');
            if (!str_ends_with($url, '/api/generate')) {
                $url .= '/api/generate';
            }

            $headers = [];
            $ollamaKey = config('services.ollama.key', env('OLLAMA_API_KEY'));
            if (!empty($ollamaKey)) {
                $headers['Authorization'] = 'Bearer ' . $ollamaKey;
            }

            $response = Http::timeout(30)
                ->withHeaders($headers)
                ->post($url, [
                    'model' => $ollamaModel,
                    'prompt' => $prompt,
                    'stream' => false,
                ]);


            if ($response->failed()) {
                return [
                    'text' => "I'm sorry, I'm having trouble connecting to my AI engines. Please try again later.",
                    'provider' => 'None'
                ];
            }

            return [
                'text' => $response->json()['response'],
                'provider' => 'Ollama'
            ];
        } catch (\Exception $e) {
            Log::error('Ollama Error: ' . $e->getMessage());
            return [
                'text' => "I'm sorry, I'm having trouble connecting to my AI engines. Please try again later.",
                'provider' => 'None'
            ];
        }
    }
}
