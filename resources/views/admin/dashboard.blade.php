@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Hero Welcome Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950/90 to-slate-900 p-8 sm:p-10 text-white shadow-2xl border border-indigo-500/25 border-glow">
        <!-- Ambient background glows -->
        <div class="absolute -right-10 -bottom-10 w-96 h-96 bg-gradient-to-br from-indigo-500/25 to-purple-500/35 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -top-10 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute left-1/4 -bottom-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3.5 max-w-2xl">
                <div class="flex flex-wrap items-center gap-2.5">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-indigo-500/15 backdrop-blur-md border border-indigo-500/30 text-indigo-300 text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-sm shadow-emerald-400"></span>
                        Admin Control Center
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 text-xs font-semibold">
                        <i class="fas fa-bolt text-amber-400 text-[10px]"></i> Real-time Analytics
                    </span>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white font-heading leading-tight">
                    Welcome back, <span class="bg-gradient-to-r from-indigo-300 via-purple-300 to-pink-300 bg-clip-text text-transparent">{{ Auth::user()->name ?? 'Admin' }}</span>! 👋
                </h1>
                <p class="text-indigo-200/80 text-sm sm:text-base leading-relaxed">
                    Real-time monitoring of learning engagements, AI tutoring sessions, quiz completion metrics, and course catalog status.
                </p>
            </div>

            <!-- Quick Action CTA Pill -->
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('quizzes.create') }}" class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 text-white font-bold text-xs shadow-xl shadow-indigo-600/30 hover:shadow-indigo-600/50 hover:scale-[1.02] active:scale-95 transition-all duration-200">
                    <i class="fas fa-plus text-xs"></i>
                    <span>Create Quiz</span>
                </a>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center p-3.5 rounded-2xl bg-slate-800/80 hover:bg-slate-700/80 backdrop-blur-md border border-slate-700 text-slate-300 hover:text-white transition-all duration-200 shadow-md" title="Manage Catalog">
                    <i class="fas fa-sliders text-sm"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Key Metrics Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">

        <!-- Active Students -->
        <div class="group relative glass-panel rounded-3xl p-6 shadow-lg hover:shadow-indigo-500/10 transition-all duration-300 overflow-hidden">
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
                <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shadow-inner group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Courses -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Total Courses</p>
                    <p class="text-3xl font-bold text-slate-800">{{ \App\Models\Course::count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Quizzes -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Total Quizzes</p>
                    <p class="text-3xl font-bold text-slate-800">{{ \App\Models\Quiz::count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-question-circle text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Questions -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Total Questions</p>
                    <p class="text-3xl font-bold text-slate-800">{{ \App\Models\Question::count() }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-list text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 mb-8">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            <a href="{{ route('quizzes.index') }}" class="flex items-center p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                <i class="fas fa-plus-circle text-indigo-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-slate-800">Manage Quizzes</p>
                    <p class="text-sm text-slate-600">Create and edit quizzes</p>
                </div>
            </a>

            <a href="{{ route('courses.index') }}" class="flex items-center p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                <i class="fas fa-book text-green-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-slate-800">Manage Courses</p>
                    <p class="text-sm text-slate-600">Organize course content</p>
                </div>
            </a>

            <a href="{{ route('students.index') }}" class="flex items-center p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                <i class="fas fa-users text-blue-600 text-xl mr-3"></i>
                <div>
                    <p class="font-medium text-slate-800">Manage Students</p>
                    <p class="text-sm text-slate-600">View and manage students</p>
                </div>
            </a>

        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Recent Activity</h2>
        <div class="space-y-4">
            <div class="flex items-center p-4 bg-slate-50 rounded-lg">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-user-plus text-blue-600"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-slate-800">New student registered</p>
                    <p class="text-sm text-slate-600">2 hours ago</p>
                </div>
            </div>

            <div class="flex items-center p-4 bg-slate-50 rounded-lg">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-plus-circle text-green-600"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-slate-800">New quiz created</p>
                    <p class="text-sm text-slate-600">5 hours ago</p>
                </div>
            </div>

            <div class="flex items-center p-4 bg-slate-50 rounded-lg">
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-question-circle text-purple-600"></i>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-slate-800">Questions updated</p>
                    <p class="text-sm text-slate-600">1 day ago</p>
                </div>
            </div>
        </div>
    </div>

    <!-- footer note -->
    <div class="mt-10 text-center text-xs text-slate-400 border-t border-slate-200 pt-6">
        <i class="fas fa-shield-alt text-indigo-300 mr-1"></i> Admin dashboard · updated {{ now()->format('M d, Y h:i A') }}
    </div>
</div>
@endsection