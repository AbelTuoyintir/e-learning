<?php

namespace App\Http\Controllers;   // ✅ This must be the very first line (after <?php)

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class CourseController extends Controller
{
    //
    public function index(){
        $courses= \App\Models\Course::where('status','active')->paginate(5);
        $totalCourses = \App\Models\Course::count();
        $totalPublishedCourses = \App\Models\Course::where('status', 'active')->count();
        return view('course.courses',[
            'courses'=>$courses,
            'totalCourses'=>$totalCourses,
            'totalPublishedCourses'=>$totalPublishedCourses
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
            'duration' => 'nullable|integer',
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
;
    return view('students.studcourses', [
        'courses'=> $courses,
    ]);
}


public function enroll(Request $request)
{
    Log::info('Enrollment process started.', [
        'user_id' => auth()->guard('student')->id(),
        'input' => $request->all(),
    ]);

    try {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $studentId = auth()->guard('student')->id();
        $courseId = $validated['course_id'];

        // Check if student is already enrolled
        $existingEnrollment = \App\Models\StudentCourses::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->first();

        if ($existingEnrollment) {
            $message = 'You are already enrolled in this course.';
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 409);
            }
            return redirect()->back()->with('error', $message);
        }

        // Create enrollment
        $enrollment = \App\Models\StudentCourses::create([
            'student_id' => $studentId,
            'course_id' => $courseId,
        ]);

        Log::info('Enrollment successful.', [
            'student_id' => $studentId,
            'course_id' => $courseId,
            'enrollment_id' => $enrollment->id,
        ]);

        $successMessage = 'Course enrollment successful!';
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'enrollment_id' => $enrollment->id
            ]);
        }
        
        return redirect()->back()->with('success', $successMessage);

    } catch (\Exception $e) {
        Log::error('Error during course enrollment.', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        $errorMessage = 'An unexpected error occurred during enrollment.';
        
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => $errorMessage], 500);
        }
        
        return redirect()->back()->with('error', $errorMessage);
    }
}

public function enrolledCourses(){
    $studentId = auth()->guard('student')->id();
    $enrolledCourses = \App\Models\StudentCourses::where('student_id', $studentId)
        ->with('course') // Eager load the related course
        ->get();

    return view('students.enrolledcourse', [
        'enrolledCourses' => $enrolledCourses,
    ]);
}
}


