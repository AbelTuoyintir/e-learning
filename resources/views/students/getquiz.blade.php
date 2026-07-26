@extends('layouts.studentNavBar')

@section('title', isset($course) ? $course->title . ' – Quizzes' : 'All Quizzes')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 py-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="mb-8" aria-label="Breadcrumb">
            <ol class="flex items-center flex-wrap gap-2 text-sm">
                <li>
                    <a href="{{ route('students.enrolledcourses') }}"
                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200 font-medium inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"/>
                        </svg>
                        My Courses
                    </a>
                </li>
                @if(isset($course))
                    <li class="flex items-center text-gray-400 dark:text-gray-600">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </li>
                    <li class="text-gray-700 dark:text-gray-300 font-medium truncate max-w-[200px]">{{ $course->title }}</li>
                @endif
                <li class="flex items-center text-gray-400 dark:text-gray-600">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </li>
                <li class="text-gray-900 dark:text-white font-semibold">Quizzes</li>
            </ol>
        </nav>

        {{-- Page Header --}}
        <div class="mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                        {{ isset($course) ? $course->title . ' Quizzes' : 'All Quizzes' }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mt-1 flex items-center">
                        <span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                        Test your knowledge with these interactive quizzes
                    </p>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-2xl shadow-lg shadow-blue-500/20 flex items-center gap-4">
                    <div class="text-center">
                        <span class="block text-3xl font-bold">{{ $quizzes->count() }}</span>
                        <span class="text-sm font-medium opacity-90">Quiz{{ $quizzes->count() == 1 ? '' : 'zes' }}</span>
                    </div>
                    <div class="h-12 w-px bg-white/20"></div>
                    <div class="text-center">
                        <span class="block text-3xl font-bold">{{ $quizzes->sum('questions_count') }}</span>
                        <span class="text-sm font-medium opacity-90">Questions</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter/Search Bar --}}
        <div class="mb-8 flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="quiz-search" placeholder="Search quizzes..." 
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                </div>
            </div>
            <div class="flex gap-2">
                <select id="status-filter" class="px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    <option value="all">All Status</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                    <option value="overdue">Overdue</option>
                </select>
                <button id="reset-filters" class="px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Quizzes Grid --}}
        @if($quizzes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="quiz-grid">
                @foreach($quizzes as $quiz)
                    @php
                        $attempted = $quiz->attempts_count > 0;
                        $latestScore = $quiz->latestAttempt?->score ?? null;
                        $isOverdue = $quiz->due_at && now()->isAfter($quiz->due_at);
                        $status = $attempted ? 'completed' : ($isOverdue ? 'overdue' : 'pending');
                        $progressColor = $latestScore ? ($latestScore >= 70 ? 'green' : ($latestScore >= 50 ? 'yellow' : 'red')) : 'gray';
                    @endphp
                    <div class="quiz-card bg-white dark:bg-gray-900 rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100 dark:border-gray-800"
                         data-status="{{ $status }}"
                         data-title="{{ strtolower($quiz->title) }}">
                        
                        {{-- Quiz Header with Status Badge --}}
                        <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-800 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white line-clamp-2 flex-1 mr-2">{{ $quiz->title }}</h3>
                                <span class="flex-shrink-0 bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 text-xs font-bold px-3 py-1.5 rounded-full border border-blue-200 dark:border-blue-800 shadow-sm">
                                    ⏱️ {{ $quiz->duration ?? 10 }} min
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                <span class="flex items-center text-gray-600 dark:text-gray-400 text-sm">
                                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $quiz->questions_count }} Questions
                                </span>

                                @if($quiz->passing_score)
                                    <span class="flex items-center text-gray-600 dark:text-gray-400 text-sm">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        Pass: {{ $quiz->passing_score }}%
                                    </span>
                                @endif

                                @if($attempted && $latestScore)
                                    <span class="ml-auto text-sm font-bold px-3 py-1 rounded-full 
                                        {{ $latestScore >= 70 ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 
                                           ($latestScore >= 50 ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' : 
                                           'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400') }}">
                                        {{ $latestScore }}%
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Quiz Details --}}
                        <div class="p-6">
                            {{-- Status & Due Date --}}
                            <div class="mb-4">
                                @if($isOverdue)
                                    <div class="inline-flex items-center px-3 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg text-sm font-medium">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        Overdue
                                    </div>
                                @elseif($quiz->due_at)
                                    <div class="inline-flex items-center px-3 py-1.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-lg text-sm font-medium">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Due {{ $quiz->due_at->diffForHumans() }}
                                    </div>
                                @endif
                            </div>

                            {{-- Description --}}
                            @if($quiz->description)
                                <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2 mb-4">{{ $quiz->description }}</p>
                            @endif

                            {{-- Progress Bar for Attempted Quizzes --}}
                            @if($attempted && $latestScore !== null)
                                <div class="mb-4">
                                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                        <span>Score</span>
                                        <span>{{ $latestScore }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="h-2 rounded-full transition-all duration-1000" 
                                             style="width: {{ $latestScore }}%; background-color: {{ $latestScore >= 70 ? '#10b981' : ($latestScore >= 50 ? '#f59e0b' : '#ef4444') }};"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $quiz->attempts_count }} attempt{{ $quiz->attempts_count > 1 ? 's' : '' }} made
                                    </p>
                                </div>
                            @endif

                            {{-- Action Button --}}
                            <a href="{{ route('quiz.start', $quiz) }}"
                               class="block w-full text-center px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transform transition-all duration-300 hover:scale-[1.02] shadow-lg shadow-blue-500/25 hover:shadow-xl flex items-center justify-center gap-2">
                                @if($attempted)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                    </svg>
                                    Retake Quiz
                                @else
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Start Quiz
                                @endif
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">No Quizzes Available</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-8">
                        {{ isset($course) ? 'There are no quizzes published for this course yet. Check back later or contact your instructor.' : 'You haven\'t enrolled in any courses with quizzes yet.' }}
                    </p>
                    <a href="{{ route('students.enrolledcourses') }}"
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-800 hover:to-gray-900 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                        Back to Courses
                    </a>
                </div>
            </div>
        @endif

        {{-- Progress Stats --}}
        @if($quizzes->count() > 0)
            <div class="mt-12 bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zm6-4a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zm6-3a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                    </svg>
                    Your Quiz Progress
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl hover:shadow-md transition-shadow duration-200">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $quizzes->where('attempts_count', '>', 0)->count() }}</div>
                        <div class="text-gray-600 dark:text-gray-400 text-sm font-medium mt-1">Quizzes Attempted</div>
                        <div class="w-full bg-blue-200 dark:bg-blue-800 rounded-full h-1 mt-2">
                            <div class="bg-blue-600 h-1 rounded-full" style="width: {{ $quizzes->count() > 0 ? ($quizzes->where('attempts_count', '>', 0)->count() / $quizzes->count()) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-xl hover:shadow-md transition-shadow duration-200">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $quizzes->where('attempts_count', 0)->count() }}</div>
                        <div class="text-gray-600 dark:text-gray-400 text-sm font-medium mt-1">Quizzes Pending</div>
                        <div class="w-full bg-green-200 dark:bg-green-800 rounded-full h-1 mt-2">
                            <div class="bg-green-600 h-1 rounded-full" style="width: {{ $quizzes->count() > 0 ? ($quizzes->where('attempts_count', 0)->count() / $quizzes->count()) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl hover:shadow-md transition-shadow duration-200">
                        <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $quizzes->sum('questions_count') }}</div>
                        <div class="text-gray-600 dark:text-gray-400 text-sm font-medium mt-1">Total Questions</div>
                        <div class="w-full bg-purple-200 dark:bg-purple-800 rounded-full h-1 mt-2">
                            <div class="bg-purple-600 h-1 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search and Filter functionality
        const searchInput = document.getElementById('quiz-search');
        const statusFilter = document.getElementById('status-filter');
        const resetBtn = document.getElementById('reset-filters');
        const quizCards = document.querySelectorAll('.quiz-card');

        function filterQuizzes() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const statusValue = statusFilter.value;

            quizCards.forEach(card => {
                const title = card.dataset.title || '';
                const status = card.dataset.status || '';
                
                let matchesSearch = title.includes(searchTerm);
                let matchesStatus = statusValue === 'all' || status === statusValue;
                
                if (matchesSearch && matchesStatus) {
                    card.style.display = '';
                    card.style.opacity = '1';
                } else {
                    card.style.display = 'none';
                    card.style.opacity = '0';
                }
            });

            // Update empty state message
            const visibleCards = document.querySelectorAll('.quiz-card[style*="display: block"], .quiz-card:not([style*="display: none"])');
            const grid = document.getElementById('quiz-grid');
            let emptyMessage = grid.querySelector('.no-results-message');
            
            if (visibleCards.length === 0) {
                if (!emptyMessage) {
                    emptyMessage = document.createElement('div');
                    emptyMessage.className = 'no-results-message col-span-full text-center py-12';
                    emptyMessage.innerHTML = `
                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-12">
                            <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <h4 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No matching quizzes found</h4>
                            <p class="text-gray-500 dark:text-gray-400">Try adjusting your search or filter criteria</p>
                        </div>
                    `;
                    grid.appendChild(emptyMessage);
                }
            } else if (emptyMessage) {
                emptyMessage.remove();
            }
        }

        // Event listeners
        searchInput.addEventListener('input', filterQuizzes);
        statusFilter.addEventListener('change', filterQuizzes);
        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = 'all';
            filterQuizzes();
        });

        // Add entrance animation
        quizCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 * index);
        });

        // Keyboard shortcut: Ctrl+F to focus search
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                e.preventDefault();
                searchInput.focus();
            }
            if (e.key === 'Escape') {
                searchInput.blur();
            }
        });
    });
</script>
@endpush