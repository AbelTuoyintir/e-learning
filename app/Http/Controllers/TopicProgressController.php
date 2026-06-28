<?php

namespace App\Http\Controllers;

use App\Models\TopicProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicProgressController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'status' => 'required|in:Not Started,In Progress,Completed',
        ]);

        $progress = TopicProgress::updateOrCreate(
            ['student_id' => Auth::id(), 'topic_id' => $request->topic_id],
            ['status' => $request->status]
        );

        // Progress Milestone Notification
        $totalTopicsCount = \App\Models\Topic::count();
        $completedTopicsCount = TopicProgress::where('student_id', Auth::id())
            ->where('status', 'Completed')
            ->count();

        if ($totalTopicsCount > 0) {
            $percentage = ($completedTopicsCount / $totalTopicsCount) * 100;
            $milestones = [25, 50, 75, 100];
            foreach ($milestones as $milestone) {
                if (abs($percentage - $milestone) < 0.1) {
                    \App\Models\Notification::create([
                        'student_id' => Auth::id(),
                        'title' => 'Progress Milestone!',
                        'message' => "Great job! You have completed {$milestone}% of all topics.",
                        'type' => 'info',
                    ]);
                }
            }
        }

        \App\Models\LearningHistory::create([
            'student_id' => Auth::id(),
            'activity_type' => 'topic_status_updated',
            'related_id' => $request->topic_id,
            'related_type' => 'App\Models\Topic',
            'description' => "Marked topic as '{$request->status}'",
            'metadata' => ['status' => $request->status],
        ]);

        // Check if all topics in the module are completed to unlock a "Retake Required" module
        $topic = \App\Models\Topic::find($request->topic_id);
        if ($topic && $request->status === 'Completed') {
            $moduleId = $topic->module_id;
            $totalTopicsInModule = \App\Models\Topic::where('module_id', $moduleId)->count();
            $completedTopicsInModule = TopicProgress::where('student_id', Auth::id())
                ->whereHas('topic', function($q) use ($moduleId) {
                    $q->where('module_id', $moduleId);
                })
                ->where('status', 'Completed')
                ->count();

            if ($completedTopicsInModule === $totalTopicsInModule) {
                $moduleProgress = \App\Models\ModuleProgress::where('student_id', Auth::id())
                    ->where('module_id', $moduleId)
                    ->where('status', 'Retake Required')
                    ->first();
                if ($moduleProgress) {
                    $moduleProgress->update([
                        'status' => 'In Progress',
                        'attempts_since_retake' => 0,
                    ]);

                    \App\Models\Notification::create([
                        'student_id' => Auth::id(),
                        'title' => 'Module Unlocked',
                        'message' => "You have completed reviewing all topics for '{$topic->module->title}'. You can now reattempt the assessment.",
                        'type' => 'success',
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'progress' => $progress,
        ]);
    }
}
