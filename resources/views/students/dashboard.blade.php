 @extends('layouts.studentNavBar')
 @section('content')
 <!-- Main Container -->
    <div class="container mx-auto p-6">
        <!-- Dashboard Section -->
        <section id="dashboardSection" class="mb-8 section-content">
            <h2 class="text-3xl font-bold text-gray-800 mb-6"><i class="fas fa-tachometer-alt mr-3 text-blue-600"></i>Student Dashboard</h2>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Enrolled Courses</p>
                            <p class="text-3xl font-bold text-blue-600">2</p>
                        </div>
                        <i class="fas fa-book-open text-4xl text-blue-200"></i>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Completed Quizzes</p>
                            <p class="text-3xl font-bold text-green-600">5</p>
                        </div>
                        <i class="fas fa-check-circle text-4xl text-green-200"></i>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Average Score</p>
                            <p class="text-3xl font-bold text-purple-600">87%</p>
                        </div>
                        <i class="fas fa-chart-line text-4xl text-purple-200"></i>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Study Streak</p>
                            <p class="text-3xl font-bold text-orange-600">7 days</p>
                        </div>
                        <i class="fas fa-fire text-4xl text-orange-200"></i>
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Upcoming Quizzes -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4"><i class="fas fa-history mr-2 text-blue-600"></i>Recent Activity</h3>
                    <div class="space-y-3">
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <div>
                                <p class="font-medium">Completed JavaScript Quiz</p>
                                <p class="text-sm text-gray-600">Score: 92% - 2 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-download text-blue-500 mr-3"></i>
                            <div>
                                <p class="font-medium">Downloaded React Materials</p>
                                <p class="text-sm text-gray-600">1 day ago</p>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-plus-circle text-purple-500 mr-3"></i>
                            <div>
                                <p class="font-medium">Enrolled in Data Science</p>
                                <p class="text-sm text-gray-600">3 days ago</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Quizzes -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4"><i class="fas fa-calendar-alt mr-2 text-red-600"></i>Upcoming Quizzes</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border-l-4 border-red-500">
                            <div>
                                <p class="font-medium">HTML & CSS Quiz</p>
                                <p class="text-sm text-gray-600">Due: Oct 15, 2025</p>
                            </div>
                            <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition">
                                Start
                            </button>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                            <div>
                                <p class="font-medium">Python Basics Quiz</p>
                                <p class="text-sm text-gray-600">Due: Oct 18, 2025</p>
                            </div>
                            <button class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm transition">
                                Start
                            </button>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                            <div>
                                <p class="font-medium">Statistics Quiz</p>
                                <p class="text-sm text-gray-600">Due: Oct 25, 2025</p>
                            </div>
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition">
                                Start
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4"><i class="fas fa-bolt mr-2 text-orange-600"></i>Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button onclick="showSection('courses')" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                        <i class="fas fa-search text-2xl text-blue-600 mb-2"></i>
                        <span class="text-sm font-medium">Browse Courses</span>
                    </button>
                    <button onclick="showSection('enrolled')" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                        <i class="fas fa-play-circle text-2xl text-green-600 mb-2"></i>
                        <span class="text-sm font-medium">Continue Learning</span>
                    </button>
                    <button onclick="showSection('results')" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                        <i class="fas fa-chart-bar text-2xl text-purple-600 mb-2"></i>
                        <span class="text-sm font-medium">View Results</span>
                    </button>
                    <button class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
                        <i class="fas fa-calendar-plus text-2xl text-orange-600 mb-2"></i>
                        <span class="text-sm font-medium">Schedule Study</span>
                    </button>
                </div>
            </div>
        </section>
@endsection
