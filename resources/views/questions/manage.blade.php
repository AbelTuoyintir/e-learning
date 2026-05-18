@extends('layouts.app')

@section('title', 'Manage Questions')

@section('content')
<div class="container mx-auto p-6">
    <!-- Page Header -->
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Questions for Quiz: <span class="text-blue-600">{{ $quiz->title }}</span>
    </h1>
    <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800">
        Question bank usage: <strong>{{ $questionCount ?? $questions->total() }}</strong> /
        <strong>{{ $questionLimit ?? ($quiz->question_limit ?? 60) }}</strong>
    </div>

    <!-- Add New Question Button -->
    <div class="mb-6 flex space-x-4">
        <a href="{{ route('questions.create', $quiz->id) }}"
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
            + Add New Question
        </a>

        <!-- Optional: Add Import CSV Button -->
        <a href="{{ route('questions.create', $quiz->id) }}#bulk-upload"
           class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg shadow">
            📁 Import CSV
        </a>
    </div>

    <!-- Questions List -->
    @forelse($questions as $question)
    <div class="bg-white rounded-lg shadow-md p-6 mb-4">
        <!-- Question Header -->
        <div class="mb-4 flex justify-between items-start">
            <div>
                <h3 class="text-lg font-semibold">{{ $question->question_text }}</h3>
                <div class="mt-2 text-sm text-gray-500">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">Points: {{ $question->points }}</span>
                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded ml-2">Correct: {{ $question->correct_option }}</span>
                </div>
            </div>
            <div class="text-xs text-gray-400">
                ID: {{ $question->id }}
            </div>
        </div>

        <!-- Options List -->
        <ul class="space-y-2">
            @php
                // Determine correct answer based on correct_option field
                $correctOptionLetter = strtoupper($question->correct_option);
                $isCorrectA = $correctOptionLetter === 'A';
                $isCorrectB = $correctOptionLetter === 'B';
                $isCorrectC = $correctOptionLetter === 'C';
                $isCorrectD = $correctOptionLetter === 'D';
            @endphp

            <li class="p-3 rounded-lg border bg-gray-50 @if($isCorrectA) border-green-400 bg-green-50 @endif">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="font-semibold">A:</span> {{ $question->option_a }}
                    </div>
                    @if($isCorrectA)
                        <span class="text-green-600 ml-2 text-sm font-medium">✓ Correct Answer</span>
                    @endif
                </div>
            </li>

            <li class="p-3 rounded-lg border bg-gray-50 @if($isCorrectB) border-green-400 bg-green-50 @endif">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="font-semibold">B:</span> {{ $question->option_b }}
                    </div>
                    @if($isCorrectB)
                        <span class="text-green-600 ml-2 text-sm font-medium">✓ Correct Answer</span>
                    @endif
                </div>
            </li>

            <li class="p-3 rounded-lg border bg-gray-50 @if($isCorrectC) border-green-400 bg-green-50 @endif">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="font-semibold">C:</span> {{ $question->option_c }}
                    </div>
                    @if($isCorrectC)
                        <span class="text-green-600 ml-2 text-sm font-medium">✓ Correct Answer</span>
                    @endif
                </div>
            </li>

            <li class="p-3 rounded-lg border bg-gray-50 @if($isCorrectD) border-green-400 bg-green-50 @endif">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="font-semibold">D:</span> {{ $question->option_d }}
                    </div>
                    @if($isCorrectD)
                        <span class="text-green-600 ml-2 text-sm font-medium">✓ Correct Answer</span>
                    @endif
                </div>
            </li>
        </ul>

        <!-- Actions -->
        <div class="mt-4 flex space-x-2">
            <a href="{{ route('questions.edit', [$quiz->id, $question->id]) }}"
               class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                ✏️ Edit
            </a>
            <form action="{{ route('questions.destroy', [$quiz->id, $question->id]) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this question?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                    🗑️ Delete
                </button>
            </form>
        </div>
    </div>
    @empty
    <!-- No Questions Found -->
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <div class="text-gray-400 mb-4">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">No Questions Found</h3>
        <p class="text-gray-500 mb-4">Get started by adding your first question to this quiz.</p>
        <a href="{{ route('questions.create', $quiz->id) }}"
           class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg shadow">
            + Add Your First Question
        </a>
    </div>
    @endforelse

    <!-- Pagination -->
    @if($questions->hasPages())
    <div class="mt-6">
        {{ $questions->links() }}
    </div>
    @endif
</div>

<!-- JavaScript for better confirmation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhance delete confirmation
    const deleteForms = document.querySelectorAll('form[method="POST"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this question?\nThis action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection
