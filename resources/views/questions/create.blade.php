@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Edit Question in: <span class="text-blue-600">{{ $quiz->title }}</span>
    </h1>

    <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
        <form action="{{ route('questions.update',[$quiz->id, $question->id]) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Question Text -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Question</label>
                <textarea name="question_text" rows="3"
                          class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300"
                          placeholder="Enter your question here..." required>{{ old('question_text', $question->question_text) }}</textarea>
            </div>

            <!-- Points -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Points</label>
                <input type="number" name="points"
                       class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300"
                       value="{{ old('points', $question->points) }}" min="1" required>
            </div>

            <!-- Options -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Answer Options</label>
                <p class="text-sm text-gray-500 mb-3">Enter possible answers and select the correct one.</p>

                @for ($i = 0; $i < 4; $i++)
                    <div class="flex items-center space-x-3 mb-3">
                        <!-- Correct Answer -->
                        <input type="radio" name="correct_option" value="{{ $i }}"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500"
                               @if(old('correct_option', $question->correct_option) == $i) checked @endif required>

                        <!-- Option Text -->
                        <input type="text" name="options[]"
                               class="border p-3 flex-1 rounded-lg focus:ring focus:ring-blue-300"
                               placeholder="Option {{ $i + 1 }}"
                               value="{{ old('options.' . $i, $question->options[$i] ?? '') }}" required>
                    </div>
                @endfor
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg shadow">
                    Update Question
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
