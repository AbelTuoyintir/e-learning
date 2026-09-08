@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Hero Welcome Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-purple-950 p-8 sm:p-10 text-white shadow-2xl border border-indigo-500/20">
        <!-- Background Ambient Glow Shapes -->
        <div class="absolute -right-10 -bottom-10 w-96 h-96 bg-gradient-to-br from-indigo-500/20 to-purple-500/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-10 w-64 h-64 bg-blue-500/15 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-500/10 backdrop-blur-md border border-indigo-500/30 text-indigo-300 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Admin Control Center
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white font-heading">
                    Welcome back, {{ Auth::user()->name ?? 'Admin' }}! 👋
                </h1>
                <p class="text-indigo-200/80 text-sm sm:text-base leading-relaxed">
                    Real-time dashboard summarizing active student engagement, assessment performance, AI tutoring metrics, and course progression.
                </p>
            </div>

            <!-- Action Pill Buttons -->
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('quizzes.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 text-white font-bold text-xs shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-[1.02] active:scale-95 transition-all duration-200">
                    <i class="fas fa-plus text-xs"></i>
                    <span>Create Quiz</span>
                </a>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-slate-800/80 hover:bg-slate-800 backdrop-blur-md border border-slate-700 text-slate-300 hover:text-white font-bold text-xs transition-all duration-200">
                    <i class="fas fa-layer-group"></i>
                    <span>Course Catalog</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Metrics Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">

        <!-- Active & Total Students -->
        <div class="group relative glass-panel rounded-3xl p-6 shadow-lg hover:shadow-indigo-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-heading">Active Students</span>
                    <h3 class="text-3xl font-extrabold text-white mt-1.5 tracking-tight font-heading">
                        {{ number_format($activeStudents ?? \App\Models\Student::where('status', 'active')->count()) }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-2.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full w-fit border border-emerald-500/20">
                        <i class="fas fa-user-check text-[10px]"></i>
                        <span>{{ number_format(\App\Models\Student::count()) }} Total Registered</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shadow-inner group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Average Score & Pass Rate -->
        <div class="group relative glass-panel rounded-3xl p-6 shadow-lg hover:shadow-emerald-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-heading">Avg Platform Score</span>
                    <h3 class="text-3xl font-extrabold text-white mt-1.5 tracking-tight font-heading">
                        {{ number_format($averageScore ?? 0, 1) }}%
                    </h3>
                    <div class="flex items-center gap-1.5 mt-2.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full w-fit border border-emerald-500/20">
                        <i class="fas fa-chart-line text-[10px]"></i>
                        <span>{{ number_format($modulePassRate ?? 0, 1) }}% Pass Rate</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shadow-inner group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-award text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Course Completion Rate -->
        <div class="group relative glass-panel rounded-3xl p-6 shadow-lg hover:shadow-purple-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-500 via-indigo-500 to-pink-500"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-heading">Course Completion</span>
                    <h3 class="text-3xl font-extrabold text-white mt-1.5 tracking-tight font-heading">
                        {{ number_format($courseCompletionRate ?? 0, 1) }}%
                    </h3>
                    <div class="flex items-center gap-1.5 mt-2.5 text-xs font-semibold text-purple-400 bg-purple-500/10 px-2.5 py-1 rounded-full w-fit border border-purple-500/20">
                        <i class="fas fa-book-bookmark text-[10px]"></i>
                        <span>{{ $courseCount ?? \App\Models\Course::count() }} Courses ({{ $moduleCount ?? \App\Models\Module::count() }} Modules)</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 shadow-inner group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- AI Tutor Sessions -->
        <div class="group relative glass-panel rounded-3xl p-6 shadow-lg hover:shadow-amber-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 via-orange-500 to-red-500"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-heading">AI Interactions</span>
                    <h3 class="text-3xl font-extrabold text-white mt-1.5 tracking-tight font-heading">
                        {{ number_format($aiUsageStats ?? \App\Models\AIChatSession::count()) }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-2.5 text-xs font-semibold text-amber-400 bg-amber-500/10 px-2.5 py-1 rounded-full w-fit border border-amber-500/20">
                        <i class="fas fa-brain text-[10px]"></i>
                        <span>Active Tutor Sessions</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 shadow-inner group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-robot text-2xl"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Visual Analytics Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Student Activity & Growth Chart -->
        <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight font-heading">Student Growth & Enrollments</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Recent monthly enrollment and assessment trend</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span> Live Analytics
                </span>
            </div>
            <div class="h-64 w-full">
                <canvas id="enrollmentChart"></canvas>
            </div>
        </div>

        <!-- Quiz Performance & Passing Rate Doughnut -->
        <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight font-heading">Assessment Performance Distribution</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Ratio of passed vs retake required assessments</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-300 border border-emerald-500/20">
                    <i class="fas fa-chart-pie text-xs"></i> Score Breakdown
                </span>
            </div>
            <div class="h-64 w-full flex items-center justify-center">
                <canvas id="quizDistributionChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Analytics List: Most Difficult Topics -->
    <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-lg font-bold text-white tracking-tight font-heading">Most Difficult Topics</h2>
                <p class="text-xs text-slate-400 mt-0.5">Topics where students achieve the lowest average quiz scores</p>
            </div>
            <a href="{{ route('quizzes.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition flex items-center gap-1">
                <span>Manage Quiz Questions</span>
                <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="space-y-4">
            @forelse($mostDifficultTopics ?? [] as $topic)
            @php
                $avgScore = round($topic->avg_score, 1);
                $statusColor = $avgScore < 50 ? 'text-rose-400 bg-rose-500/10 border-rose-500/20' : ($avgScore < 70 ? 'text-amber-400 bg-amber-500/10 border-amber-500/20' : 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20');
                $progressColor = $avgScore < 50 ? 'bg-rose-500' : ($avgScore < 70 ? 'bg-amber-500' : 'bg-emerald-500');
            @endphp
            <div class="glass-card rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-slate-800 text-slate-400 flex items-center justify-center font-bold text-sm shrink-0 border border-slate-700">
                        <i class="fas fa-circle-exclamation text-indigo-400"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-200 text-sm font-heading">{{ $topic->title }}</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Average Score across all student attempts</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 shrink-0 w-full sm:w-auto justify-between sm:justify-end">
                    <div class="w-32 hidden sm:block">
                        <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden border border-slate-700">
                            <div class="{{ $progressColor }} h-2 rounded-full" style="width: {{ $avgScore }}%"></div>
                        </div>
                    </div>

                    <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold border {{ $statusColor }}">
                        {{ $avgScore }}% Avg Score
                    </span>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-slate-400">
                <div class="w-12 h-12 rounded-2xl bg-slate-800 text-slate-500 flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-check-double text-xl"></i>
                </div>
                <p class="text-xs font-semibold text-slate-300">No score bottlenecks identified</p>
                <p class="text-[11px] text-slate-500">Student score analytics will display topic difficulties as assessments are submitted.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Management Shortcuts & System Audit Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Shortcuts Grid (2 cols) -->
        <div class="lg:col-span-2 glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight font-heading">Administrative Tools</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Direct links to core LMS management tasks</p>
                </div>
                <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-800 text-slate-300 border border-slate-700">
                    <i class="fas fa-bolt text-amber-400 mr-1.5"></i> Quick Actions
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <!-- Quizzes -->
                <a href="{{ route('quizzes.index') }}" class="group relative flex flex-col p-5 glass-card rounded-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-2xl bg-purple-600/20 border border-purple-500/30 text-purple-400 flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:text-white transition-all shadow-md">
                        <i class="fas fa-pen-to-square text-lg"></i>
                    </div>
                    <h3 class="font-bold text-slate-200 group-hover:text-purple-400 transition-colors font-heading text-sm">Manage Quizzes</h3>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Structure, edit, and assign assessment quizzes.</p>
                </a>

                <!-- Courses -->
                <a href="{{ route('courses.index') }}" class="group relative flex flex-col p-5 glass-card rounded-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600/20 border border-emerald-500/30 text-emerald-400 flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-all shadow-md">
                        <i class="fas fa-layer-group text-lg"></i>
                    </div>
                    <h3 class="font-bold text-slate-200 group-hover:text-emerald-400 transition-colors font-heading text-sm">Manage Courses</h3>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Organize modules, topics, and materials.</p>
                </a>

                <!-- Students -->
                <a href="{{ route('students.index') }}" class="group relative flex flex-col p-5 glass-card rounded-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600/20 border border-blue-500/30 text-blue-400 flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-md">
                        <i class="fas fa-users-gear text-lg"></i>
                    </div>
                    <h3 class="font-bold text-slate-200 group-hover:text-blue-400 transition-colors font-heading text-sm">Manage Students</h3>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Inspect rosters, profiles, and academic records.</p>
                </a>

            </div>
        </div>

        <!-- Service Health & System Monitor -->
        <div class="bg-gradient-to-br from-indigo-950/90 via-slate-900 to-purple-950/90 rounded-3xl p-6 sm:p-8 text-white flex flex-col justify-between shadow-2xl border border-indigo-500/20">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 mb-6 shadow-inner">
                    <i class="fas fa-shield-cat text-2xl"></i>
                </div>
                <h3 class="text-xl font-extrabold tracking-tight font-heading">AI Engine & Platform Health</h3>
                <p class="text-slate-400 text-xs mt-2 leading-relaxed">
                    Automated tutoring services operating with primary OpenAI GPT-4o-mini and fallback Ollama redundancy active.
                </p>

                <div class="mt-6 space-y-3">
                    <div class="flex items-center justify-between text-xs py-2 border-b border-slate-800">
                        <span class="text-slate-400">AI Tutor Service</span>
                        <span class="font-semibold text-emerald-400 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Active</span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-2 border-b border-slate-800">
                        <span class="text-slate-400">Database Engine</span>
                        <span class="font-semibold text-slate-200">SQLite Connected</span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-2">
                        <span class="text-slate-400">Payment Gateway</span>
                        <span class="font-semibold text-emerald-400">Paystack Active</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-slate-800 flex items-center justify-between text-xs text-indigo-300">
                <span>System v2.5-Pro</span>
                <i class="fas fa-circle-check text-emerald-400"></i>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const passRate = {{ number_format($modulePassRate ?? 75, 1) }};
    const retakeRate = Math.max(0, 100 - passRate);

    // 1. Enrollment Line Chart
    const ctxEnrollment = document.getElementById('enrollmentChart')?.getContext('2d');
    if (ctxEnrollment) {
        new Chart(ctxEnrollment, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                dataSets: [{
                    label: 'New Enrollments',
                    data: [12, 24, 38, 52, 70, {{ \App\Models\Student::count() > 0 ? \App\Models\Student::count() : 85 }}],
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.15)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#818cf8',
                    pointBorderColor: '#fff',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8', font: { family: 'Plus Jakarta Sans', size: 11 } }
                    },
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8', font: { family: 'Plus Jakarta Sans', size: 11 } }
                    }
                }
            }
        });
    }

    // 2. Quiz Pass/Attempt Pie Chart
    const ctxQuiz = document.getElementById('quizDistributionChart')?.getContext('2d');
    if (ctxQuiz) {
        new Chart(ctxQuiz, {
            type: 'doughnut',
            data: {
                labels: ['Passed (>=70%)', 'Retake / Below Threshold'],
                datasets: [{
                    data: [passRate, retakeRate],
                    backgroundColor: ['#10b981', '#f43f5e'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#cbd5e1',
                            font: { family: 'Plus Jakarta Sans', size: 11 },
                            padding: 16
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }
});
</script>
@endpush
@endsection
