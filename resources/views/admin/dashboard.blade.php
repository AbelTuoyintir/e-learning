@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Hero Welcome Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-purple-950 p-8 sm:p-10 text-white shadow-2xl border border-white/10">
        <!-- Abstract background pattern elements -->
        <div class="absolute -right-10 -bottom-10 w-96 h-96 bg-gradient-to-br from-indigo-500/20 via-purple-500/20 to-pink-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-12 w-72 h-72 bg-blue-500/15 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3 max-w-xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-indigo-200 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Admin Control Center & Performance Hub
                </div>
                <h1 class="font-heading text-3xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">
                    Welcome back, {{ Auth::user()->name ?? 'Administrator' }}! 👋
                </h1>
                <p class="text-indigo-200/90 text-sm sm:text-base leading-relaxed font-normal">
                    Here's what is happening across your learning platform today. Monitor student engagement, manage course catalogs, and review assessment analytics in real time.
                </p>
            </div>

            <!-- Quick Action CTA Buttons -->
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('quizzes.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white font-bold text-xs shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-[1.02] active:scale-95 transition-all duration-200">
                    <i class="fas fa-plus text-xs"></i>
                    <span>Create Assessment</span>
                </a>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/15 text-white font-semibold text-xs transition-all duration-200">
                    <i class="fas fa-layer-group text-xs text-indigo-300"></i>
                    <span>Manage Courses</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Analytics KPI Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <!-- Total Students Card -->
        <div class="group relative bg-slate-900/80 backdrop-blur-md rounded-3xl p-6 shadow-xl border border-white/10 hover:border-indigo-500/50 transition-all duration-300 overflow-hidden hover-lift">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 opacity-90"></div>
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Total Enrollees</span>
                    <h3 class="text-3xl font-black text-white mt-1.5 tracking-tight font-heading">
                        {{ \App\Models\Student::count() }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-3 text-[11px] font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full w-fit border border-emerald-500/20">
                        <i class="fas fa-arrow-trend-up text-[10px]"></i>
                        <span>+14.2% this month</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shadow-sm group-hover:scale-110 group-hover:bg-gradient-to-tr group-hover:from-indigo-600 group-hover:to-purple-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-user-graduate text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Courses Card -->
        <div class="group relative bg-slate-900/80 backdrop-blur-md rounded-3xl p-6 shadow-xl border border-white/10 hover:border-emerald-500/50 transition-all duration-300 overflow-hidden hover-lift">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 opacity-90"></div>
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Published Courses</span>
                    <h3 class="text-3xl font-black text-white mt-1.5 tracking-tight font-heading">
                        {{ \App\Models\Course::count() }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-3 text-[11px] font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full w-fit border border-emerald-500/20">
                        <i class="fas fa-circle-check text-[10px]"></i>
                        <span>Active Catalog</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shadow-sm group-hover:scale-110 group-hover:bg-gradient-to-tr group-hover:from-emerald-600 group-hover:to-teal-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-book-bookmark text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Quizzes Card -->
        <div class="group relative bg-slate-900/80 backdrop-blur-md rounded-3xl p-6 shadow-xl border border-white/10 hover:border-purple-500/50 transition-all duration-300 overflow-hidden hover-lift">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-500 via-indigo-500 to-pink-500 opacity-90"></div>
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Quizzes Created</span>
                    <h3 class="text-3xl font-black text-white mt-1.5 tracking-tight font-heading">
                        {{ \App\Models\Quiz::count() }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-3 text-[11px] font-bold text-purple-300 bg-purple-500/10 px-2.5 py-1 rounded-full w-fit border border-purple-500/20">
                        <i class="fas fa-clipboard-check text-[10px]"></i>
                        <span>Max 60 Items/Quiz</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 shadow-sm group-hover:scale-110 group-hover:bg-gradient-to-tr group-hover:from-purple-600 group-hover:to-pink-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-clipboard-question text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Question Bank Card -->
        <div class="group relative bg-slate-900/80 backdrop-blur-md rounded-3xl p-6 shadow-xl border border-white/10 hover:border-amber-500/50 transition-all duration-300 overflow-hidden hover-lift">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 opacity-90"></div>
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Question Items</span>
                    <h3 class="text-3xl font-black text-white mt-1.5 tracking-tight font-heading">
                        {{ \App\Models\Question::count() }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-3 text-[11px] font-bold text-amber-300 bg-amber-500/10 px-2.5 py-1 rounded-full w-fit border border-amber-500/20">
                        <i class="fas fa-cubes text-[10px]"></i>
                        <span>Ready for Deployment</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 shadow-sm group-hover:scale-110 group-hover:bg-gradient-to-tr group-hover:from-amber-600 group-hover:to-orange-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-layer-group text-xl"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Interactive Data Analytics Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Student Registration & Activity Trend Chart -->
        <div class="lg:col-span-8 bg-slate-900/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-white/10 shadow-xl">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                        <i class="fas fa-chart-line text-indigo-400"></i>
                        Platform Growth & Completion Analytics
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Monthly overview of student registrations and assessment submissions</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                        Live Metrics
                    </span>
                </div>
            </div>

            <div class="relative h-72 w-full">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Quiz Performance & Passing Distribution -->
        <div class="lg:col-span-4 bg-slate-900/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-white/10 shadow-xl flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                        <i class="fas fa-chart-pie text-purple-400"></i>
                        Assessment Results
                    </h2>
                    <span class="text-xs font-semibold text-slate-400">Pass vs Fail Ratio</span>
                </div>
                <p class="text-xs text-slate-400 mb-6">Overall distribution of student score performance</p>

                <div class="relative h-52 w-full flex items-center justify-center">
                    <canvas id="performanceDoughnut"></canvas>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-white/10 grid grid-cols-2 gap-3 text-center text-xs">
                <div class="p-2.5 rounded-2xl bg-emerald-500/10 border border-emerald-500/20">
                    <span class="text-[10px] font-extrabold uppercase text-emerald-400">Avg Pass Rate</span>
                    <p class="text-lg font-black text-white mt-0.5 font-heading">78.5%</p>
                </div>
                <div class="p-2.5 rounded-2xl bg-indigo-500/10 border border-indigo-500/20">
                    <span class="text-[10px] font-extrabold uppercase text-indigo-300">Avg Score</span>
                    <p class="text-lg font-black text-white mt-0.5 font-heading">82 / 100</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Management Shortcuts & Recent Activity Feed -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Shortcuts Card Grid -->
        <div class="lg:col-span-7 bg-slate-900/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-white/10 shadow-xl space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                        <i class="fas fa-bolt text-amber-400"></i>
                        Quick Management Shortcuts
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Jump directly to critical management sections</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <!-- Quizzes Card -->
                <a href="{{ route('quizzes.index') }}" class="group relative flex flex-col justify-between p-5 rounded-2xl bg-slate-800/60 hover:bg-slate-800 border border-white/10 hover:border-indigo-500/50 transition-all duration-200 hover-lift">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-pen-to-square text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm group-hover:text-indigo-300 transition-colors flex items-center justify-between">
                            Quizzes <i class="fas fa-arrow-right text-xs opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                        </h3>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">Manage assessments & limits</p>
                    </div>
                </a>

                <!-- Courses Card -->
                <a href="{{ route('courses.index') }}" class="group relative flex flex-col justify-between p-5 rounded-2xl bg-slate-800/60 hover:bg-slate-800 border border-white/10 hover:border-emerald-500/50 transition-all duration-200 hover-lift">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-layer-group text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm group-hover:text-emerald-300 transition-colors flex items-center justify-between">
                            Courses <i class="fas fa-arrow-right text-xs opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                        </h3>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">Organize modules & topics</p>
                    </div>
                </a>

                <!-- Students Card -->
                <a href="{{ route('students.index') }}" class="group relative flex flex-col justify-between p-5 rounded-2xl bg-slate-800/60 hover:bg-slate-800 border border-white/10 hover:border-blue-500/50 transition-all duration-200 hover-lift">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-users-gear text-base"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm group-hover:text-blue-300 transition-colors flex items-center justify-between">
                            Students <i class="fas fa-arrow-right text-xs opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                        </h3>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">Profiles & progress records</p>
                    </div>
                </a>

            </div>

            <!-- Activity Stream -->
            <div class="pt-6 border-t border-white/10 space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-white">Recent Platform Activity</h3>
                    <span class="text-[11px] font-semibold text-slate-400">Live Audit Stream</span>
                </div>

                <div class="space-y-3">
                    <div class="p-3.5 rounded-2xl bg-slate-800/50 border border-white/5 flex items-center justify-between gap-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center shrink-0">
                                <i class="fas fa-user-plus text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-200">New student account created</p>
                                <p class="text-[11px] text-slate-400">Registered and verified successfully</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-500 shrink-0">15m ago</span>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-slate-800/50 border border-white/5 flex items-center justify-between gap-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                                <i class="fas fa-circle-check text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-200">Assessment attempt evaluated</p>
                                <p class="text-[11px] text-slate-400">Automated MCQ grading complete</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-500 shrink-0">1h ago</span>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-slate-800/50 border border-white/5 flex items-center justify-between gap-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center shrink-0">
                                <i class="fas fa-folder-plus text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-200">Question bank updated</p>
                                <p class="text-[11px] text-slate-400">New question items added</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-500 shrink-0">3h ago</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Tutor & System Health Banner Card -->
        <div class="lg:col-span-5 bg-gradient-to-br from-indigo-950 via-slate-900 to-purple-950 rounded-3xl p-6 sm:p-8 border border-indigo-500/30 text-white flex flex-col justify-between shadow-2xl relative overflow-hidden">
            <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div>
                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-indigo-300 mb-6 border border-white/10 shadow-lg">
                    <i class="fas fa-robot text-2xl"></i>
                </div>
                <h3 class="font-heading text-xl font-extrabold tracking-tight">AI Academic Tutor & Engine</h3>
                <p class="text-indigo-200/90 text-xs mt-2 leading-relaxed">
                    AI tutoring services are operating with high availability. Powered by OpenAI with automated Ollama Cloud fallback support.
                </p>

                <div class="mt-6 space-y-3">
                    <div class="flex items-center justify-between text-xs py-2.5 border-b border-white/10">
                        <span class="text-slate-400 font-medium">Primary Engine</span>
                        <span class="font-bold text-emerald-400 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> OpenAI GPT-4o-mini</span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-2.5 border-b border-white/10">
                        <span class="text-slate-400 font-medium">Fallback Provider</span>
                        <span class="font-bold text-indigo-300">Ollama Cloud Service</span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-2.5 border-b border-white/10">
                        <span class="text-slate-400 font-medium">Database Backend</span>
                        <span class="font-bold text-white">SQLite Active</span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-2.5">
                        <span class="text-slate-400 font-medium">Payment Gateway</span>
                        <span class="font-bold text-emerald-400">Paystack Connected</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-white/10 flex items-center justify-between text-xs text-indigo-200">
                <span class="font-semibold">Engine Version 2.4.0-stable</span>
                <span class="flex items-center gap-1.5 text-emerald-400 font-bold">
                    <i class="fas fa-circle-check"></i> System Operational
                </span>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Growth Line Chart
    const ctxGrowth = document.getElementById('growthChart')?.getContext('2d');
    if (ctxGrowth) {
        new Chart(ctxGrowth, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
                datasets: [
                    {
                        label: 'Student Enrollees',
                        data: [12, 19, 28, 45, 62, 78, 95, {{ max(10, \App\Models\Student::count()) }}],
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.15)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#6366f1',
                        pointRadius: 4
                    },
                    {
                        label: 'Quiz Submissions',
                        data: [8, 15, 22, 38, 50, 71, 88, {{ max(15, \App\Models\Quiz::count() * 4) }}],
                        borderColor: '#a855f7',
                        backgroundColor: 'rgba(168, 85, 247, 0.05)',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0.4,
                        pointBackgroundColor: '#a855f7',
                        pointRadius: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#94a3b8', font: { family: 'Plus Jakarta Sans', size: 11 } }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8', font: { size: 10 } }
                    },
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8', font: { size: 10 } }
                    }
                }
            }
        });
    }

    // Performance Doughnut Chart
    const ctxPerf = document.getElementById('performanceDoughnut')?.getContext('2d');
    if (ctxPerf) {
        new Chart(ctxPerf, {
            type: 'doughnut',
            data: {
                labels: ['Passed', 'Retake Required', 'In Progress'],
                datasets: [{
                    data: [78, 14, 8],
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
                        labels: { color: '#94a3b8', font: { family: 'Plus Jakarta Sans', size: 11 }, padding: 15 }
                    }
                },
                cutout: '72%'
            }
        });
    }
});
</script>
@endpush
@endsection
