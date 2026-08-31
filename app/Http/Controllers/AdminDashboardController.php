<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    //
    public function index(){
        $totalStudents = \App\Models\Student::count();
        $activeStudents = \App\Models\Student::where('status', 'active')->count();
        $quizCount = \App\Models\Quiz::count();
        $questionCount = \App\Models\Question::count();
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

        // Most Difficult Topics
        $mostDifficultTopics = \App\Models\Topic::select('topics.title', \Illuminate\Support\Facades\DB::raw('AVG(results.percentage) as avg_score'))
            ->join('quizzes', 'topics.id', '=', 'quizzes.topic_id')
            ->join('results', 'quizzes.id', '=', 'results.quiz_id')
            ->groupBy('topics.id', 'topics.title')
            ->orderBy('avg_score', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'activeStudents',
            'courseCompletionRate',
            'modulePassRate',
            'averageScore',
            'aiUsageStats',
            'courseCount',
            'moduleCount',
            'quizCount',
            'questionCount',
            'mostDifficultTopics'
        ));
    }
}
