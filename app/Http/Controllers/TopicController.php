<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Module;
use App\Models\TopicContent;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        $relations = ['module.course', 'contents', 'quiz.questions'];
        if (Schema::hasTable('topic_videos')) {
            $relations[] = 'videos';
        }

        // Get existing topics for this module with their relationships
        $topics = Topic::where('module_id', $module->id)
                      ->with($relations)
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
            'module_id'    => 'required|exists:modules,id',
            'title'        => 'required|string|max:255',
            'order'        => 'nullable|integer|min:0',
            'is_active'    => 'boolean',
            'video_urls'   => 'nullable|array',
            'video_urls.*' => [
                'nullable',
                'url',
                function ($attribute, $value, $fail) {
                    if ($value && ! $this->isYoutubeUrl($value)) {
                        $fail('Each video must be a valid YouTube URL (youtube.com or youtu.be).');
                    }
                },
            ],
            'document'     => 'nullable|file|mimes:pdf|max:1048576', // 1GB (in KB)
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $filePath = null;

        try {
            $fileName = null;

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $filePath = $file->store('topic-materials', 'local');
                $fileName = $file->getClientOriginalName();
            }

            DB::transaction(function () use ($request, $filePath, $fileName) {
                $urls = collect($request->input('video_urls', []))
                    ->filter()   // drop empty inputs
                    ->unique()
                    ->values();

                $topic = Topic::create([
                    'module_id' => $request->module_id,
                    'title'     => $request->title,
                    'order'     => $request->order ?? 0,
                    'is_active' => $request->boolean('is_active', true),
                    'file_path' => $filePath,
                    'file_name' => $fileName,
                    'video_url' => $urls->first(),
                ]);

                if (Schema::hasTable('topic_videos')) {
                    foreach ($urls as $index => $url) {
                        $topic->videos()->create([
                            'url'   => $url,
                            'order' => $index,
                        ]);
                    }
                }
            });

            return redirect()->route('admin.topics.create', $request->module_id)
                ->with('success', 'Topic created successfully!');

        } catch (\Exception $e) {
            // Don't leave an orphaned upload behind if the DB write failed
            if ($filePath) {
                Storage::disk('local')->delete($filePath);
            }

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
        $relations = ['contents', 'quiz.questions'];
        if (Schema::hasTable('topic_videos')) {
            $relations[] = 'videos';
        }
        $topic->load($relations); // Load relationships for editing
        return view('course.editTopic', compact('topic', 'modules'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Topic $topic)
    {
        $validator = Validator::make($request->all(), [
            'module_id'    => 'required|exists:modules,id',
            'title'        => 'required|string|max:255',
            'order'        => 'nullable|integer|min:0',
            'is_active'    => 'boolean',
            'video_urls'   => 'nullable|array',
            'video_urls.*' => [
                'nullable',
                'url',
                function ($attribute, $value, $fail) {
                    if ($value && ! $this->isYoutubeUrl($value)) {
                        $fail('Each video must be a valid YouTube URL (youtube.com or youtu.be).');
                    }
                },
            ],
            'document'     => 'nullable|file|extensions:pdf,pptx|max:1048576', // 1GB in KB
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $newPath = null;

        try {
            $data = [
                'module_id' => $request->module_id,
                'title'     => $request->title,
                'order'     => $request->order ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ];

            $oldPath = $topic->file_path;

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $newPath = $file->store('topic-materials', 'local');
                $data['file_path'] = $newPath;
                $data['file_name'] = $file->getClientOriginalName();
            }

            DB::transaction(function () use ($request, $topic, $data) {
                $urls = collect($request->input('video_urls', []))
                    ->filter()->unique()->values();

                $data['video_url'] = $urls->first();
                $topic->update($data);

                if (Schema::hasTable('topic_videos')) {
                    $topic->videos()->delete();
                    foreach ($urls as $index => $url) {
                        $topic->videos()->create(['url' => $url, 'order' => $index]);
                    }
                }
            });

            // Only delete the old file after the DB update succeeded
            if ($newPath && $oldPath) {
                Storage::disk('local')->delete($oldPath);
            }

           

           
            $redirect = route('admin.topics.create', $request->module_id);

            if ($request->expectsJson()) {
                session()->flash('success', 'Topic created successfully!');
                return response()->json(['redirect' => $redirect]);
            }

            return redirect($redirect)->with('success', 'Topic created successfully!');

        } catch (\Exception $e) {
            if ($newPath) {
                Storage::disk('local')->delete($newPath);
            }
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

            // Delete associated contents, videos, and questions
            $topic->contents()->delete();
            if (Schema::hasTable('topic_videos')) {
                $topic->videos()->delete();
            }
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
