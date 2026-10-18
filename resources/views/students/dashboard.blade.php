 @extends('layouts.studentNavBar')
 @section('content')
 <!-- Main Container -->
    <div class="container mx-auto p-6 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
        <!-- Dashboard Section -->
        <section id="dashboardSection" class="mb-8 section-content">
            <h2 class="text-3xl font-bold text-gray-800 mb-6"><i class="fas fa-tachometer-alt mr-3 text-blue-600"></i>Student Dashboard</h2>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Enrolled Courses</p>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $enrolledCoursesCount }}</p>
                        </div>
                        <i class="fas fa-book-open text-4xl text-blue-200 dark:text-blue-900/40"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Completed Quizzes</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $completedQuizzesCount }}</p>
                        </div>
                        <i class="fas fa-check-circle text-4xl text-green-200 dark:text-green-900/40"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Average Score</p>
                            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($averageScore, 1) }}%</p>
                        </div>
                        <i class="fas fa-chart-line text-4xl text-purple-200 dark:text-purple-900/40"></i>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Course Progress</p>
                            <p class="text-3xl font-bold text-orange-600 dark:text-orange-400">{{ $completionPercentage }}%</p>
                        </div>
                        <i class="fas fa-tasks text-4xl text-orange-200 dark:text-orange-900/40"></i>
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

        <!-- Analytics: Strong & Weak Areas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-t-4 border-green-500 border-x border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4"><i class="fas fa-chart-line mr-2 text-green-600 dark:text-green-400"></i>Strong Areas</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($strongAreas as $area)
                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300 rounded-full text-sm font-medium">{{ $area }}</span>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Not enough data to determine strong areas.</p>
                    @endforelse
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-t-4 border-red-500 border-x border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4"><i class="fas fa-exclamation-triangle mr-2 text-red-600 dark:text-red-400"></i>Weak Areas</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($weakAreas as $area)
                        <span class="px-3 py-1 bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300 rounded-full text-sm font-medium">{{ $area }}</span>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Not enough data to determine weak areas.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activity & Upcoming Quizzes -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Activity -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4"><i class="fas fa-history mr-2 text-blue-600 dark:text-blue-400"></i>Recent Activity</h3>
                <div class="space-y-3">
                    @forelse($recentResults as $result)
                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <i class="fas fa-check-circle text-green-500 dark:text-green-400 mr-3"></i>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">Completed {{ $result->quiz->title }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Score: {{ number_format(($result->score / max(1, $result->quiz->questions->count())) * 100, 1) }}% - {{ $result->completed_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm">No recent activity</p>
                    @endforelse
                </div>
            </div>

            <!-- Available Quizzes -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4"><i class="fas fa-calendar-alt mr-2 text-red-600 dark:text-red-400"></i>Available Quizzes</h3>
                <div class="space-y-3">
                    @forelse($availableQuizzes as $quiz)
                    <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border-l-4 border-red-500">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $quiz->title }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $quiz->questions->count() }} questions</p>
                        </div>
                        <a href="{{ route('quiz.start', $quiz) }}" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg text-sm shadow transition duration-200 flex items-center gap-1">
                            <i class="fas fa-play text-xs"></i> Start
                        </a>
                    </div>
                    @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm">No quizzes available</p>
                    @endforelse
                </div>
            </div>
        </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-100 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-4"><i class="fas fa-bolt mr-2 text-amber-500"></i>Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('students.courses') }}" class="flex flex-col items-center justify-center p-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                        <i class="fas fa-search text-2xl mb-2"></i>
                        <span class="text-sm">Browse Courses</span>
                    </a>
                    <a href="{{ route('students.enrolledcourses') }}" class="flex flex-col items-center justify-center p-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                        <i class="fas fa-play-circle text-2xl mb-2"></i>
                        <span class="text-sm">My Courses</span>
                    </a>
                    <a href="{{ route('results.index') }}" class="flex flex-col items-center justify-center p-4 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                        <i class="fas fa-chart-bar text-2xl mb-2"></i>
                        <span class="text-sm">View Results</span>
                    </a>
                    <button onclick="openAIChat()" class="flex flex-col items-center justify-center p-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                        <i class="fas fa-robot text-2xl mb-2"></i>
                        <span class="text-sm">Chat with AI Tutor</span>
                    </button>
                </div>
            </div>
        </section>

@endsection
