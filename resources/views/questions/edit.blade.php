@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Edit Question in: <span class="text-blue-600">{{ $quiz->title }}</span>
    </h1>

    <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
        <form action="{{ route('questions.update', [$quiz->id, $question->id]) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-gray-700 font-medium mb-1">Question</label>
                <textarea name="question_text" rows="3"
                          class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300"
                          required>{{ old('question_text', $question->question_text) }}</textarea>
            </div>

            <div class="grid gap-3">
                <input type="text" name="option_a" class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300"
                       value="{{ old('option_a', $question->option_a) }}" placeholder="Option A" required>
                <input type="text" name="option_b" class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300"
                       value="{{ old('option_b', $question->option_b) }}" placeholder="Option B" required>
                <input type="text" name="option_c" class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300"
                       value="{{ old('option_c', $question->option_c) }}" placeholder="Option C" required>
                <input type="text" name="option_d" class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300"
                       value="{{ old('option_d', $question->option_d) }}" placeholder="Option D" required>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Correct Option</label>
                <select name="correct_option" class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300" required>
                    <option value="A" @selected(old('correct_option', strtoupper($question->correct_option)) === 'A')>Option A</option>
                    <option value="B" @selected(old('correct_option', strtoupper($question->correct_option)) === 'B')>Option B</option>
                    <option value="C" @selected(old('correct_option', strtoupper($question->correct_option)) === 'C')>Option C</option>
                    <option value="D" @selected(old('correct_option', strtoupper($question->correct_option)) === 'D')>Option D</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Points</label>
                <input type="number" name="points"
                       class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300"
                       value="{{ old('points', $question->points) }}" min="1" required>
            </div>

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
