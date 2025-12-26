<?php

namespace App\Http\Controllers;   // ✅ This must be the very first line (after <?php)

use App\Models\Course;
use App\Models\StudentCourses;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class CourseController extends Controller
{
    //
    public function index(){
        $courses= \App\Models\Course::where('status','active')->paginate(5);
        $totalCourses = \App\Models\Course::count();
        $totalPublishedCourses = \App\Models\Course::where('status', 'active')->count();
        $modules = \App\Models\Module::with('course')->latest()->get();
        $cour = Course::all();
        return view('course.courses',[
            'courses'=>$courses,
            'totalCourses'=>$totalCourses,
            'totalPublishedCourses'=>$totalPublishedCourses,
            'modules'=>$modules,
            'cour'=>$cour,
        ]);
    }

   public function store(Request $request)
{
    try {
        // Validate and store course data
        $validated = $request->validate([
            'title' => 'required|string|max:255', // Fixed: changed from 'title' to 'name'
            'description' => 'nullable|string',
            'instructor' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload if image is provided
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('course_images', 'public');
            $validated['image'] = $path;
        }

        // Create the course
        $course = \App\Models\Course::create($validated);

        return redirect()->back()->with('success', 'Course created successfully!');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to create course: ' . $e->getMessage())->withInput();
    }
}

public function filterAndSearch(Request $request)
{
    $query = \App\Models\Course::query();

    // Search by title/description
    if ($request->search) {
        $query->where(function($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
        });
    }

    // Filter by category
    if ($request->category && $request->category !== 'All Categories') {
        $query->where('category', $request->category);
    }

    // Filter by status
    if ($request->status && $request->status !== 'All Status') {
        $query->where('status', $request->status);
    }

    $courses = $query->latest()->get();

    return response()->json($courses);
}

public function courseReg(){
    $courses = \App\Models\Course::where('status','active')->paginate(10);
    return view('students.studcourses', [
        'courses'=> $courses,
    ]);
}


public function enroll(Request $request)
{
    try {
        \Log::info('Enrollment process started.', ['user_id' => auth()->id(), 'input' => $request->all()]);

        // Get the authenticated user
        $user = auth()->user();

        // Check if user is authenticated
        if (!$user) {
            // Use a direct URL instead of named route to avoid issues
            return redirect('/login')->with('error', 'Please log in to enroll in courses.');
        }

        $courseId = $request->input('course_id');

        // Validate course_id
        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        // Find the course
        $course = Course::find($courseId);

        if (!$course) {
            return back()->with('error', 'Course not found.');
        }

        // Check if course is paid and payment is required
        if ($course->price > 0) {
            return back()->with('error', 'This is a paid course. Payment integration is not yet implemented.');
        }

        // Check if already enrolled
        if ($user->enrollments()->where('course_id', $courseId)->exists()) {
            return back()->with('info', 'You are already enrolled in this course.');
        }

        // Create enrollment
        $enrollment = $user->enrollments()->create([
            'course_id' => $courseId,
            'enrolled_at' => now(),
        ]);

        \Log::info('Enrollment successful.', [
            'user_id' => $user->id,
            'course_id' => $courseId,
            'enrollment_id' => $enrollment->id
        ]);

        return back()->with('success', 'Successfully enrolled in the course!');

    } catch (\Exception $e) {
        \Log::error('Error during course enrollment.', [
            'error' => $e->getMessage(),
            'user_id' => auth()->id(),
            'course_id' => $request->input('course_id')
        ]);

        return back()->with('error', 'An error occurred during enrollment. Please try again.');
    }
}

    // Show edit form
    public function edit(Course $course)
    {
        $course =Course::find($course->id);
        return view('course.edit', compact('course'));
    }

    // Update course
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // Add other fields as needed
        ]);

        $course->update($validated);

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully!');
    }

    // Show modules for a course
    public function modules(Course $course)
    {
        $modules = $course->modules()->paginate(10); // paginate 10 per page
        // dd($modules);
        return view('course.module', compact('course', 'modules'));
    }

    public function show(Course $course){
        $course->load('modules.topics');
        return view('course.courseDetails', compact('course'));
    }

    // Delete course
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully!');
    }

    // Get enrolled courses for the authenticated student
   public function enrolledCourses()
    {
        $user = Auth::user();

        // Debug step 1: Check user
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Debug step 2: Check relationship
        $enrolledCourses = $user->enrollments()
            ->with('course')
            ->get();

        return view('students.enrolledcourse', compact('enrolledCourses'));
    }
    // Get materials for a specific course
    public function getMaterials(Course $course)
    {
        // Check if the user is enrolled in the course
        $user = auth()->user();
        if (!$user->enrollments()->where('course_id', $course->id)->exists()) {
            return redirect()->route('students.enrolledcourses')->with('error', 'You are not enrolled in this course.');
        }

        return view('students.materials', compact('course'));
    }

    // Get quizzes for a specific course
   public function getQuizzes(Course $course)
    {
        $user = auth()->user();
        if (!$user->enrollments()->where('course_id', $course->id)->exists()) {
            return redirect()->route('students.enrolledcourses')
                            ->with('error', 'You are not enrolled in this course.');
        }

        $quizzes = $course->quizzes()          // eager-load anything you need
                        ->withCount(['questions', 'attempts' => fn($q) => $q->where('user_id', $user->id)])
                        ->orderBy('due_at')
                        ->get();

        return view('students.quizzes.index', compact('course', 'quizzes'));
    }
}
