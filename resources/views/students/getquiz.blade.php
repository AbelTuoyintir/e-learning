@extends('layouts.studentNavBar')
@section('title', isset($course) ? $course->title . ' – Quizzes' : 'All Quizzes')

@section('content')
<div class="min-h-screen bg-linear-to-br from-gray-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('students.enrolledcourses') }}"
                       class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                        My Courses
                    </a>
                </li>
                @if(isset($course))
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </li>
                    <li class="text-gray-600 font-medium">{{ $course->title }}</li>
                @endif
            </ol>
        </nav>

        {{-- Page Header --}}
        <div class="mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Course Quizzes</h1>
                    <p class="text-gray-600 text-lg">Test your knowledge with these interactive quizzes</p>
                </div>
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-3 rounded-xl shadow-lg">
                    <div class="text-center">
                        <span class="block text-2xl font-bold">{{ $quizzes->count() }}</span>
                        <span class="text-sm font-medium">Quiz{{ $quizzes->count() == 1 ? '' : 'zes' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quizzes Grid --}}
        @if($quizzes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($quizzes as $quiz)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl border border-gray-100">
                        {{-- Quiz Header --}}
                        <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-xl font-bold text-gray-900 line-clamp-2">{{ $quiz->title }}</h3>
                                <span class="bg-white text-blue-600 text-xs font-semibold px-3 py-1 rounded-full border border-blue-200">
                                    {{ $quiz->duration }} min
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center text-gray-600">
                                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $quiz->questions_count }} Qs
                                    </span>

                                    @if($quiz->attempts_count > 0)
                                        <span class="flex items-center text-green-600">
                                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Attempted
                                        </span>
                                    @endif
                                </div>

                                @if($quiz->attempts_count > 0)
                                    <div class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        Score: {{ $quiz->latestAttempt->score ?? 'N/A' }}%
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Quiz Details --}}
                        <div class="p-6">
                            @if($quiz->due_at)
                                <div class="mb-4">
                                    @if(now()->isAfter($quiz->due_at))
                                        <div class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-lg">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            Overdue
                                        </div>
                                    @else
                                        <div class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            Due {{ $quiz->due_at->diffForHumans() }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="space-y-3">
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                                    </svg>
                                    Passing Score: {{ $quiz->passing_score ?? 60 }}%
                                </div>

                                @if($quiz->description)
                                    <p class="text-gray-600 text-sm line-clamp-2">{{ $quiz->description }}</p>
                                @endif
                            </div>

                            {{-- Action Button --}}
                            <div class="mt-6">
                                <a href="{{ route('quiz.start', $quiz) }}"
                                   class="block w-full text-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transform transition-all duration-300 hover:scale-[1.02] shadow-lg hover:shadow-xl">
                                    @if($quiz->attempts_count > 0)
                                        <div class="flex items-center justify-center space-x-2">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Review / Re-attempt Quiz</span>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center space-x-2">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Start Quiz Now</span>
                                        </div>
                                    @endif
                                </a>

                                @if($quiz->attempts_count > 0)
                                    <p class="text-center text-green-600 text-sm mt-2 font-medium">
                                        ✓ You've completed {{ $quiz->attempts_count }} attempt{{ $quiz->attempts_count > 1 ? 's' : '' }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 mx-auto mb-6 text-gray-400">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">No Quizzes Available</h3>
                    <p class="text-gray-600 mb-8">{{ isset($course) ? 'There are no quizzes published for this course yet. Check back later or contact your instructor.' : 'You haven\'t enrolled in any courses with quizzes yet.' }}</p>
                    <a href="{{ route('students.enrolledcourses') }}"
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-semibold rounded-lg hover:from-gray-700 hover:to-gray-800 transition-all duration-300">
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
            <div class="mt-12 bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Quiz Progress</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-blue-50 rounded-xl">
                        <div class="text-3xl font-bold text-blue-600">{{ $quizzes->where('attempts_count', '>', 0)->count() }}</div>
                        <div class="text-gray-600">Quizzes Attempted</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-xl">
                        <div class="text-3xl font-bold text-green-600">{{ $quizzes->where('attempts_count', 0)->count() }}</div>
                        <div class="text-gray-600">Quizzes Pending</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-xl">
                        <div class="text-3xl font-bold text-purple-600">{{ $quizzes->sum('questions_count') }}</div>
                        <div class="text-gray-600">Total Questions</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add hover effects and animations
    document.addEventListener('DOMContentLoaded', function() {
        const quizCards = document.querySelectorAll('.transform');

        quizCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endpush
