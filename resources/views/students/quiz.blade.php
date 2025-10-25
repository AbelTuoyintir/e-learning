@extends('layouts.app')

@section('title', 'Available Quizzes')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Available Quizzes</h1>

    @if($quizzes->isEmpty())
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            <p>No quizzes available at the moment.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($quizzes as $quiz)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $quiz->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ $quiz->description }}</p>

                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm text-gray-500">
                                {{ $quiz->questions_count }} questions
                            </span>
                            <span class="text-sm text-gray-500">
                                {{ $quiz->time_limit }} minutes
                            </span>
                        </div>

                        <a href="{{ route('quiz.start', $quiz->id) }}"
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-center block transition-colors duration-200">
                            Start Quiz
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
