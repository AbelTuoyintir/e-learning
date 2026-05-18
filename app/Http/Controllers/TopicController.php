<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Module;
use App\Models\TopicContent;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            'document' => 'nullable|file|mimes:pdf,pptx|max:10240',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            if ($request->filled('video_url') && ! $this->isYoutubeUrl($request->video_url)) {
                return back()->withErrors([
                    'video_url' => 'Please provide a valid YouTube URL (youtube.com or youtu.be).',
                ])->withInput();
            }

            $filePath = null;
            $fileName = null;

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $filePath = $file->store('topic-materials', 'local');
                $fileName = $file->getClientOriginalName();
            }

            // Create the topic
            Topic::create([
                'module_id' => $request->module_id,
                'title' => $request->title,
                'order' => $request->order ?? 0,
                'is_active' => $request->is_active ?? true,
                'video_url' => $request->video_url,
                'file_path' => $filePath,
                'file_name' => $fileName,
            ]);

            return redirect()->route('admin.topics.create', $request->module_id)
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
                'order' => 'nullable|integer|min:0',
                'is_active' => 'required|boolean',
                'module_id' => 'required|exists:modules,id',
                'video_url' => 'nullable|url',
                'document' => 'nullable|file|mimes:pdf,pptx|max:10240',
            ]);

            // Find the topic
            $topic = Topic::findOrFail($id);

            if ($request->filled('video_url') && ! $this->isYoutubeUrl($request->video_url)) {
                return back()->withErrors([
                    'video_url' => 'Please provide a valid YouTube URL (youtube.com or youtu.be).',
                ])->withInput();
            }

            if ($request->hasFile('document')) {
                $this->deleteTopicFileIfExists($topic->file_path);

                $file = $request->file('document');
                $validated['file_path'] = $file->store('topic-materials', 'local');
                $validated['file_name'] = $file->getClientOriginalName();
            }

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
            $this->deleteTopicFileIfExists($topic->file_path);

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

    private function isYoutubeUrl(string $url): bool
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (! $host) {
            return false;
        }

        $normalizedHost = Str::lower($host);
        $normalizedHost = str_replace('www.', '', $normalizedHost);

        return in_array($normalizedHost, ['youtube.com', 'm.youtube.com', 'youtu.be'], true)
            || Str::endsWith($normalizedHost, '.youtube.com');
    }

    private function deleteTopicFileIfExists(?string $filePath): void
    {
        if (! $filePath) {
            return;
        }

        if (Storage::disk('local')->exists($filePath)) {
            Storage::disk('local')->delete($filePath);
            return;
        }

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }


}
