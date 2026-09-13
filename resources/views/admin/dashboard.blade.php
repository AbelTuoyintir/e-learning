@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-7xl">

    <!-- ============================================ -->
    <!-- HERO / WELCOME – Clean flat design           -->
    <!-- ============================================ -->
    <div class="relative overflow-hidden rounded-3xl bg-indigo-700 p-8 mb-10 text-white shadow-lg">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <span class="inline-flex items-center gap-2 bg-white/20 px-4 py-1.5 rounded-full text-sm font-medium text-indigo-50 border border-white/10 mb-3">
                    <i class="fas fa-shield-alt text-indigo-200"></i> 
                    Administrator
                </span>
                <h1 class="text-4xl font-extrabold tracking-tight mb-1">Welcome back, {{ Auth::user()->name ?? 'Admin' }}</h1>
                <p class="text-indigo-100 text-lg max-w-2xl">Oversee your quiz ecosystem – students, courses, quizzes &amp; questions.</p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center gap-3">
                <span class="hidden md:inline-flex items-center gap-2 bg-white/10 px-5 py-2.5 rounded-xl border border-white/20 text-sm font-medium">
                    <i class="fas fa-calendar-alt text-indigo-200"></i> 
                    <span id="currentDate" class="text-indigo-50">{{ now()->format('D, M d, Y') }}</span>
                </span>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-white text-indigo-700 px-6 py-2.5 rounded-xl font-semibold shadow-md hover:bg-indigo-50 transition-all duration-200">
                    <i class="fas fa-sync-alt"></i> Refresh
                </a>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- STATS CARDS – Solid colors, no gradients     -->
    <!-- ============================================ -->
    @php
        $stats = [
            'students' => [
                'count' => \App\Models\Student::count(),
                'trend' => '+12%',
                'trend_type' => 'up',
                'icon' => 'fa-user-graduate',
                'bg' => 'bg-blue-500',
                'color' => 'text-blue-600'
            ],
            'courses' => [
                'count' => \App\Models\Course::count(),
                'trend' => '+4',
                'trend_type' => 'up',
                'icon' => 'fa-layer-group',
                'bg' => 'bg-emerald-500',
                'color' => 'text-emerald-600'
            ],
            'quizzes' => [
                'count' => \App\Models\Quiz::count(),
                'trend' => 'stable',
                'trend_type' => 'neutral',
                'icon' => 'fa-question-circle',
                'bg' => 'bg-purple-500',
                'color' => 'text-purple-600'
            ],
            'questions' => [
                'count' => \App\Models\Question::count(),
                'trend' => '+8%',
                'trend_type' => 'up',
                'icon' => 'fa-list-ul',
                'bg' => 'bg-orange-500',
                'color' => 'text-orange-600'
            ]
        ];
        
        // Calculate additional metrics
        $totalAttempts = \App\Models\QuizAttempt::count();
        $averageScore = \App\Models\QuizAttempt::avg('score') ?? 0;
        $pendingReviews = \App\Models\QuizAttempt::where('status', 'pending')->count();
        $activeCourses = \App\Models\Course::where('is_active', true)->count();
        
        // Get recent activity
        $recentActivities = collect();
        
        // Get recent student registrations
        $recentStudents = \App\Models\Student::latest()->take(2)->get()->map(function($student) {
            return (object) [
                'type' => 'student',
                'message' => "New student registered: {$student->name}",
                'time' => $student->created_at->diffForHumans(),
                'icon' => 'fa-user-plus',
                'bg' => 'bg-blue-100',
                'text' => 'text-blue-600',
                'badge' => 'new',
                'badge_bg' => 'bg-blue-50',
                'badge_text' => 'text-blue-600'
            ];
        });
        
        // Get recent quizzes
        $recentQuizzes = \App\Models\Quiz::latest()->take(2)->get()->map(function($quiz) {
            return (object) [
                'type' => 'quiz',
                'message' => "New quiz created: {$quiz->title}",
                'time' => $quiz->created_at->diffForHumans(),
                'icon' => 'fa-plus-circle',
                'bg' => 'bg-emerald-100',
                'text' => 'text-emerald-600',
                'badge' => 'quiz',
                'badge_bg' => 'bg-emerald-50',
                'badge_text' => 'text-emerald-600'
            ];
        });
        
        // Get recent question updates
        $recentQuestions = \App\Models\Question::latest()->take(2)->get()->map(function($question) {
            return (object) [
                'type' => 'question',
                'message' => "Question updated: {$question->question_text}",
                'time' => $question->updated_at->diffForHumans(),
                'icon' => 'fa-question-circle',
                'bg' => 'bg-purple-100',
                'text' => 'text-purple-600',
                'badge' => 'update',
                'badge_bg' => 'bg-purple-50',
                'badge_text' => 'text-purple-600'
            ];
        });
        
        // Get recent attempts
        $recentAttempts = \App\Models\QuizAttempt::with(['student', 'quiz'])->latest()->take(2)->get()->map(function($attempt) {
            return (object) [
                'type' => 'attempt',
                'message' => "{$attempt->student->name} completed quiz: {$attempt->quiz->title}",
                'time' => $attempt->created_at->diffForHumans(),
                'icon' => 'fa-check-double',
                'bg' => 'bg-amber-100',
                'text' => 'text-amber-600',
                'badge' => 'graded',
                'badge_bg' => 'bg-amber-50',
                'badge_text' => 'text-amber-600'
            ];
        });
        
        // Merge and sort activities
        $recentActivities = $recentStudents->concat($recentQuizzes)->concat($recentQuestions)->concat($recentAttempts)
            ->sortByDesc(function($item) {
                return strtotime($item->time);
            })->take(5);
            
        // Get top scorer
        $topScorer = \App\Models\QuizAttempt::with('user')
            ->select('user_id', \DB::raw('AVG(score) as avg_score'))
            ->groupBy('user_id')
            ->orderBy('avg_score', 'desc')
            ->first();
            
        $topScorerName = $topScorer ? ($topScorer->user->name ?? 'N/A') : 'N/A';
        $topScorerScore = $topScorer ? round($topScorer->avg_score, 1) : 0;
        
        // Get total quiz attempts
        $totalAttemptsCount = \App\Models\QuizAttempt::count();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        @foreach($stats as $key => $stat)
        <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-200 hover-lift transition-all duration-200">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium tracking-wide uppercase">{{ ucfirst($key) }}</p>
                    <p class="text-4xl font-extrabold text-slate-800 mt-1">{{ number_format($stat['count']) }}</p>
                    <span class="inline-flex items-center text-xs font-medium 
                        @if($stat['trend_type'] == 'up') text-emerald-600 bg-emerald-50
                        @elseif($stat['trend_type'] == 'neutral') text-amber-600 bg-amber-50
                        @else text-rose-600 bg-rose-50 @endif 
                        px-2 py-0.5 rounded-full mt-2">
                        @if($stat['trend_type'] == 'up')
                            <i class="fas fa-arrow-up mr-1"></i>
                        @elseif($stat['trend_type'] == 'neutral')
                            <i class="fas fa-minus mr-1"></i>
                        @else
                            <i class="fas fa-arrow-down mr-1"></i>
                        @endif
                        {{ $stat['trend'] }}
                    </span>
                </div>
                <div class="w-14 h-14 {{ $stat['bg'] }} rounded-2xl flex items-center justify-center shadow-md">
                    <i class="fas {{ $stat['icon'] }} text-white text-2xl"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- ============================================ -->
    <!-- QUICK ACTIONS + RECENT ACTIVITY 2‑col layout -->
    <!-- ============================================ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">

        <!-- Quick Actions (span 2 on large) -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200 shadow-md">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-bolt text-indigo-500"></i> Quick Actions
                </h2>
                <span class="text-xs font-medium text-slate-400 bg-slate-100 px-3 py-1 rounded-full">shortcuts</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('quizzes.index') }}" class="quick-action-card flex flex-col items-center justify-center p-5 bg-slate-50 rounded-xl border border-slate-200 hover:border-indigo-400 transition-all duration-200 group">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-2 group-hover:bg-indigo-200 transition-colors">
                        <i class="fas fa-pen-fancy text-xl"></i>
                    </div>
                    <span class="font-semibold text-slate-700">Manage Quizzes</span>
                    <span class="text-xs text-slate-400">{{ \App\Models\Quiz::count() }} total</span>
                </a>
                <a href="{{ route('courses.index') }}" class="quick-action-card flex flex-col items-center justify-center p-5 bg-slate-50 rounded-xl border border-slate-200 hover:border-emerald-400 transition-all duration-200 group">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-2 group-hover:bg-emerald-200 transition-colors">
                        <i class="fas fa-book-open text-xl"></i>
                    </div>
                    <span class="font-semibold text-slate-700">Manage Courses</span>
                    <span class="text-xs text-slate-400">{{ $activeCourses }} active</span>
                </a>
                <a href="{{ route('students.index') }}" class="quick-action-card flex flex-col items-center justify-center p-5 bg-slate-50 rounded-xl border border-slate-200 hover:border-blue-400 transition-all duration-200 group">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-2 group-hover:bg-blue-200 transition-colors">
                        <i class="fas fa-users-cog text-xl"></i>
                    </div>
                    <span class="font-semibold text-slate-700">Manage Students</span>
                    <span class="text-xs text-slate-400">{{ \App\Models\Student::count() }} enrolled</span>
                </a>
            </div>
            <!-- secondary actions row -->
            <div class="mt-4 flex flex-wrap items-center gap-3 pt-3 border-t border-slate-200">
                <span class="text-xs text-slate-400 font-medium"><i class="far fa-clock mr-1"></i> more</span>
                @php($reportsRouteExists = \Illuminate\Support\Facades\Route::has('reports.index'))
                @php($settingsRouteExists = \Illuminate\Support\Facades\Route::has('settings.index'))
                @php($exportRouteExists = \Illuminate\Support\Facades\Route::has('export.data'))

                <a href="{{ $reportsRouteExists ? route('reports.index') : '#' }}" class="text-sm text-slate-600 hover:text-indigo-600 transition-colors flex items-center gap-1"><i class="fas fa-chart-line"></i> Reports</a>
                <a href="{{ $settingsRouteExists ? route('settings.index') : '#' }}" class="text-sm text-slate-600 hover:text-indigo-600 transition-colors flex items-center gap-1"><i class="fas fa-cog"></i> Settings</a>
                <a href="{{ $exportRouteExists ? route('export.data') : '#' }}" class="text-sm text-slate-600 hover:text-indigo-600 transition-colors flex items-center gap-1"><i class="fas fa-file-export"></i> Export</a>
            </div>
        </div>

        <!-- Recent Activity (span 1) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-md">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-history text-indigo-400"></i> Activity
                </h2>
                <span class="text-xs text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">{{ $recentActivities->count() }} latest</span>
            </div>
            <div class="space-y-5 max-h-[260px] overflow-y-auto pr-1 scroll-hint">
                @forelse($recentActivities as $activity)
                <div class="activity-item flex items-start gap-4 p-2 rounded-xl transition-colors">
                    <div class="w-9 h-9 rounded-full {{ $activity->bg }} flex items-center justify-center {{ $activity->text }} flex-shrink-0">
                        <i class="fas {{ $activity->icon }} text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800">{{ $activity->message }}</p>
                        <p class="text-xs text-slate-400 flex items-center gap-1"><i class="far fa-clock"></i> {{ $activity->time }}</p>
                    </div>
                    <span class="text-[10px] font-medium {{ $activity->badge_text }} {{ $activity->badge_bg }} px-2 py-0.5 rounded-full">{{ $activity->badge }}</span>
                </div>
                @empty
                <div class="text-center py-8 text-slate-400">
                    <i class="fas fa-inbox text-3xl mb-2"></i>
                    <p>No recent activity</p>
                </div>
                @endforelse
            </div>
            <!-- view all link -->
            @php($activityRouteExists = \Illuminate\Support\Facades\Route::has('activity.index'))
            <div class="mt-4 pt-3 border-t border-slate-200 text-center">
                <a href="{{ $activityRouteExists ? route('activity.index') : '#' }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors inline-flex items-center gap-1">
                    View all activity <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- EXTRA: quick insights / bottom row            -->
    <!-- ============================================ -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-5 shadow-md border border-slate-200 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center">
                <i class="fas fa-trophy text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Top scorer</p>
                <p class="text-lg font-bold text-slate-800">{{ $topScorerName }} <span class="text-sm font-normal text-slate-400">· {{ $topScorerScore }}%</span></p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-md border border-slate-200 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                <i class="fas fa-hourglass-half text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Pending reviews</p>
                <p class="text-lg font-bold text-slate-800">{{ $pendingReviews }} <span class="text-sm font-normal text-slate-400">quizzes</span></p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-md border border-slate-200 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center">
                <i class="fas fa-rocket text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total attempts</p>
                <p class="text-lg font-bold text-slate-800">{{ $totalAttemptsCount }} <span class="text-sm font-normal text-slate-400">submissions</span></p>
            </div>
        </div>
    </div>

    <!-- footer note -->
    <div class="mt-10 text-center text-xs text-slate-400 border-t border-slate-200 pt-6">
        <i class="fas fa-shield-alt text-indigo-300 mr-1"></i> Admin dashboard · updated {{ now()->format('M d, Y h:i A') }}
    </div>
</div>

<!-- custom styles for animations & effects -->
<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.15);
    }
    .quick-action-card {
        transition: all 0.2s ease;
    }
    .quick-action-card:hover {
        background: #f1f5f9;
        border-color: #818cf8;
    }
    .activity-item {
        transition: background 0.15s ease;
    }
    .activity-item:hover {
        background: #f8fafc;
    }
    .scroll-hint::-webkit-scrollbar {
        width: 4px;
    }
    .scroll-hint::-webkit-scrollbar-track {
        background: #e2e8f0;
        border-radius: 8px;
    }
    .scroll-hint::-webkit-scrollbar-thumb {
        background: #a5b4fc;
        border-radius: 8px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Admin dashboard loaded with dynamic data');
    });
</script>
@endsection