@extends('layouts.studentNavBar')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">My Quizzes</h1>

    @if($enrolledCourses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($enrolledCourses as $enrollment)
                @php
                    $course = $enrollment->course;
                @endphp

                @if($course->quizzes && $course->quizzes->count() > 0)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $course->title }}</h3>
                            <p class="text-gray-600 text-sm mb-4">Total Quizzes: {{ $course->quizzes->count() }}</p>

                            <div class="space-y-3 mb-4">
                                @foreach($course->quizzes as $quiz)
                                    <div class="border rounded-lg p-3 hover:bg-gray-50">
                                        <h4 class="font-medium text-gray-700">{{ $quiz->title }}</h4>
                                        @if($quiz->due_at)
                                            <p class="text-sm text-gray-500 mt-1">
                                                <i class="fas fa-calendar-alt mr-1"></i>
                                                Due: {{ $quiz->due_at->format('M d, Y') }}
                                            </p>
                                        @endif
                                        <div class="mt-2 flex justify-between items-center">
                                            <span class="text-sm text-gray-600">
                                                {{ $quiz->questions_count ?? $quiz->questions->count() }} questions
                                            </span>
                                            <a href="{{ route('students.course.quizzes', $course) }}"
                                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                View <i class="fas fa-arrow-right ml-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <a href="{{ route('students.course.quizzes', $course) }}"
                               class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition text-center block">
                                <i class="fas fa-question-circle mr-2"></i>
                                View All Quizzes
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        @if(!$enrolledCourses->contains(fn($e) => $e->course->quizzes && $e->course->quizzes->count() > 0))
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <i class="fas fa-tasks text-5xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-700 mb-2">No Quizzes Available</h3>
                <p class="text-gray-500 mb-6">You don't have any quizzes in your enrolled courses yet.</p>
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow-md p-8 text-center">
            <i class="fas fa-book-open text-5xl text-gray-400 mb-4"></i>
            <h3 class="text-xl font-medium text-gray-700 mb-2">No Enrolled Courses</h3>
            <p class="text-gray-500 mb-6">You need to enroll in courses to see quizzes.</p>
            <a href="{{ route('students.courses') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center transition">
                <i class="fas fa-search mr-2"></i>
                Browse Courses
            </a>
        </div>
    @endif
</div>
@endsection
