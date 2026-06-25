<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Result;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutorDashboardController extends Controller
{
    public function index()
    {
        $tutor = Auth::user();
        $courses = Course::where('user_id', $tutor->id)->withCount('enrollments')->get();
        $courseIds = $courses->pluck('id');

        $totalStudents = Enrollment::whereIn('course_id', $courseIds)->distinct('student_id')->count();
        $totalEnrollments = Enrollment::whereIn('course_id', $courseIds)->count();
        $totalCommissions = Enrollment::whereIn('course_id', $courseIds)
            ->where('payment_status', 'paid')
            ->sum('price_paid') * 0.7; // 70% commission

        $recentEnrollments = Enrollment::whereIn('course_id', $courseIds)
            ->with(['student', 'course'])
            ->latest()
            ->take(5)
            ->get();

        return view('tutor.dashboard', compact(
            'courses',
            'totalStudents',
            'totalEnrollments',
            'totalCommissions',
            'recentEnrollments'
        ));
    }

    public function students(Course $course)
    {
        $this->authorize('view', $course);

        $enrollments = Enrollment::where('course_id', $course->id)
            ->with('student')
            ->paginate(20);

        return view('tutor.students', compact('course', 'enrollments'));
    }

    public function studentPerformance(Course $course, Student $student)
    {
        $this->authorize('view', $course);

        $results = Result::where('student_id', $student->id)
            ->whereHas('quiz', function($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->with('quiz')
            ->latest()
            ->get();

        return view('tutor.student-performance', compact('course', 'student', 'results'));
    }
}
