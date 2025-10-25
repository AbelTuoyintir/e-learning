@extends('layouts.app')
@section('content')

<!-- Dashboard Container -->
<div class="space-y-8">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Admin Dashboard</h1>
            <p class="text-slate-500 mt-1">Welcome back, {{ Auth::user()->name ?? 'Admin' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                <i class="fas fa-download mr-2"></i>Export Report
            </button>
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                <i class="fas fa-plus mr-2"></i>Create Quiz
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Total Students</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">2,543</p>
                    <p class="text-green-600 text-sm mt-2"><i class="fas fa-arrow-up mr-1"></i>+12% from last month</p>
                </div>
                <div class="w-12 h-12 grid place-items-center rounded-xl bg-indigo-100 text-indigo-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Active Quizzes</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">48</p>
                    <p class="text-green-600 text-sm mt-2"><i class="fas fa-arrow-up mr-1"></i>+5 new this week</p>
                </div>
                <div class="w-12 h-12 grid place-items-center rounded-xl bg-purple-100 text-purple-600">
                    <i class="fas fa-question-circle text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Avg. Quiz Score</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">78%</p>
                    <p class="text-red-600 text-sm mt-2"><i class="fas fa-arrow-down mr-1"></i>-2% from last month</p>
                </div>
                <div class="w-12 h-12 grid place-items-center rounded-xl bg-green-100 text-green-600">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Completed Attempts</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">12,847</p>
                    <p class="text-green-600 text-sm mt-2"><i class="fas fa-arrow-up mr-1"></i>+18% from last month</p>
                </div>
                <div class="w-12 h-12 grid place-items-center rounded-xl bg-orange-100 text-orange-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Quiz Performance Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-800">Quiz Performance Trend</h3>
                <select class="px-3 py-2 border border-slate-200 rounded-lg text-sm">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 90 days</option>
                </select>
            </div>
            <div class="h-64 flex items-center justify-center text-slate-400">
                <canvas id="performanceChart" class="w-full h-full"></canvas>
                <!-- Chart.js or similar library can be used here -->
                <div class="absolute text-center">
                    <i class="fas fa-chart-line text-4xl mb-2"></i>
                    <p>Chart will render here</p>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Top Performers</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <img src="https://i.pravatar.cc/40?img=1" alt="User" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-medium text-slate-800">Alice Johnson</p>
                            <p class="text-sm text-slate-500">98% avg score</p>
                        </div>
                    </div>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full">#1</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <img src="https://i.pravatar.cc/40?img=2" alt="User" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-medium text-slate-800">Bob Williams</p>
                            <p class="text-sm text-slate-500">96% avg score</p>
                        </div>
                    </div>
                    <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-2 py-1 rounded-full">#2</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <div class="flex items-center gap-3">
                        <img src="https://i.pravatar.cc/40?img=3" alt="User" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-medium text-slate-800">Carol Davis</p>
                            <p class="text-sm text-slate-500">94% avg score</p>
                        </div>
                    </div>
                    <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-2 py-1 rounded-full">#3</span>
                </div>
            </div>
            <button class="w-full mt-4 text-indigo-600 hover:text-indigo-800 text-sm font-medium">View full leaderboard →</button>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Recent Activity -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-800">Recent Activity</h3>
                <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View all →</a>
            </div>
            <div class="space-y-3">
                <div class="flex items-start gap-3 p-3 hover:bg-slate-50 rounded-lg transition">
                    <div class="w-8 h-8 grid place-items-center rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-user-plus text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-slate-800">New student registered: <span class="font-medium">John Doe</span></p>
                        <p class="text-xs text-slate-500">2 minutes ago</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3 hover:bg-slate-50 rounded-lg transition">
                    <div class="w-8 h-8 grid place-items-center rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-plus text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-slate-800">New quiz created: <span class="font-medium">JavaScript Basics</span></p>
                        <p class="text-xs text-slate-500">15 minutes ago</p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3 hover:bg-slate-50 rounded-lg transition">
                    <div class="w-8 h-8 grid place-items-center rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-trophy text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-slate-800">New high score: <span class="font-medium">Alice Johnson scored 98%</span></p>
                        <p class="text-xs text-slate-500">1 hour ago</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="#" class="flex flex-col items-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition group">
                    <i class="fas fa-plus-circle text-2xl text-indigo-600 mb-2 group-hover:scale-110"></i>
                    <span class="text-sm font-medium text-slate-700">Create Quiz</span>
                </a>
                <a href="#" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition group">
                    <i class="fas fa-user-plus text-2xl text-green-600 mb-2 group-hover:scale-110"></i>
                    <span class="text-sm font-medium text-slate-700">Add Student</span>
                </a>
                <a href="#" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition group">
                    <i class="fas fa-user-plus text-2xl text-green-600 mb-2 group-hover:scale-110"></i>
                    <span class="text-sm font-medium text-slate-700">Add Course</span>
                </a>
                <a href="#" class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-xl transition group">
                    <i class="fas fa-chart-bar text-2xl text-purple-600 mb-2 group-hover:scale-110"></i>
                    <span class="text-sm font-medium text-slate-700">View Results</span>
                </a>
                <a href="#" class="flex flex-col items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-xl transition group">
                    <i class="fas fa-cog text-2xl text-orange-600 mb-2 group-hover:scale-110"></i>
                    <span class="text-sm font-medium text-slate-700">Settings</span>
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
