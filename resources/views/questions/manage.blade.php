@extends('layouts.app')

@section('title', 'Manage Questions')

@section('content')
<div class="container mx-auto p-6">
    <!-- Page Header -->
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Questions for Quiz: <span class="text-blue-600">{{ $quiz->title }}</span>
    </h1>

    <!-- Add New Question Button -->
    <div class="mb-6">
        <a href="{{ route('questions.create', $quiz->id) }}"
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
            + Add New Question
        </a>
    </div>

    <!-- Questions List -->
    @foreach($questions as $question)
    <div class="bg-white rounded-lg shadow-md p-6 mb-4">
        <!-- Question -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold">{{ $question->question_text }}</h3>
            <span class="text-sm text-gray-500">Points: {{ $question->points }}</span>

        </div>

   <ul class="space-y-2">
    <li class="p-3 rounded-lg border bg-gray-50
        @if($question->correct_option === $question->{'option a'}) border-green-400 bg-green-50 @endif">
        <span class="font-semibold">A:</span> {{ $question->{'option a'} }}
        @if($question->correct_option === $question->{'option a'})
            <span class="text-green-600 ml-2 text-sm">✓ Correct</span>
        @endif
    </li>
    <li class="p-3 rounded-lg border bg-gray-50
        @if($question->correct_option === $question->{'option b'}) border-green-400 bg-green-50 @endif">
        <span class="font-semibold">B:</span> {{ $question->{'option b'} }}
        @if($question->correct_option === $question->{'option b'})
            <span class="text-green-600 ml-2 text-sm">✓ Correct</span>
        @endif
    </li>
    <li class="p-3 rounded-lg border bg-gray-50
        @if($question->correct_option === $question->{'option c'}) border-green-400 bg-green-50 @endif">
        <span class="font-semibold">C:</span> {{ $question->{'option c'} }}
        @if($question->correct_option === $question->{'option c'})
            <span class="text-green-600 ml-2 text-sm">✓ Correct</span>
        @endif
    </li>
    <li class="p-3 rounded-lg border bg-gray-50
        @if($question->correct_option === $question->{'option d'}) border-green-400 bg-green-50 @endif">
        <span class="font-semibold">D:</span> {{ $question->{'option d'} }}
        @if($question->correct_option === $question->{'option d'})
            <span class="text-green-600 ml-2 text-sm">✓ Correct</span>
        @endif
    </li>
</ul>

        <!-- Actions -->
        <div class="mt-4 flex space-x-2">
            <a href="{{ route('questions.edit', [$quiz->id, $question->id]) }}"
               class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Edit
            </a>
            <form action="{{ route('questions.destroy', [$quiz->id, $question->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
                        onclick="return confirm('Are you sure you want to delete this question?')">
                    Delete
                </button>
            </form>
        </div>
    </div>
    @endforeach

    <!-- Pagination -->
    <div class="mt-6">
        {{ $questions->links() }}
    </div>
</div>
@endsection
