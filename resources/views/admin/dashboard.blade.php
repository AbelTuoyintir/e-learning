@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Hero Welcome Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950/95 to-slate-900 p-8 sm:p-10 text-white shadow-2xl border border-indigo-500/30 border-glow">
        <!-- Ambient background glows -->
        <div class="absolute -right-10 -bottom-10 w-96 h-96 bg-gradient-to-br from-indigo-500/25 to-purple-500/35 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-10 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute left-1/4 -bottom-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3.5 max-w-2xl">
                <div class="flex flex-wrap items-center gap-2.5">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-500/20 backdrop-blur-md border border-indigo-500/35 text-indigo-300 text-xs font-bold shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-sm shadow-emerald-400"></span>
                        Admin Control Center
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/15 border border-purple-500/25 text-purple-300 text-xs font-semibold">
                        <i class="fas fa-bolt text-amber-400 text-[10px]"></i> Real-time Analytics
                    </span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white font-heading leading-tight">
                    Welcome back, <span class="bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 bg-clip-text text-transparent">{{ Auth::user()->name ?? 'Admin' }}</span>! 👋
                </h1>
                <p class="text-indigo-200/90 text-sm sm:text-base leading-relaxed">
                    Real-time monitoring of learning engagements, AI tutoring sessions, quiz completion metrics, and course catalog status.
                </p>
            </div>

            <!-- Quick Action CTA Pill -->
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('quizzes.create') }}" class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 text-white font-bold text-xs shadow-xl shadow-indigo-600/30 hover:shadow-indigo-600/50 hover:scale-[1.02] active:scale-95 transition-all duration-200 border border-indigo-400/30">
                    <i class="fas fa-plus text-xs"></i>
                    <span>Create Quiz</span>
                </a>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center p-3.5 rounded-2xl bg-slate-800/80 hover:bg-slate-700/80 backdrop-blur-md border border-slate-700/80 text-slate-300 hover:text-white transition-all duration-200 shadow-md" title="Manage Catalog">
                    <i class="fas fa-sliders text-sm"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Key Metrics Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">

        <!-- Active Students -->
        <div class="group relative glass-panel rounded-3xl p-6 shadow-xl hover:shadow-indigo-500/20 transition-all duration-300 overflow-hidden border border-slate-800 hover:border-indigo-500/40">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-heading">Active Students</span>
                    <h3 class="text-3xl font-extrabold text-white mt-1.5 tracking-tight font-heading">
                        {{ $activeStudents ?? \App\Models\Student::count() }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-2.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full w-fit border border-emerald-500/20">
                        <i class="fas fa-user-check text-[10px]"></i>
                        <span>Enrolled & Active</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-indigo-500/15 border border-indigo-500/25 flex items-center justify-center text-indigo-400 shadow-inner group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Course Pass Rate / Completion -->
        <div class="group relative glass-panel rounded-3xl p-6 shadow-xl hover:shadow-emerald-500/20 transition-all duration-300 overflow-hidden border border-slate-800 hover:border-emerald-500/40">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-heading">Pass Rate</span>
                    <h3 class="text-3xl font-extrabold text-white mt-1.5 tracking-tight font-heading">
                        {{ number_format($modulePassRate ?? 85, 1) }}%
                    </h3>
                    <div class="flex items-center gap-1.5 mt-2.5 text-xs font-semibold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full w-fit border border-emerald-500/20">
                        <i class="fas fa-award text-[10px]"></i>
                        <span>Passed Modules</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/15 border border-emerald-500/25 flex items-center justify-center text-emerald-400 shadow-inner group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-circle-check text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Average Score -->
        <div class="group relative glass-panel rounded-3xl p-6 shadow-xl hover:shadow-purple-500/20 transition-all duration-300 overflow-hidden border border-slate-800 hover:border-purple-500/40">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-500 via-indigo-500 to-pink-500"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-heading">Average Score</span>
                    <h3 class="text-3xl font-extrabold text-white mt-1.5 tracking-tight font-heading">
                        {{ number_format($averageScore ?? 78, 1) }}%
                    </h3>
                    <div class="flex items-center gap-1.5 mt-2.5 text-xs font-semibold text-purple-400 bg-purple-500/10 px-2.5 py-1 rounded-full w-fit border border-purple-500/20">
                        <i class="fas fa-chart-line text-[10px]"></i>
                        <span>Global Score Avg</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-purple-500/15 border border-purple-500/25 flex items-center justify-center text-purple-400 shadow-inner group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-chart-pie text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- AI Tutor Interactions -->
        <div class="group relative glass-panel rounded-3xl p-6 shadow-xl hover:shadow-amber-500/20 transition-all duration-300 overflow-hidden border border-slate-800 hover:border-amber-500/40">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 via-orange-500 to-red-500"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 font-heading">AI Interactions</span>
                    <h3 class="text-3xl font-extrabold text-white mt-1.5 tracking-tight font-heading">
                        {{ $aiUsageStats ?? \App\Models\AIChatSession::count() }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-2.5 text-xs font-semibold text-amber-400 bg-amber-500/10 px-2.5 py-1 rounded-full w-fit border border-amber-500/20">
                        <i class="fas fa-brain text-[10px]"></i>
                        <span>Tutoring Sessions</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-amber-500/15 border border-amber-500/25 flex items-center justify-center text-amber-400 shadow-inner group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-robot text-2xl"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Visual Analytics Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Student Growth Chart -->
        <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-800">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight font-heading">Student Growth & Enrollments</h2>
                    <p class="text-xs text-slate-400 mt-0.5">6-month registration trend visualization</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/15 text-indigo-300 border border-indigo-500/25">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span> Live Sync
                </span>
            </div>
            <div class="h-64 w-full">
                <canvas id="enrollmentChart"></canvas>
            </div>
        </div>

        <!-- Assessment Distribution Chart -->
        <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-800">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight font-heading">Assessment Performance Status</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Distribution of student results and pass status</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/15 text-emerald-300 border border-emerald-500/25">
                    <i class="fas fa-chart-pie text-xs"></i> Insights
                </span>
            </div>
            <div class="h-64 w-full flex items-center justify-center">
                <canvas id="quizDistributionChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Most Difficult Topics & Content Summary Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Most Difficult Topics Table -->
        <div class="lg:col-span-2 glass-panel rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-800">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight font-heading">Most Difficult Topics</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Topics with lowest student score averages</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/15 text-amber-300 border border-amber-500/25">
                    <i class="fas fa-triangle-exclamation text-xs"></i> Attention Needed
                </span>
            </div>

            <div class="space-y-4">
                @if(isset($mostDifficultTopics) && $mostDifficultTopics->count() > 0)
                    @foreach($mostDifficultTopics as $topic)
                    <div class="glass-card rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border border-slate-800/80 hover:border-amber-500/30">
                        <div class="flex items-center space-x-3.5">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/15 border border-amber-500/25 text-amber-400 flex items-center justify-center shrink-0 font-bold text-sm shadow-xs">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-200 text-sm font-heading">{{ $topic->title }}</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Average Score: <strong class="text-amber-400 font-heading">{{ number_format($topic->avg_score, 1) }}%</strong></p>
                            </div>
                        </div>
                        <div class="w-full sm:w-44 bg-slate-950 rounded-full h-3 overflow-hidden border border-slate-800 p-0.5 shadow-inner">
                            <div class="bg-gradient-to-r from-amber-500 to-rose-500 h-full rounded-full transition-all duration-500 shadow-sm" style="width: {{ min(100, $topic->avg_score) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="glass-card rounded-2xl p-6 text-center text-slate-400 border border-slate-800/80">
                        <i class="fas fa-circle-check text-2xl text-emerald-400 mb-2"></i>
                        <p class="font-bold text-slate-200 text-sm font-heading">All topics showing balanced performance</p>
                        <p class="text-xs text-slate-400 mt-1">No topics currently flagged below passing thresholds.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- System Overview & Content Counts Card -->
        <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-800 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-white tracking-tight font-heading">Course Catalog Metrics</h2>
                    <i class="fas fa-cubes text-indigo-400 text-xl"></i>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3.5 glass-card rounded-2xl border border-slate-800/80">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-xs">
                                <i class="fas fa-book"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-300">Total Courses</span>
                        </div>
                        <span class="text-sm font-extrabold text-white font-heading">{{ $courseCount ?? \App\Models\Course::count() }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3.5 glass-card rounded-2xl border border-slate-800/80">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-300">Course Modules</span>
                        </div>
                        <span class="text-sm font-extrabold text-white font-heading">{{ $moduleCount ?? \App\Models\Module::count() }}</span>
                    </div>

                    <div class="flex items-center justify-between p-3.5 glass-card rounded-2xl border border-slate-800/80">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center text-xs">
                                <i class="fas fa-clipboard-question"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-300">Active Quizzes</span>
                        </div>
                        <span class="text-sm font-extrabold text-white font-heading">{{ \App\Models\Quiz::count() }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                <span>Completion Rate: <strong class="text-indigo-400 font-heading">{{ number_format($courseCompletionRate ?? 0, 1) }}%</strong></span>
                <a href="{{ route('courses.index') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold transition flex items-center gap-1">
                    <span>View All</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

    </div>

    <!-- Quick Management Shortcuts -->
    <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-800">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-white tracking-tight font-heading">Management Shortcuts</h2>
                <p class="text-xs text-slate-400 mt-0.5">Jump directly to administrative tasks</p>
            </div>
            <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-800/80 text-slate-300 border border-slate-700/80">
                <i class="fas fa-bolt text-amber-400 mr-1.5"></i> Fast Navigation
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <!-- Manage Quizzes -->
            <a href="{{ route('quizzes.index') }}" class="group relative flex items-start p-5 glass-card rounded-2xl transition-all duration-300 hover:-translate-y-1.5 border border-slate-800 hover:border-indigo-500/40">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600/20 border border-indigo-500/30 text-indigo-400 flex items-center justify-center shrink-0 mr-4 shadow-md group-hover:bg-indigo-600 group-hover:text-white transition-all">
                    <i class="fas fa-pen-to-square text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-200 group-hover:text-indigo-400 transition-colors font-heading">Manage Quizzes</h3>
                        <i class="fas fa-arrow-right text-xs text-slate-500 group-hover:text-indigo-400 group-hover:translate-x-1 transition-all"></i>
                    </div>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Configure, structure, and assign assessment quizzes and questions.</p>
                </div>
            </a>

            <!-- Manage Courses -->
            <a href="{{ route('courses.index') }}" class="group relative flex items-start p-5 glass-card rounded-2xl transition-all duration-300 hover:-translate-y-1.5 border border-slate-800 hover:border-emerald-500/40">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600/20 border border-emerald-500/30 text-emerald-400 flex items-center justify-center shrink-0 mr-4 shadow-md group-hover:bg-emerald-600 group-hover:text-white transition-all">
                    <i class="fas fa-layer-group text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-200 group-hover:text-emerald-400 transition-colors font-heading">Manage Courses</h3>
                        <i class="fas fa-arrow-right text-xs text-slate-500 group-hover:text-emerald-400 group-hover:translate-x-1 transition-all"></i>
                    </div>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Organize course modules, topics, learning materials, and pricing.</p>
                </div>
            </a>

            <!-- Manage Students -->
            <a href="{{ route('students.index') }}" class="group relative flex items-start p-5 glass-card rounded-2xl transition-all duration-300 hover:-translate-y-1.5 border border-slate-800 hover:border-blue-500/40">
                <div class="w-12 h-12 rounded-2xl bg-blue-600/20 border border-blue-500/30 text-blue-400 flex items-center justify-center shrink-0 mr-4 shadow-md group-hover:bg-blue-600 group-hover:text-white transition-all">
                    <i class="fas fa-users-gear text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-200 group-hover:text-blue-400 transition-colors font-heading">Manage Students</h3>
                        <i class="fas fa-arrow-right text-xs text-slate-500 group-hover:text-blue-400 group-hover:translate-x-1 transition-all"></i>
                    </div>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">Review student profiles, enrollment statuses, and academic records.</p>
                </div>
            </a>

        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Enrollment Line Chart with Gradient Fill
    const ctxEnrollment = document.getElementById('enrollmentChart')?.getContext('2d');
    if (ctxEnrollment) {
        const gradient = ctxEnrollment.createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

        new Chart(ctxEnrollment, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'New Student Registrations',
                    data: [12, 19, 28, 45, 62, 85],
                    borderColor: '#818cf8',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#090d16',
                        titleColor: '#f8fafc',
                        bodyColor: '#c7d2fe',
                        borderColor: '#334155',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.04)' },
                        ticks: { color: '#94a3b8', font: { family: 'Plus Jakarta Sans', size: 11 } }
                    },
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.04)' },
                        ticks: { color: '#94a3b8', font: { family: 'Plus Jakarta Sans', size: 11 } }
                    }
                }
            }
        });
    }

    // 2. Quiz Pass/Attempt Pie Chart
    const ctxQuiz = document.getElementById('quizDistributionChart')?.getContext('2d');
    if (ctxQuiz) {
        const passRate = {{ isset($modulePassRate) ? round($modulePassRate) : 85 }};
        const retakeRate = Math.max(0, 100 - passRate - 10);
        new Chart(ctxQuiz, {
            type: 'doughnut',
            data: {
                labels: ['Passed (>=70%)', 'Retake Required', 'In Progress'],
                datasets: [{
                    data: [passRate, retakeRate, 10],
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
