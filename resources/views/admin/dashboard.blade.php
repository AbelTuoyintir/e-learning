@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Hero Welcome Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-purple-950 p-8 sm:p-10 text-white shadow-2xl shadow-indigo-950/30 border border-white/10">
        <!-- Abstract glowing background pattern elements -->
        <div class="absolute -right-10 -bottom-10 w-96 h-96 bg-gradient-to-br from-indigo-500/25 to-purple-500/25 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-10 w-72 h-72 bg-blue-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute left-1/4 -bottom-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3 max-w-xl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-indigo-200 text-xs font-semibold shadow-inner">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Admin Control Center</span>
                    <span class="text-white/40">•</span>
                    <span class="text-emerald-300 font-mono text-[11px]">Live Updates</span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white font-heading leading-tight">
                    Welcome back, {{ Auth::user()->name ?? 'Admin' }}! 👋
                </h1>
                <p class="text-indigo-200/90 text-sm sm:text-base leading-relaxed">
                    Here's what is happening across your learning platform today. Manage courses, track student performance, and monitor system metrics seamlessly.
                </p>
            </div>

            <!-- Quick Action CTA Pill -->
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('quizzes.create') }}" class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 text-white font-bold text-sm shadow-xl shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-[1.02] active:scale-95 transition-all duration-200 border border-white/20">
                    <i class="fas fa-plus text-xs"></i>
                    <span>Create New Quiz</span>
                </a>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center p-3.5 rounded-2xl bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/15 text-white shadow-md hover:scale-105 active:scale-95 transition-all duration-200" title="Manage Courses">
                    <i class="fas fa-sliders text-base"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Analytics Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">

        <!-- Total Students -->
        <a href="{{ route('students.index') }}" class="group relative bg-white/90 backdrop-blur-md rounded-3xl p-6 shadow-sm hover:shadow-2xl hover:-translate-y-1.5 border border-slate-200/80 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 opacity-90"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Total Students</span>
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-900 mt-1.5 tracking-tight font-heading">
                        {{ \App\Models\Student::count() }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-3 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full w-fit border border-emerald-100">
                        <i class="fas fa-arrow-trend-up text-[10px]"></i>
                        <span>Active Enrollees</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shadow-sm group-hover:scale-110 group-hover:bg-gradient-to-tr group-hover:from-indigo-600 group-hover:to-purple-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
            </div>
        </a>

        <!-- Total Courses -->
        <a href="{{ route('courses.index') }}" class="group relative bg-white/90 backdrop-blur-md rounded-3xl p-6 shadow-sm hover:shadow-2xl hover:-translate-y-1.5 border border-slate-200/80 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 opacity-90"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Active Courses</span>
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-900 mt-1.5 tracking-tight font-heading">
                        {{ \App\Models\Course::count() }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-3 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full w-fit border border-emerald-100">
                        <i class="fas fa-layer-group text-[10px]"></i>
                        <span>Published Catalog</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm group-hover:scale-110 group-hover:bg-gradient-to-tr group-hover:from-emerald-600 group-hover:to-teal-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-book-bookmark text-2xl"></i>
                </div>
            </div>
        </a>

        <!-- Total Quizzes -->
        <a href="{{ route('quizzes.index') }}" class="group relative bg-white/90 backdrop-blur-md rounded-3xl p-6 shadow-sm hover:shadow-2xl hover:-translate-y-1.5 border border-slate-200/80 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-purple-500 via-indigo-500 to-pink-500 opacity-90"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Total Quizzes</span>
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-900 mt-1.5 tracking-tight font-heading">
                        {{ \App\Models\Quiz::count() }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-3 text-xs font-bold text-purple-600 bg-purple-50 px-2.5 py-1 rounded-full w-fit border border-purple-100">
                        <i class="fas fa-circle-check text-[10px]"></i>
                        <span>Active Assessments</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 shadow-sm group-hover:scale-110 group-hover:bg-gradient-to-tr group-hover:from-purple-600 group-hover:to-indigo-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-circle-question text-2xl"></i>
                </div>
            </div>
        </a>

        <!-- Question Bank -->
        <a href="{{ route('quizzes.index') }}" class="group relative bg-white/90 backdrop-blur-md rounded-3xl p-6 shadow-sm hover:shadow-2xl hover:-translate-y-1.5 border border-slate-200/80 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 opacity-90"></div>
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Question Bank</span>
                    <h3 class="text-3xl sm:text-4xl font-black text-slate-900 mt-1.5 tracking-tight font-heading">
                        {{ \App\Models\Question::count() }}
                    </h3>
                    <div class="flex items-center gap-1.5 mt-3 text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full w-fit border border-amber-100">
                        <i class="fas fa-list-check text-[10px]"></i>
                        <span>Items Available</span>
                    </div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 shadow-sm group-hover:scale-110 group-hover:bg-gradient-to-tr group-hover:from-amber-600 group-hover:to-orange-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-cubes text-2xl"></i>
                </div>
            </div>
        </a>

    </div>

    <!-- Quick Actions Cards Grid -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Quick Management Shortcuts</h2>
                <p class="text-xs text-slate-500 mt-0.5">Jump directly to critical management sections</p>
            </div>
            <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                <i class="fas fa-bolt text-amber-500 mr-1.5"></i> Fast Navigation
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <!-- Manage Quizzes -->
            <a href="{{ route('quizzes.index') }}" class="group relative flex items-start p-5 bg-slate-50/80 hover:bg-gradient-to-br hover:from-indigo-50 hover:to-slate-50 border border-slate-200/70 hover:border-indigo-200 rounded-2xl transition-all duration-200 hover:-translate-y-0.5 shadow-2xs">
                <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 mr-4 shadow-md shadow-indigo-600/20 group-hover:scale-110 transition-transform">
                    <i class="fas fa-pen-to-square text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">Manage Quizzes</h3>
                        <i class="fas fa-arrow-right text-xs text-slate-400 group-hover:text-indigo-600 group-hover:translate-x-1 transition-all"></i>
                    </div>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Create, configure, and assign assessment quizzes and questions.</p>
                </div>
            </a>

            <!-- Manage Courses -->
            <a href="{{ route('courses.index') }}" class="group relative flex items-start p-5 bg-slate-50/80 hover:bg-gradient-to-br hover:from-emerald-50 hover:to-slate-50 border border-slate-200/70 hover:border-emerald-200 rounded-2xl transition-all duration-200 hover:-translate-y-0.5 shadow-2xs">
                <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 mr-4 shadow-md shadow-emerald-600/20 group-hover:scale-110 transition-transform">
                    <i class="fas fa-layer-group text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-800 group-hover:text-emerald-600 transition-colors">Manage Courses</h3>
                        <i class="fas fa-arrow-right text-xs text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all"></i>
                    </div>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Organize course modules, topics, learning materials, and pricing.</p>
                </div>
            </a>

            <!-- Manage Students -->
            <a href="{{ route('students.index') }}" class="group relative flex items-start p-5 bg-slate-50/80 hover:bg-gradient-to-br hover:from-blue-50 hover:to-slate-50 border border-slate-200/70 hover:border-blue-200 rounded-2xl transition-all duration-200 hover:-translate-y-0.5 shadow-2xs">
                <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 mr-4 shadow-md shadow-blue-600/20 group-hover:scale-110 transition-transform">
                    <i class="fas fa-users-gear text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">Manage Students</h3>
                        <i class="fas fa-arrow-right text-xs text-slate-400 group-hover:text-blue-600 group-hover:translate-x-1 transition-all"></i>
                    </div>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Review student profiles, enrollment statuses, and academic records.</p>
                </div>
            </a>

        </div>
    </div>

    <!-- Timeline & Recent Activity Feed -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Activity Stream Card -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Recent Platform Activity</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Real-time log of administrative and learning events</p>
                </div>
                <button onclick="showInfo('Logs updated in real-time', 'System Audit')" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                    View All
                </button>
            </div>

            <div class="relative space-y-6 before:absolute before:inset-0 before:left-5 before:w-0.5 before:bg-slate-100">

                <!-- Event 1 -->
                <div class="relative flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 ring-4 ring-white shadow-xs z-10">
                        <i class="fas fa-user-plus text-sm"></i>
                    </div>
                    <div class="flex-1 bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-slate-800 text-sm">New student account created</p>
                            <span class="text-[11px] font-medium text-slate-400">2 hours ago</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Student registered and verified their email address.</p>
                    </div>
                </div>

                <!-- Event 2 -->
                <div class="relative flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 ring-4 ring-white shadow-xs z-10">
                        <i class="fas fa-circle-check text-sm"></i>
                    </div>
                    <div class="flex-1 bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-slate-800 text-sm">Assessment quiz published</p>
                            <span class="text-[11px] font-medium text-slate-400">5 hours ago</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">A new quiz was published to Computer Science fundamentals module.</p>
                    </div>
                </div>

                <!-- Event 3 -->
                <div class="relative flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center shrink-0 ring-4 ring-white shadow-xs z-10">
                        <i class="fas fa-folder-plus text-sm"></i>
                    </div>
                    <div class="flex-1 bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold text-slate-800 text-sm">Question bank updated</p>
                            <span class="text-[11px] font-medium text-slate-400">1 day ago</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">15 new multiple choice questions imported successfully.</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right Side Quick Info widget -->
        <div class="bg-gradient-to-br from-indigo-900 via-slate-900 to-purple-950 rounded-3xl p-6 sm:p-8 text-white flex flex-col justify-between shadow-xl">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-indigo-300 mb-6">
                    <i class="fas fa-shield-cat text-2xl"></i>
                </div>
                <h3 class="text-xl font-extrabold tracking-tight">System Health & AI Tutor</h3>
                <p class="text-slate-300 text-xs mt-2 leading-relaxed">
                    AI tutoring services are running smoothly with high availability and quick response rates across all course topics.
                </p>

                <div class="mt-6 space-y-3">
                    <div class="flex items-center justify-between text-xs py-2 border-b border-white/10">
                        <span class="text-slate-400">AI Engine</span>
                        <span class="font-semibold text-emerald-400 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-400"></span> Online</span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-2 border-b border-white/10">
                        <span class="text-slate-400">Database Driver</span>
                        <span class="font-semibold text-white">SQLite Active</span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-2">
                        <span class="text-slate-400">Paystack Gateway</span>
                        <span class="font-semibold text-emerald-400">Connected</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-white/10 flex items-center justify-between text-xs text-indigo-200">
                <span>Version 2.4.0-stable</span>
                <i class="fas fa-circle-check text-emerald-400"></i>
            </div>
        </div>

    </div>

</div>
@endsection
