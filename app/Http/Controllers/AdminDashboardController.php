<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    //
    public function index(){
        $activeStudents = \App\Models\Student::where('status', 'active')->count();
        $totalResults = \App\Models\Result::count();
        $modulePassRate = $totalResults > 0 ? (\App\Models\Result::where('passed', 1)->count() / $totalResults * 100) : 0;
        $averageScore = \App\Models\Result::avg('percentage') ?? 0;
        $aiUsageStats = \App\Models\AIChatSession::count();
        $courseCount = \App\Models\Course::count();
        $moduleCount = \App\Models\Module::count();

        // Calculate course completion rate (students who passed all assessments in a course)
        $courseCompletionRate = 0;
        $enrolledStudents = \App\Models\Enrollment::with('course')->get();
        if ($enrolledStudents->count() > 0) {
            $completedCount = 0;
            foreach ($enrolledStudents as $enrollment) {
                $totalModuleQuizzes = \App\Models\Quiz::where('course_id', $enrollment->course_id)
                    ->where('quiz_type', 'module_assessment')
                    ->count();
                $passedQuizzes = \App\Models\Result::where('student_id', $enrollment->student_id)
                    ->whereHas('quiz', function($q) use ($enrollment) {
                        $q->where('course_id', $enrollment->course_id)->where('quiz_type', 'module_assessment');
                    })
                    ->where('passed', 1)
                    ->distinct('quiz_id')
                    ->count();

                if ($totalModuleQuizzes > 0 && $passedQuizzes === $totalModuleQuizzes) {
                    $completedCount++;
                }
            }
            $courseCompletionRate = ($completedCount / $enrolledStudents->count()) * 100;
        }

        return view('admin.dashboard', compact(
            'activeStudents',
            'courseCompletionRate',
            'modulePassRate',
            'averageScore',
            'aiUsageStats',
            'courseCount',
            'moduleCount'
        ));
    }
}
