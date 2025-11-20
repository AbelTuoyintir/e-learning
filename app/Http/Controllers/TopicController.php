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
        return view('admin.topics.index', compact('topics'));
    }

    /**
     * Show the form for creating a new resource.
     */
       public function create(Module $module)
    {
        // Get existing topics for this module with their relationships
        $topics = Topic::where('module_id', $module->id)
                      ->with(['module.course', 'contents', 'questions'])
                      ->orderBy('order')
                      ->paginate(10);

        return view('course.topic', compact('module', 'topics'));
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
                'video_url' => $request->video_url,
            ]);

            // Create topic contents - use TopicContent instead of Topic_content
            if ($request->contents) {
                foreach ($request->contents as $index => $contentData) {
                    $filePath = null;
                    $fileName = null;

                    // Handle file upload
                    if (isset($contentData['file']) && $contentData['file'] instanceof \Illuminate\Http\UploadedFile) {
                        $file = $contentData['file'];
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $filePath = $file->storeAs('topics/content', $fileName, 'public');
                    }

                    // Use TopicContent model (proper PascalCase)
                    TopicContent::create([
                        'topic_id' => $topic->id,
                        'type' => $contentData['type'],
                        'body' => $contentData['body'] ?? null,
                        'file_path' => $filePath,
                        'file_name' => $fileName,
                        'order' => $index,
                    ]);
                }
            }

            // Create questions
            if ($request->questions) {
                foreach ($request->questions as $index => $questionData) {
                    Question::create([
                        'topic_id' => $topic->id,
                        'text' => $questionData['text'],
                        'options' => $questionData['options'] ?? [],
                        'correct_option' => $questionData['correct'] ?? 0,
                        'order' => $index,
                    ]);
                }
            }

            return redirect()->route('admin.topics.index')
                           ->with('success', 'Topic created successfully!');

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
        $topic->load('contents', 'questions'); // Load relationships for editing
        return view('admin.topics.edit', compact('topic', 'modules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic)
    {
        $validator = Validator::make($request->all(), [
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'file' => 'nullable|file|mimes:pdf,mp4,mov,avi,jpg,jpeg,png,gif|max:10240',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            // For web requests, use redirect back instead of JSON
            return back()->withErrors($validator)->withInput();
        }

        try {
            $filePath = $topic->file_path;
            $fileName = $topic->file_name;

            // Handle file upload if new file provided
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                // Delete old file if exists
                if ($topic->file_path) {
                    Storage::disk('public')->delete($topic->file_path);
                }

                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('topics/files', $fileName, 'public');
            }

            // Update the topic
            $topic->update([
                'module_id' => $request->module_id,
                'title' => $request->title,
                'content' => $request->content,
                'video_url' => $request->video_url,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'order' => $request->order ?? $topic->order,
                'is_active' => $request->is_active ?? $topic->is_active,
            ]);

            // For web requests, use redirect instead of JSON
            return redirect()->route('admin.topics.index')
                           ->with('success', 'Topic updated successfully!');

        } catch (\Exception $e) {
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
            $topic->questions()->delete();

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
