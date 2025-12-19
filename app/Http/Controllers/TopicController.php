<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Module;
use App\Models\TopicContent; // Fixed import name
use App\Models\Question; // Add this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
// Remove the wrong import: use Illuminate\Database\Eloquent\pagination;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $topics = Topic::with('module.course')->latest()->paginate(10);
        $topicContent = TopicContent::all();
        return view('admin.topics.index', compact('topics'));
    }

    /**
     * Show the form for creating a new resource.
     */
       public function create(Module $module)
    {
        // Get existing topics for this module with their relationships
        $topics = Topic::where('module_id', $module->id)
                      ->with(['module.course', 'contents', 'quiz.questions'])
                      ->orderBy('order')
                      ->paginate(10);

        // Get existing quizzes for this module
        $quizzes = \App\Models\Quiz::where('module_id', $module->id)->get();

        return view('course.topic', compact('module', 'topics', 'quizzes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'video_url' => 'nullable|url',
            'quiz_id' => 'nullable|exists:quizzes,id',
            'contents' => 'nullable|array',
            'questions' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Create the topic
            $topic = Topic::create([
                'module_id' => $request->module_id,
                'title' => $request->title,
                'order' => $request->order ?? 0,
                'is_active' => $request->is_active ?? true,
            ]);

            return redirect()->route('admin.topics.create')->with('success', 'Topic created successfully!');

        } catch (\Exception $e) {
            \Log::error('Topic creation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to create topic: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Topic $topic)
    {


        return view('course.topic', compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic)
    {
        $modules = Module::where('is_active', true)->get();
        $topic->load('contents', 'quiz.questions'); // Load relationships for editing
        return view('course.editTopic', compact('topic', 'modules'));
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, $id): RedirectResponse
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'required|boolean',
                'module_id' => 'required|exists:modules,id'
            ]);

            // Find the topic
            $topic = Topic::findOrFail($id);

            // Update the topic
            $topic->update($validated);

            // For web requests, use redirect instead of JSON
           return redirect()->back()->with('success', 'Topic updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Topic update failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to update topic: ' . $e->getMessage())->withInput();
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic)
    {
        try {
            // Delete associated file
            if ($topic->file_path) {
                Storage::disk('public')->delete($topic->file_path);
            }

            // Delete associated contents and questions
            $topic->contents()->delete();
            if ($topic->quiz) {
                $topic->quiz->questions()->delete();
            }

            $topic->delete();

            // For web requests, use redirect instead of JSON
            return redirect()->route('admin.topics.index')
                           ->with('success', 'Topic deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('admin.topics.index')
                           ->with('error', 'Failed to delete topic: ' . $e->getMessage());
        }
    }

    /**
     * Get topics by module
     */
    public function byModule(Module $module)
    {
        $topics = $module->topics()
                        ->where('is_active', true)
                        ->orderBy('order')
                        ->get();

        return response()->json([
            'success' => true,
            'topics' => $topics
        ]);
    }

    /**
     * Update topic order
     */
    public function updateOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topics' => 'required|array',
            'topics.*.id' => 'required|exists:topics,id',
            'topics.*.order' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            foreach ($request->topics as $topicData) {
                Topic::where('id', $topicData['id'])->update(['order' => $topicData['order']]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Topic order updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update topic order: ' . $e->getMessage()
            ], 500);
        }
    }


}
