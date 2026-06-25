@extends('layouts.app')

@section('title', 'Tutor Dashboard')

@section('content')
<div class="container mx-auto px-6 py-8">

    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-8 mb-8 text-white shadow-lg">
        <h1 class="text-3xl font-bold mb-2">Hello, {{ Auth::user()->name }}!</h1>
        <p class="text-blue-100">Welcome to your tutor dashboard. Track your courses and student performance.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Courses -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">My Courses</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $courses->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Students -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Total Students</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $totalStudents }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-graduate text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Enrollments -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Enrollments</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $totalEnrollments }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-plus text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Commissions -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Earned Commission</p>
                    <p class="text-3xl font-bold text-slate-800">GH₵ {{ number_format($totalCommissions, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-money-bill-wave text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- My Courses Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-8 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-slate-800">My Courses</h2>
            <a href="{{ route('courses.index') }}" class="text-blue-600 hover:underline text-sm font-medium">Manage All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 text-sm font-medium">
                    <tr>
                        <th class="px-6 py-4">Course Title</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Students</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($courses as $course)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $course->title }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $course->category }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $course->enrollments_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-800 font-semibold">GH₵ {{ number_format($course->price, 2) }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('tutor.course.students', $course->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                <i class="fas fa-users mr-1"></i> View Students
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Enrollments -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-xl font-bold text-slate-800">Recent Enrollments</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($recentEnrollments as $enrollment)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div class="flex items-center">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($enrollment->student->firstname) }}&background=random" class="w-10 h-10 rounded-full mr-3">
                            <div>
                                <p class="font-medium text-slate-800">{{ $enrollment->student->firstname }} {{ $enrollment->student->lastname }}</p>
                                <p class="text-xs text-slate-500">{{ $enrollment->course->title }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-slate-800">GH₵ {{ number_format($enrollment->price_paid, 2) }}</p>
                            <p class="text-[10px] text-slate-400">{{ $enrollment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-xl font-bold text-slate-800 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('courses.index') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition text-center">
                    <i class="fas fa-plus-circle text-blue-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-slate-700">Create Course</span>
                </a>
                <a href="{{ route('quizzes.create') }}" class="flex flex-col items-center p-4 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition text-center">
                    <i class="fas fa-question-circle text-indigo-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-slate-700">Create Quiz</span>
                </a>
                <a href="{{ route('quizzes.index') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition text-center">
                    <i class="fas fa-tasks text-purple-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-slate-700">Manage Quizzes</span>
                </a>
                <a href="#" class="flex flex-col items-center p-4 bg-green-50 rounded-xl hover:bg-green-100 transition text-center">
                    <i class="fas fa-chart-line text-green-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-slate-700">Analytics</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
