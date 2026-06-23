<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    //
    public function index(){
        $activeStudents = \App\Models\Student::where('status', 'active')->count();
        $totalResultsCount = \App\Models\Result::count();
        $modulePassRate = $totalResultsCount > 0 ? (\App\Models\Result::where('passed', 1)->count() / $totalResultsCount * 100) : 0;
        $averageScore = \App\Models\Result::avg('percentage') ?? 0;
        $aiUsageStats = \App\Models\AIChatSession::count();
        $courseCount = \App\Models\Course::count();
        $moduleCount = \App\Models\Module::count();

        // Optimized Course Completion Rate calculation
        $courseCompletionRate = 0;
        $totalEnrollmentsCount = \App\Models\Enrollment::count();
        if ($totalEnrollmentsCount > 0) {
            $completedEnrollmentsCount = \App\Models\Enrollment::whereHas('course', function($q) {
                $q->whereHas('quizzes', function($sq) { $sq->where('quiz_type', 'module_assessment'); });
            })->whereDoesntHave('course.quizzes', function($q) {
                $q->where('quiz_type', 'module_assessment')
                  ->whereDoesntHave('results', function($sq) {
                      $sq->where('passed', 1)->whereColumn('results.student_id', 'enrollments.student_id');
                  });
            })->count();

            $courseCompletionRate = ($completedEnrollmentsCount / $totalEnrollmentsCount) * 100;
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
