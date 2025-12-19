@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-6 py-8">

    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 mb-8 text-white">
        <h1 class="text-3xl font-bold mb-2">Welcome back, Admin!</h1>
        <p class="text-indigo-100">Manage your quiz application from this dashboard.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <!-- Students -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Total Students</p>
                    <p class="text-3xl font-bold text-slate-800">{{ \App\Models\Student::count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
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

</div>
@endsection
