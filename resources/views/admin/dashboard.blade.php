@extends('layouts.app')

@section('title', 'Admin Control Center')

@section('content')
<div class="space-y-8 pb-10">

    <!-- Hero Welcome Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950/90 to-slate-900 p-8 sm:p-10 text-white shadow-2xl border border-indigo-500/25 border-glow">
        <!-- Ambient background glows -->
        <div class="absolute -right-10 -bottom-10 w-96 h-96 bg-gradient-to-br from-indigo-500/25 to-purple-500/35 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-10 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute left-1/4 -bottom-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3.5 max-w-2xl">
                <div class="flex flex-wrap items-center gap-2.5">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-500/15 backdrop-blur-md border border-indigo-500/30 text-indigo-300 text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-sm shadow-emerald-400"></span>
                        Admin Control Center
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 text-xs font-semibold">
                        <i class="fas fa-bolt text-amber-400 text-[10px]"></i> Real-time Analytics
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-300 text-xs font-semibold">
                        <i class="fas fa-robot text-indigo-400 text-[10px]"></i> AI Monitoring
                    </span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white font-heading leading-tight">
                    Welcome back, <span class="bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 bg-clip-text text-transparent">{{ Auth::user()->name ?? 'Admin' }}</span> 👋
                </h1>
                <p class="text-indigo-200/80 text-sm sm:text-base leading-relaxed">
                    Live system intelligence and operational telemetry across active students, course completion rates, automated grading, and AI tutor interactions.
                </p>
            </div>

            <!-- Quick Action CTA Pill Group -->
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('quizzes.create') }}" class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 text-white font-bold text-xs shadow-xl shadow-indigo-600/30 hover:shadow-indigo-600/50 hover:scale-[1.02] active:scale-95 transition-all duration-200">
                    <i class="fas fa-plus text-xs"></i>
                    <span>Create Quiz</span>
                </a>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-2xl bg-slate-800/80 hover:bg-slate-700/80 backdrop-blur-md border border-slate-700 text-slate-200 font-bold text-xs hover:text-white transition-all duration-200 shadow-md">
                    <i class="fas fa-book-bookmark text-emerald-400 text-xs"></i>
                    <span>Manage Courses</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Key Metrics Stats Grid (4 Primary Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">

        <!-- Active Students Card -->
        <div class="group relative glass-card rounded-3xl p-6 shadow-xl overflow-hidden border-glow">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 font-heading">Active Students</span>
                    <h3 class="text-3xl font-extrabold text-white mt-2 tracking-tight font-heading">
                        {{ number_format($activeStudents ?? 0) }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-3 text-xs font-semibold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full w-fit border border-emerald-500/20">
                        <i class="fas fa-user-check text-[10px]"></i>
                        <span>Enrolled & Active</span>
                    </div>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-indigo-500/15 border border-indigo-500/30 flex items-center justify-center text-indigo-400 shadow-inner group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-user-graduate text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/60 flex items-center justify-between text-xs text-slate-400">
                <span>Roster status</span>
                <a href="{{ route('students.index') }}" class="text-indigo-400 font-semibold hover:underline flex items-center gap-1">
                    <span>View roster</span> <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

        <!-- Pass Rate Metric Card -->
        <div class="group relative glass-card rounded-3xl p-6 shadow-xl overflow-hidden border-glow">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 font-heading">Pass Rate</span>
                    <h3 class="text-3xl font-extrabold text-white mt-2 tracking-tight font-heading">
                        {{ number_format($modulePassRate ?? 0, 1) }}%
                    </h3>
                    <div class="flex items-center gap-1.5 mt-3 text-xs font-semibold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full w-fit border border-emerald-500/20">
                        <i class="fas fa-chart-line text-[10px]"></i>
                        <span>Module Assessments</span>
                    </div>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shadow-inner group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-award text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/60 flex items-center justify-between text-xs text-slate-400">
                <span>Passing threshold 70%</span>
                <span class="text-emerald-400 font-semibold">High Performance</span>
            </div>
        </div>

        <!-- Average Score Card -->
        <div class="group relative glass-card rounded-3xl p-6 shadow-xl overflow-hidden border-glow">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 font-heading">Average Score</span>
                    <h3 class="text-3xl font-extrabold text-white mt-2 tracking-tight font-heading">
                        {{ number_format($averageScore ?? 0, 1) }}%
                    </h3>
                    <div class="flex items-center gap-1.5 mt-3 text-xs font-semibold text-purple-400 bg-purple-500/10 px-2.5 py-1 rounded-full w-fit border border-purple-500/20">
                        <i class="fas fa-bullseye text-[10px]"></i>
                        <span>Overall Mean</span>
                    </div>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-purple-500/15 border border-purple-500/30 flex items-center justify-center text-purple-400 shadow-inner group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-chart-pie text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/60 flex items-center justify-between text-xs text-slate-400">
                <span>Graded attempts</span>
                <span class="text-purple-400 font-semibold">Verified</span>
            </div>
        </div>

        <!-- AI Interactions Metric Card -->
        <div class="group relative glass-card rounded-3xl p-6 shadow-xl overflow-hidden border-glow">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400 font-heading">AI Interactions</span>
                    <h3 class="text-3xl font-extrabold text-white mt-2 tracking-tight font-heading">
                        {{ number_format($aiUsageStats ?? 0) }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-3 text-xs font-semibold text-blue-400 bg-blue-500/10 px-2.5 py-1 rounded-full w-fit border border-blue-500/20">
                        <i class="fas fa-brain text-[10px]"></i>
                        <span>AI Tutor Sessions</span>
                    </div>
                </div>
                <div class="w-13 h-13 rounded-2xl bg-blue-500/15 border border-blue-500/30 flex items-center justify-center text-blue-400 shadow-inner group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-wand-magic-sparkles text-xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/60 flex items-center justify-between text-xs text-slate-400">
                <span>AI Service Status</span>
                <span class="text-blue-400 font-semibold">Active & Cached</span>
            </div>
        </div>

    </div>

    <!-- Secondary Telemetry Row: Catalog Totals -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="glass-panel rounded-2xl p-5 border border-slate-800/80 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-lg shadow-inner">
                    <i class="fas fa-book-open"></i>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">Total Courses</p>
                    <p class="text-2xl font-extrabold text-white font-heading mt-0.5">{{ number_format($courseCount ?? \App\Models\Course::count()) }}</p>
                </div>
            </div>
            <a href="{{ route('courses.index') }}" class="p-2 rounded-xl text-slate-400 hover:text-indigo-400 hover:bg-slate-800 transition">
                <i class="fas fa-arrow-up-right-from-square text-xs"></i>
            </a>
        </div>

        <div class="glass-panel rounded-2xl p-5 border border-slate-800/80 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-lg shadow-inner">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">Total Modules</p>
                    <p class="text-2xl font-extrabold text-white font-heading mt-0.5">{{ number_format($moduleCount ?? \App\Models\Module::count()) }}</p>
                </div>
            </div>
            <a href="{{ route('courses.index') }}" class="p-2 rounded-xl text-slate-400 hover:text-purple-400 hover:bg-slate-800 transition">
                <i class="fas fa-arrow-up-right-from-square text-xs"></i>
            </a>
        </div>

        <div class="glass-panel rounded-2xl p-5 border border-slate-800/80 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg shadow-inner">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">Course Completion</p>
                    <p class="text-2xl font-extrabold text-white font-heading mt-0.5">{{ number_format($courseCompletionRate ?? 0, 1) }}%</p>
                </div>
            </div>
            <div class="px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold">
                On Track
            </div>
        </div>
    </div>

    <!-- Analytics Charts & Performance Diagnostics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Chart.js Activity & Engagement Visualizer -->
        <div class="lg:col-span-8 glass-panel rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-800 flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 animate-pulse"></span>
                        <h2 class="text-xl font-extrabold text-white tracking-tight font-heading">Platform Engagement & Assessment Flow</h2>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Comparative assessment trends and AI tutoring interactions over time</p>
                </div>
                <div class="inline-flex items-center p-1 rounded-2xl bg-slate-900 border border-slate-800 text-xs font-semibold">
                    <span class="px-3 py-1 rounded-xl bg-indigo-600 text-white shadow-sm">Monthly</span>
                    <span class="px-3 py-1 rounded-xl text-slate-400 hover:text-slate-200 cursor-pointer">Weekly</span>
                </div>
            </div>

            <!-- Canvas -->
            <div class="relative w-full h-72 sm:h-80">
                <canvas id="adminAnalyticsChart"></canvas>
            </div>

            <div class="grid grid-cols-3 gap-4 pt-6 mt-6 border-t border-slate-800/80 text-center">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Auto-Graded Quizzes</p>
                    <p class="text-lg font-bold text-slate-200 mt-1 font-heading">Active Flow</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Attempt Policy</p>
                    <p class="text-lg font-bold text-indigo-400 mt-1 font-heading">Max 4 Attempts</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500">AI Response Cache</p>
                    <p class="text-lg font-bold text-emerald-400 mt-1 font-heading">1 hr Cache</p>
                </div>
            </div>
        </div>

        <!-- Most Difficult Topics Ranking Widget -->
        <div class="lg:col-span-4 glass-panel rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-800 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center text-sm">
                            <i class="fas fa-triangle-exclamation"></i>
                        </div>
                        <h2 class="text-lg font-bold text-white font-heading">Most Difficult Topics</h2>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-400">Lowest Avg</span>
                </div>
                <p class="text-xs text-slate-400 mb-5">Topics where students struggle most based on quiz percentage scores.</p>

                <!-- Ranking list -->
                <div class="space-y-4">
                    @forelse($mostDifficultTopics ?? [] as $index => $topic)
                    <div class="p-3.5 bg-slate-900/80 rounded-2xl border border-slate-800 flex items-center justify-between group hover:border-slate-700 transition">
                        <div class="flex items-center space-x-3 min-w-0">
                            <span class="w-7 h-7 rounded-xl bg-slate-800 text-slate-400 font-extrabold text-xs flex items-center justify-center shrink-0 font-mono">
                                #{{ $index + 1 }}
                            </span>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-200 truncate font-heading group-hover:text-indigo-300 transition-colors">
                                    {{ $topic->title }}
                                </p>
                                <div class="w-28 bg-slate-800 rounded-full h-1.5 mt-1.5 overflow-hidden">
                                    <div class="bg-rose-500 h-1.5 rounded-full" style="width: {{ min(100, max(10, round($topic->avg_score))) }}%"></div>
                                </div>
                            </div>
                        </div>
                        <span class="text-xs font-extrabold text-rose-400 bg-rose-500/10 px-2.5 py-1 rounded-xl border border-rose-500/20 shrink-0 font-mono">
                            {{ round($topic->avg_score, 1) }}%
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-8 text-slate-500">
                        <i class="fas fa-circle-check text-2xl text-emerald-400/60 mb-2"></i>
                        <p class="text-xs font-medium text-slate-400">No score bottlenecks detected yet.</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Metrics update as students complete topics.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-800/80 text-center">
                <a href="{{ route('courses.index') }}" class="text-xs font-bold text-indigo-400 hover:text-indigo-300 transition flex items-center justify-center gap-1.5">
                    <span>Manage Course Curriculum</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>

    </div>

    <!-- Quick Navigation Control Deck & Recent System Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Command & Quick Action Grid -->
        <div class="lg:col-span-7 glass-panel rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-800">
            <h2 class="text-lg font-bold text-white mb-4 font-heading flex items-center gap-2">
                <i class="fas fa-bolt text-amber-400"></i>
                Administrative Quick Actions
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <a href="{{ route('quizzes.index') }}" class="group p-4 bg-slate-900/90 rounded-2xl border border-slate-800 hover:border-purple-500/40 transition-all duration-300 flex items-start space-x-3.5">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/15 border border-purple-500/30 text-purple-400 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition">
                        <i class="fas fa-clipboard-question"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-200 text-sm font-heading group-hover:text-purple-300 transition">Manage Quizzes</p>
                        <p class="text-xs text-slate-400 mt-0.5">Configure 60-question pools & duration limits</p>
                    </div>
                </a>

                <a href="{{ route('courses.index') }}" class="group p-4 bg-slate-900/90 rounded-2xl border border-slate-800 hover:border-emerald-500/40 transition-all duration-300 flex items-start space-x-3.5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition">
                        <i class="fas fa-book-bookmark"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-200 text-sm font-heading group-hover:text-emerald-300 transition">Manage Courses</p>
                        <p class="text-xs text-slate-400 mt-0.5">Structure modules, topics & reading content</p>
                    </div>
                </a>

                <a href="{{ route('students.index') }}" class="group p-4 bg-slate-900/90 rounded-2xl border border-slate-800 hover:border-blue-500/40 transition-all duration-300 flex items-start space-x-3.5">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/15 border border-blue-500/30 text-blue-400 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fas fa-users font-heading"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-200 text-sm font-heading group-hover:text-blue-300 transition">Manage Students</p>
                        <p class="text-xs text-slate-400 mt-0.5">Inspect academic rosters & update profiles</p>
                    </div>
                </a>

                <button @click="cmdPaletteOpen = true" class="group p-4 bg-slate-900/90 rounded-2xl border border-slate-800 hover:border-indigo-500/40 transition-all duration-300 flex items-start space-x-3.5 text-left w-full">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/15 border border-indigo-500/30 text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition">
                        <i class="fas fa-terminal"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-200 text-sm font-heading group-hover:text-indigo-300 transition">Command Palette</p>
                        <p class="text-xs text-slate-400 mt-0.5">Quickly search actions & section shortcuts (⌘K)</p>
                    </div>
                </button>

            </div>
        </div>

        <!-- System Activity Timeline Stream -->
        <div class="lg:col-span-5 glass-panel rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-800">
            <h2 class="text-lg font-bold text-white mb-4 font-heading flex items-center gap-2">
                <i class="fas fa-clock-rotate-left text-indigo-400"></i>
                Recent System Activity
            </h2>
            <div class="space-y-3.5">
                <div class="flex items-start space-x-3 p-3 bg-slate-900/70 rounded-2xl border border-slate-800">
                    <div class="w-8 h-8 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center shrink-0 text-xs">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-200 truncate">New Student Registration</p>
                        <p class="text-[11px] text-slate-400">Student accounts created & auto-synced</p>
                    </div>
                    <span class="text-[10px] text-slate-500 font-mono">Recent</span>
                </div>

                <div class="flex items-start space-x-3 p-3 bg-slate-900/70 rounded-2xl border border-slate-800">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 text-xs">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-200 truncate">Automated Assessment Grading</p>
                        <p class="text-[11px] text-slate-400">Objective questions evaluated real-time</p>
                    </div>
                    <span class="text-[10px] text-slate-500 font-mono">Active</span>
                </div>

                <div class="flex items-start space-x-3 p-3 bg-slate-900/70 rounded-2xl border border-slate-800">
                    <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center shrink-0 text-xs">
                        <i class="fas fa-brain"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-200 truncate">AI Tutor Session Active</p>
                        <p class="text-[11px] text-slate-400">GPT-4o-mini academic guidance cached</p>
                    </div>
                    <span class="text-[10px] text-slate-500 font-mono">Synced</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Telemetry Footer Note -->
    <div class="mt-8 text-center text-xs text-slate-500 border-t border-slate-800/80 pt-6">
        <i class="fas fa-shield-halved text-indigo-400 mr-1.5"></i> Admin Control Center • Updated {{ now()->format('M d, Y h:i A') }}
    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('adminAnalyticsChart')?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
                    datasets: [
                        {
                            label: 'Assessment Pass Rate (%)',
                            data: [65, 72, 78, 75, 82, 88, 85, {{ round($modulePassRate ?? 80) }}],
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.08)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2.5,
                            pointBackgroundColor: '#10b981',
                            pointRadius: 4
                        },
                        {
                            label: 'AI Tutor Interactions',
                            data: [120, 190, 300, 450, 620, 810, 950, {{ min(1200, max(100, ($aiUsageStats ?? 50) * 10)) }}],
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99, 102, 241, 0.08)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2.5,
                            pointBackgroundColor: '#6366f1',
                            pointRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#94a3b8',
                                font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#f8fafc',
                            bodyColor: '#cbd5e1',
                            borderColor: '#334155',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#64748b', font: { family: 'Plus Jakarta Sans', size: 11 } }
                        },
                        y: {
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#64748b', font: { family: 'Plus Jakarta Sans', size: 11 } }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection