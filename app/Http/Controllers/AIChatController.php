<?php

namespace App\Http\Controllers;

use App\Models\AIChatSession;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AIChatController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'course_id' => 'nullable|exists:courses,id',
            'module_id' => 'nullable|exists:modules,id',
        ]);

        $question = $request->input('question');
        $course_id = $request->input('course_id');
        $module_id = $request->input('module_id');

        $response = $this->aiService->ask($question, [
            'course_id' => $course_id,
            'module_id' => $module_id,
        ]);

        AIChatSession::create([
            'student_id' => Auth::id(),
            'question' => $question,
            'response' => $response,
            'course_id' => $course_id,
            'module_id' => $module_id,
        ]);

        \App\Models\LearningHistory::create([
            'student_id' => Auth::id(),
            'activity_type' => 'ai_chat_session',
            'description' => "Asked AI: " . substr($question, 0, 50) . "...",
            'metadata' => [
                'course_id' => $course_id,
                'module_id' => $module_id,
            ],
        ]);

        return response()->json([
            'response' => $response,
        ]);
    }

    public function history()
    {
        $history = AIChatSession::where('student_id', Auth::id())
            ->with(['course', 'module'])
            ->latest()
            ->get();

        return response()->json($history);
    }
}
