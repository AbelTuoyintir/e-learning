 @extends('layouts.studentNavBar')
 @section('content')
 <!-- Main Container -->
    <div class="container mx-auto p-6 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
        <!-- Dashboard Section -->
        <section id="dashboardSection" class="mb-8 section-content">
            <h2 class="text-3xl font-bold text-gray-800 mb-6"><i class="fas fa-tachometer-alt mr-3 text-blue-600"></i>Student Dashboard</h2>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Enrolled Courses</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $enrolledCoursesCount }}</p>
                        </div>
                        <i class="fas fa-book-open text-4xl text-blue-200"></i>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Completed Quizzes</p>
                            <p class="text-3xl font-bold text-green-600">{{ $completedQuizzesCount }}</p>
                        </div>
                        <i class="fas fa-check-circle text-4xl text-green-200"></i>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Average Score</p>
                            <p class="text-3xl font-bold text-purple-600">{{ number_format($averageScore, 1) }}%</p>
                        </div>
                        <i class="fas fa-chart-line text-4xl text-purple-200"></i>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Course Progress</p>
                            <p class="text-3xl font-bold text-orange-600">{{ $completionPercentage }}%</p>
                        </div>
                        <i class="fas fa-tasks text-4xl text-orange-200"></i>
                    </div>
                </div>
            </div>

            <!-- Learning Progress Details -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-8">
                <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 rounded shadow-sm">
                    <h4 class="font-bold text-blue-800 dark:text-blue-400">Topic Progress</h4>
                    <p class="text-xl md:text-2xl font-bold">{{ $completedTopics }} / {{ $totalTopics }} Topics</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 rounded shadow-sm">
                    <h4 class="font-bold text-green-800 dark:text-green-400">Passed Modules</h4>
                    <p class="text-xl md:text-2xl font-bold">{{ $passedModules }}</p>
                </div>
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded shadow-sm">
                    <h4 class="font-bold text-red-800 dark:text-red-400">Retake Required</h4>
                    <p class="text-xl md:text-2xl font-bold">{{ $retakeModules ?? 0 }}</p>
                </div>
            </div>

        <!-- Recent Activity & Upcoming Quizzes -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4"><i class="fas fa-history mr-2 text-blue-600"></i>Recent Activity</h3>
                <div class="space-y-3">
                    @forelse($recentResults as $result)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <div>
                            <p class="font-medium">Completed {{ $result->quiz->title }}</p>
                            <p class="text-sm text-gray-600">Score: {{ number_format(($result->score / $result->quiz->questions->count()) * 100, 1) }}% - {{ $result->completed_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">No recent activity</p>
                    @endforelse
                </div>
            </div>

            <!-- Available Quizzes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4"><i class="fas fa-calendar-alt mr-2 text-red-600"></i>Available Quizzes</h3>
                <div class="space-y-3">
                    @forelse($availableQuizzes as $quiz)
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border-l-4 border-red-500">
                        <div>
                            <p class="font-medium">{{ $quiz->title }}</p>
                            <p class="text-sm text-gray-600">{{ $quiz->questions->count() }} questions</p>
                        </div>
                        <a href="{{ route('quiz.start', $quiz) }}" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition">
                            Start
                        </a>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">No quizzes available</p>
                    @endforelse
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
                    <button onclick="openAIChat()" class="flex flex-col items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition">
                        <i class="fas fa-robot text-2xl text-red-600 mb-2"></i>
                        <span class="text-sm font-medium">Chat with AI Tutor</span>
                    </button>
                    <button class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition">
                        <i class="fas fa-calendar-plus text-2xl text-orange-600 mb-2"></i>
                        <span class="text-sm font-medium">Schedule Study</span>
                    </button>
                </div>
            </div>
        </section>

@endsection
