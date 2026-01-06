@extends('layouts.studentNavBar')
@section('content')
<!-- Results Section -->
<section id="resultsSection" class="mb-8 section-content hidden dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <h2 class="text-3xl font-bold text-gray-800 mb-6"><i class="fas fa-chart-bar mr-3 text-purple-600"></i>My Results</h2>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Overall Performance</h3>
                <i class="fas fa-trophy text-2xl text-yellow-500"></i>
            </div>
            <div class="text-center">
                <div class="relative inline-flex items-center justify-center w-32 h-32">
                    <svg class="w-32 h-32 transform -rotate-90">
                        <circle cx="64" cy="64" r="56" stroke="#e5e7eb" stroke-width="12" fill="none"/>
                        <circle cx="64" cy="64" r="56" stroke="#10b981" stroke-width="12" fill="none"
                                stroke-dasharray="351.86" stroke-dashoffset="70.37"/>
                    </svg>
                    <span class="absolute text-2xl font-bold text-gray-800">87%</span>
                </div>
                <p class="text-sm text-gray-600 mt-2">Average Score</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Grade Distribution</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">A (90-100%)</span>
                    <div class="flex items-center">
                        <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 60%"></div>
                        </div>
                        <span class="text-sm font-medium">3</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">B (80-89%)</span>
                    <div class="flex items-center">
                        <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 40%"></div>
                        </div>
                        <span class="text-sm font-medium">2</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">C (70-79%)</span>
                    <div class="flex items-center">
                        <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: 20%"></div>
                        </div>
                        <span class="text-sm font-medium">1</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Below 70%</span>
                    <div class="flex items-center">
                        <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                        <span class="text-sm font-medium">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Achievements</h3>
            <div class="space-y-3">
                <div class="flex items-center p-3 bg-yellow-50 rounded-lg">
                    <i class="fas fa-medal text-yellow-500 mr-3"></i>
                    <div>
                        <p class="font-medium text-sm">Perfect Score!</p>
                        <p class="text-xs text-gray-600">HTML & CSS Quiz</p>
                    </div>
                </div>
                <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                    <i class="fas fa-fire text-orange-500 mr-3"></i>
                    <div>
                        <p class="font-medium text-sm">7-Day Streak</p>
                        <p class="text-xs text-gray-600">Keep it up!</p>
                    </div>
                </div>
                <div class="flex items-center p-3 bg-green-50 rounded-lg">
                    <i class="fas fa-trophy text-green-500 mr-3"></i>
                    <div>
                        <p class="font-medium text-sm">Top Performer</p>
                        <p class="text-xs text-gray-600">Web Development</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Results Table -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Quiz Results History</h3>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Quiz Name</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Course</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Score</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Grade</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">JavaScript Fundamentals</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Web Development</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Oct 12, 2025</td>
                        <td class="px-4 py-3 text-center">
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm font-medium">92%</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-sm font-medium">A</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View Details
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">Python Basics</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Data Science</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Oct 10, 2025</td>
                        <td class="px-4 py-3 text-center">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm font-medium">85%</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-sm font-medium">B</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View Details
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">HTML & CSS</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Web Development</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Oct 8, 2025</td>
                        <td class="px-4 py-3 text-center">
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm font-medium">96%</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="bg-green-500 text-white px-2 py-1 rounded-full text-sm font-medium">A</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View Details
                            </button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-800">Statistics Overview</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Data Science</td>
                        <td class="px-4 py-3 text-sm text-gray-600">Oct 5, 2025</td>
                        <td class="px-4 py-3 text-center">
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm font-medium">78%</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-sm font-medium">C</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                View Details
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
