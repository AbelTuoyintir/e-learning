<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::all();
        $modules = \App\Models\Module::all();
        $topics = \App\Models\Topic::all();

        return view('manage.manageQuiz', [
            'quizzes' => $quizzes
        ]);
    }

    public function create()
    {
        $courses = Course::all();
        $modules = \App\Models\Module::all();
        $topics = \App\Models\Topic::all();
        return view('manage.create', [
            'quiz' => new Quiz(),
            'courses' => $courses,
            'modules'=> $modules,
            'topics' => $topics,
            'submitText' => 'Create Quiz'
        ]);
    }

    public function edit(Quiz $quiz) // Fixed: Added parameter
    {
        $courses = Course::all();
        $modules = \App\Models\Module::all();
        $topics = \App\Models\Topic::all();

        return view('manage.editQuiz', [
            'quiz' => $quiz, // Fixed: Now $quiz is defined
            'courses' => $courses,
            'modules'=> $modules,
            'topics' => $topics,
            'submitText' => 'Update Quiz'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'time_limit' => 'required|integer|min:1',
            'time_per_question' => 'required|integer|min:5',
            'quiz_type' => 'nullable|string|max:255', 
            'course_id' => 'required|exists:courses,id',
            'module_id' => 'nullable|exists:modules,id',
            'topic_id' => 'nullable|exists:topics,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('quiz_images', 'public');
        }

        Quiz::create($validated);
        return redirect()->route('quizzes.index')
                        ->with('success', 'Quiz created successfully!');
    }

    public function update(Request $request, Quiz $quiz) // Fixed: Added parameter and type-hinted
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'time_limit' => 'required|integer|min:1',
            'time_per_question' => 'required|integer|min:5',
            'course_id' => 'required|exists:courses,id',
            'module_id' => 'nullable|exists:modules,id',
            'topic_id' => 'nullable|exists:topics,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($quiz->image && Storage::disk('public')->exists($quiz->image)) {
                Storage::disk('public')->delete($quiz->image);
            }
            $validated['image'] = $request->file('image')->store('quiz_images', 'public');
        }

        $quiz->update($validated); // Fixed: Now $quiz is defined

        return redirect()->route('quizzes.index')->with('success', 'Quiz updated successfully!');
    }

    public function destroy(Quiz $quiz)
    {
        if ($quiz->image && Storage::disk('public')->exists($quiz->image)) {
            Storage::disk('public')->delete($quiz->image);
        }

        $quiz->delete();

        return redirect()->route('quizzes.index')->with('success', 'Quiz deleted successfully!');
    }
}
