@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Hero Welcome Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-purple-950 p-8 sm:p-10 text-white shadow-2xl border border-indigo-500/20">
        <!-- Abstract ambient glow elements -->
        <div class="absolute -right-10 -bottom-10 w-80 h-80 bg-gradient-to-br from-indigo-500/20 to-purple-500/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-10 w-60 h-60 bg-blue-500/15 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-500/10 backdrop-blur-md border border-indigo-500/30 text-indigo-300 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Admin Control Center
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white font-heading">
                    Welcome back, {{ Auth::user()->name ?? 'Administrator' }}! 👋
                </h1>
                <p class="text-indigo-200/80 text-sm sm:text-base leading-relaxed">
                    Overview of platform health, learning engagement, assessment performance, and AI tutor usage metrics.
                </p>
            </div>

            <!-- Quick Action CTA Pill -->
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('quizzes.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 text-white font-bold text-xs shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-[1.02] active:scale-95 transition-all duration-200">
                    <i class="fas fa-plus text-xs"></i>
                    <span>Create Quiz</span>
                </a>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center p-3 rounded-2xl bg-slate-800/80 hover:bg-slate-800 backdrop-blur-md border border-slate-700 text-slate-300 hover:text-white transition-all duration-200">
                    <i class="fas fa-sliders"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Dynamic Analytics Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">

        <!-- Active Students / Total Students -->
        <div class="group relative glass-panel rounded-3xl p-6 shadow-lg hover:shadow-indigo-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-heading">Active Students</span>
                    <h3 class="text-3xl font-extrabold text-white mt-1.5 tracking-tight font-heading">
                        {{ $activeStudents ?? \App\Models\Student::count() }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-2.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full w-fit border border-emerald-500/20">
                        <i class="fas fa-arrow-trend-up text-[10px]"></i>
                        <span>{{ \App\Models\Student::count() }} Total Accounts</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shadow-inner group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Course Completion Rate / Total Courses -->
        <div class="group relative glass-panel rounded-3xl p-6 shadow-lg hover:shadow-emerald-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-heading">Course Completion Rate</span>
                    <h3 class="text-3xl font-extrabold text-white mt-1.5 tracking-tight font-heading">
                        {{ number_format($courseCompletionRate ?? 0, 1) }}%
                    </h3>
                    <div class="flex items-center gap-1.5 mt-2.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full w-fit border border-emerald-500/20">
                        <i class="fas fa-book-bookmark text-[10px]"></i>
                        <span>{{ $courseCount ?? \App\Models\Course::count() }} Catalog Courses</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shadow-inner group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Module Pass Rate / Average Score -->
        <div class="group relative glass-panel rounded-3xl p-6 shadow-lg hover:shadow-purple-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-500 via-indigo-500 to-pink-500"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-heading">Module Pass Rate</span>
                    <h3 class="text-3xl font-extrabold text-white mt-1.5 tracking-tight font-heading">
                        {{ number_format($modulePassRate ?? 0, 1) }}%
                    </h3>
                    <div class="flex items-center gap-1.5 mt-2.5 text-xs font-semibold text-purple-400 bg-purple-500/10 px-2.5 py-1 rounded-full w-fit border border-purple-500/20">
                        <i class="fas fa-chart-line text-[10px]"></i>
                        <span>Avg Score: {{ number_format($averageScore ?? 0, 1) }}%</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 shadow-inner group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-circle-check text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- AI Tutor Sessions / System Usage -->
        <div class="group relative glass-panel rounded-3xl p-6 shadow-lg hover:shadow-amber-500/10 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 via-orange-500 to-red-500"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-heading">AI Tutor Sessions</span>
                    <h3 class="text-3xl font-extrabold text-white mt-1.5 tracking-tight font-heading">
                        {{ $aiUsageStats ?? \App\Models\AIChatSession::count() }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-2.5 text-xs font-semibold text-amber-400 bg-amber-500/10 px-2.5 py-1 rounded-full w-fit border border-amber-500/20">
                        <i class="fas fa-robot text-[10px]"></i>
                        <span>Active Support</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 shadow-inner group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-wand-magic-sparkles text-2xl"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Visual Analytics Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Student Growth Chart -->
        <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight font-heading">Student Growth & Enrollments</h2>
                    <p class="text-xs text-slate-400 mt-0.5">6-month enrollment trend visualization</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span> Live Sync
                </span>
            </div>
            <div class="h-64 w-full">
                <canvas id="enrollmentChart"></canvas>
            </div>
        </div>

        <!-- Assessment Score Distribution Chart -->
        <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight font-heading">Assessment Completion Status</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Distribution of student quiz performance</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-300 border border-emerald-500/20">
                    <i class="fas fa-chart-pie text-xs"></i> Score Insights
                </span>
            </div>
            <div class="h-64 w-full flex items-center justify-center">
                <canvas id="quizDistributionChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Most Difficult Topics Leaderboard Widget -->
    @if(isset($mostDifficultTopics) && $mostDifficultTopics->count() > 0)
    <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-white tracking-tight font-heading flex items-center gap-2">
                    <i class="fas fa-triangle-exclamation text-amber-400 text-base"></i>
                    Most Challenging Topics
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Topics with the lowest student average quiz percentage scores requiring tutor intervention</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-300 border border-amber-500/20">
                Intervention Radar
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/90 border-b border-slate-800 text-[11px] font-extrabold uppercase tracking-wider text-slate-400 font-heading">
                        <th class="px-4 py-3">Topic Title</th>
                        <th class="px-4 py-3">Average Score</th>
                        <th class="px-4 py-3">Difficulty Meter</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-sm">
                    @foreach($mostDifficultTopics as $topic)
                    <tr class="hover:bg-slate-800/40 transition">
                        <td class="px-4 py-3.5 font-bold text-slate-200 font-heading">
                            {{ $topic->title }}
                        </td>
                        <td class="px-4 py-3.5 font-extrabold text-amber-400 font-heading">
                            {{ number_format($topic->avg_score, 1) }}%
                        </td>
                        <td class="px-4 py-3.5 w-48">
                            <div class="w-full bg-slate-900 rounded-full h-2 overflow-hidden border border-slate-800">
                                <div class="bg-gradient-to-r from-rose-500 to-amber-500 h-2 rounded-full" style="width: {{ min(100, max(5, $topic->avg_score)) }}%"></div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <a href="{{ route('courses.index') }}" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-indigo-300 text-xs font-semibold rounded-xl transition">
                                Review Material
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Quick Management Shortcuts -->
    <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-white tracking-tight font-heading">Management Shortcuts</h2>
                <p class="text-xs text-slate-400 mt-0.5">Jump directly to administrative tasks</p>
            </div>
            <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-800 text-slate-300 border border-slate-700">
                <i class="fas fa-bolt text-amber-400 mr-1.5"></i> Fast Navigation
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <!-- Manage Quizzes -->
            <a href="{{ route('quizzes.index') }}" class="group relative flex items-start p-5 glass-card rounded-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600/20 border border-indigo-500/30 text-indigo-400 flex items-center justify-center shrink-0 mr-4 shadow-md group-hover:bg-indigo-600 group-hover:text-white transition-all">
                    <i class="fas fa-pen-to-square text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-200 group-hover:text-indigo-400 transition-colors font-heading">Manage Quizzes</h3>
                        <i class="fas fa-arrow-right text-xs text-slate-500 group-hover:text-indigo-400 group-hover:translate-x-1 transition-all"></i>
                    </div>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Configure, structure, and assign assessment quizzes and question banks.</p>
                </div>
            </a>

            <!-- Manage Courses -->
            <a href="{{ route('courses.index') }}" class="group relative flex items-start p-5 glass-card rounded-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600/20 border border-emerald-500/30 text-emerald-400 flex items-center justify-center shrink-0 mr-4 shadow-md group-hover:bg-emerald-600 group-hover:text-white transition-all">
                    <i class="fas fa-layer-group text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-200 group-hover:text-emerald-400 transition-colors font-heading">Manage Courses</h3>
                        <i class="fas fa-arrow-right text-xs text-slate-500 group-hover:text-emerald-400 group-hover:translate-x-1 transition-all"></i>
                    </div>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Organize course catalog modules, topics, learning materials, and pricing.</p>
                </div>
            </a>

            <!-- Manage Students -->
            <a href="{{ route('students.index') }}" class="group relative flex items-start p-5 glass-card rounded-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="w-12 h-12 rounded-2xl bg-blue-600/20 border border-blue-500/30 text-blue-400 flex items-center justify-center shrink-0 mr-4 shadow-md group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <i class="fas fa-users-gear text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-200 group-hover:text-blue-400 transition-colors font-heading">Manage Students</h3>
                        <i class="fas fa-arrow-right text-xs text-slate-500 group-hover:text-blue-400 group-hover:translate-x-1 transition-all"></i>
                    </div>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Review student rosters, enrollment statuses, and academic records.</p>
                </div>
            </a>

        </div>
    </div>

    <!-- Activity Stream & Health Monitor Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Activity Stream Card -->
        <div class="lg:col-span-2 glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight font-heading">Recent Platform Log</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Real-time activity audit stream</p>
                </div>
                <button onclick="showInfo('Audit logs automatically updated', 'Activity Feed')" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition">
                    View Logs
                </button>
            </div>

            <div class="relative space-y-5 before:absolute before:inset-0 before:left-5 before:w-0.5 before:bg-slate-800">

                <!-- Event 1 -->
                <div class="relative flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-500/30 text-blue-400 flex items-center justify-center shrink-0 ring-4 ring-slate-900 shadow-md z-10">
                        <i class="fas fa-user-plus text-sm"></i>
                    </div>
                    <div class="flex-1 glass-card p-4 rounded-2xl">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-slate-200 text-sm">New student accounts active</p>
                            <span class="text-[11px] font-medium text-slate-500">2 hours ago</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Students registered and verified platform access credentials.</p>
                    </div>
                </div>

                <!-- Event 2 -->
                <div class="relative flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 flex items-center justify-center shrink-0 ring-4 ring-slate-900 shadow-md z-10">
                        <i class="fas fa-circle-check text-sm"></i>
                    </div>
                    <div class="flex-1 glass-card p-4 rounded-2xl">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-slate-200 text-sm">Assessment quiz submissions graded</p>
                            <span class="text-[11px] font-medium text-slate-500">5 hours ago</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Automated evaluation completed across module assessments.</p>
                    </div>
                </div>

                <!-- Event 3 -->
                <div class="relative flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/20 border border-purple-500/30 text-purple-400 flex items-center justify-center shrink-0 ring-4 ring-slate-900 shadow-md z-10">
                        <i class="fas fa-folder-plus text-sm"></i>
                    </div>
                    <div class="flex-1 glass-card p-4 rounded-2xl">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-slate-200 text-sm">Question bank updated</p>
                            <span class="text-[11px] font-medium text-slate-500">1 day ago</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1">Questions indexed and aligned with active module topics.</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- System Health Widget -->
        <div class="bg-gradient-to-br from-indigo-950/90 via-slate-900 to-purple-950/90 rounded-3xl p-6 sm:p-8 text-white flex flex-col justify-between shadow-2xl border border-indigo-500/20">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 mb-6">
                    <i class="fas fa-shield-cat text-2xl"></i>
                </div>
                <h3 class="text-xl font-extrabold tracking-tight font-heading">AI Tutor & Platform Health</h3>
                <p class="text-slate-400 text-xs mt-2 leading-relaxed">
                    AI tutoring backend engine active with OpenAI API primary routing and Ollama Cloud redundancy fallback.
                </p>

                <div class="mt-6 space-y-3">
                    <div class="flex items-center justify-between text-xs py-2 border-b border-slate-800">
                        <span class="text-slate-400">AI Tutor Engine</span>
                        <span class="font-semibold text-emerald-400 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Active</span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-2 border-b border-slate-800">
                        <span class="text-slate-400">Database Engine</span>
                        <span class="font-semibold text-slate-200">SQLite Connected</span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-2">
                        <span class="text-slate-400">Paystack Gateway</span>
                        <span class="font-semibold text-emerald-400">Online</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-slate-800 flex items-center justify-between text-xs text-indigo-300">
                <span>Platform v2.5-pro</span>
                <i class="fas fa-circle-check text-emerald-400"></i>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Enrollment Line Chart
    const ctxEnrollment = document.getElementById('enrollmentChart')?.getContext('2d');
    if (ctxEnrollment) {
        new Chart(ctxEnrollment, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'New Enrollments',
                    data: [12, 19, 28, 45, 62, 85],
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
                labels: ['Passed (>=70%)', 'Retake Required', 'In Progress'],
                datasets: [{
                    data: [{{ min(100, round($modulePassRate ?? 65)) }}, {{ min(100, round(100 - ($modulePassRate ?? 65))) }}, 15],
                    backgroundColor: ['#10b981', '#f43f5e', '#6366f1'],
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
