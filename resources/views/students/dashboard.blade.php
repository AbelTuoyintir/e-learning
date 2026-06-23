 @extends('layouts.studentNavBar')
 @section('content')
 <!-- Main Container -->
    <div class="container mx-auto p-6 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
        <!-- Dashboard Section -->
        <section id="dashboardSection" class="mb-8 section-content">
            <h2 class="text-3xl font-bold text-gray-800 mb-6"><i class="fas fa-tachometer-alt mr-3 text-blue-600"></i>Student Dashboard</h2>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded shadow-sm">
                    <h4 class="font-bold text-blue-800">Topic Progress</h4>
                    <p class="text-2xl font-bold">{{ $completedTopics }} / {{ $totalTopics }} Topics</p>
                </div>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm">
                    <h4 class="font-bold text-green-800">Passed Modules</h4>
                    <p class="text-2xl font-bold">{{ $passedModules }}</p>
                </div>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                    <h4 class="font-bold text-red-800">Retake Required</h4>
                    <p class="text-2xl font-bold">{{ $retakeModules ?? 0 }}</p>
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

        <!-- AI Chat Modal (Basic Implementation) -->
        <div id="aiChatModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg w-full max-w-2xl h-[600px] flex flex-col shadow-2xl">
                <div class="p-4 border-b flex justify-between items-center bg-blue-600 text-white rounded-t-lg">
                    <h3 class="font-bold"><i class="fas fa-robot mr-2"></i>AI Academic Tutor</h3>
                    <button onclick="closeAIChat()" class="text-white hover:text-gray-200">&times;</button>
                </div>
                <div id="chatHistory" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
                    <div class="bg-blue-100 p-3 rounded-lg max-w-[80%]">
                        Hello! I am your AI academic tutor. How can I help you with your studies today?
                    </div>
                </div>
                <div class="p-4 border-t bg-white">
                    <form id="aiChatForm" class="flex gap-2">
                        <input type="text" id="aiQuestion" class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ask a question...">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">Send</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openAIChat() {
                document.getElementById('aiChatModal').classList.remove('hidden');
            }
            function closeAIChat() {
                document.getElementById('aiChatModal').classList.add('hidden');
            }

            document.getElementById('aiChatForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                const question = document.getElementById('aiQuestion').value;
                if (!question) return;

                const chatHistory = document.getElementById('chatHistory');

                // Add user question
                const userMsg = document.createElement('div');
                userMsg.className = 'bg-white p-3 rounded-lg max-w-[80%] ml-auto border shadow-sm';
                userMsg.textContent = question;
                chatHistory.appendChild(userMsg);
                document.getElementById('aiQuestion').value = '';

                // Add loading indicator
                const loadingMsg = document.createElement('div');
                loadingMsg.className = 'bg-blue-100 p-3 rounded-lg max-w-[80%] italic text-gray-500';
                loadingMsg.textContent = 'Thinking...';
                chatHistory.appendChild(loadingMsg);
                chatHistory.scrollTop = chatHistory.scrollHeight;

                try {
                    const response = await fetch('{{ route("student.ai.chat") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ question })
                    });
                    const data = await response.json();

                    chatHistory.removeChild(loadingMsg);
                    const aiMsg = document.createElement('div');
                    aiMsg.className = 'bg-blue-100 p-3 rounded-lg max-w-[80%]';
                    aiMsg.textContent = data.response;
                    chatHistory.appendChild(aiMsg);
                } catch (error) {
                    loadingMsg.textContent = 'Error connecting to AI tutor.';
                }
                chatHistory.scrollTop = chatHistory.scrollHeight;
            });
        </script>
@endsection
