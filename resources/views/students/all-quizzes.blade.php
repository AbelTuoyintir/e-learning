@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">All Quizzes</h1>
        <div class="text-sm text-gray-600">
            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                {{ $quizzes->count() }} quizzes
            </span>
        </div>
    </div>

    @if($quizzes->count() > 0)
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quiz</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Questions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attempts</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($quizzes as $quiz)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-question-circle text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $quiz->title }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($quiz->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $quiz->course->title ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($quiz->due_at)
                                    <div class="text-sm text-gray-900">{{ $quiz->due_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">
                                        @if($quiz->due_at->isFuture())
                                            <span class="text-green-600">Due in {{ $quiz->due_at->diffForHumans() }}</span>
                                        @else
                                            <span class="text-red-600">Overdue by {{ $quiz->due_at->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">No due date</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $quiz->questions_count ?? $quiz->questions->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($quiz->attempts_count > 0)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        {{ $quiz->attempts_count }} attempt(s)
                                    </span>
                                    @if($quiz->latest_attempt)
                                        <div class="text-xs text-gray-500 mt-1">
                                            Last: {{ $quiz->latest_attempt->score ?? 0 }}%
                                        </div>
                                    @endif
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                        Not attempted
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($quiz->attempts_count >= $quiz->max_attempts)
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                        Attempts exhausted
                                    </span>
                                @elseif($quiz->due_at && $quiz->due_at->isPast())
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                        Expired
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        Available
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($quiz->attempts_count < $quiz->max_attempts && (!$quiz->due_at || $quiz->due_at->isFuture()))
                                    <a href="{{ route('students.quiz.attempt', $quiz) }}"
                                       class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg transition">
                                        <i class="fas fa-play-circle mr-1"></i> Take Quiz
                                    </a>
                                @else
                                    <button disabled
                                       class="text-gray-400 bg-gray-100 px-3 py-1 rounded-lg cursor-not-allowed">
                                        <i class="fas fa-ban mr-1"></i> Unavailable
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md p-8 text-center">
            <i class="fas fa-tasks text-5xl text-gray-400 mb-4"></i>
            <h3 class="text-xl font-medium text-gray-700 mb-2">No Quizzes Available</h3>
            <p class="text-gray-500 mb-6">You don't have any quizzes assigned in your enrolled courses.</p>
            <a href="{{ route('students.enrolledcourses') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center transition">
                <i class="fas fa-book-open mr-2"></i>
                Go to My Courses
            </a>
        </div>
    @endif
</div>
@endsection
