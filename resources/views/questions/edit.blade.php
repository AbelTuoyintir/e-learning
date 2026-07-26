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

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Type</label>
                    <select name="type" class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300" required>
                        <option value="MCQ" @selected(old('type', $question->type) === 'MCQ')>Multiple Choice</option>
                        <option value="True/False" @selected(old('type', $question->type) === 'True/False')>True/False</option>
                        <option value="Short Answer" @selected(old('type', $question->type) === 'Short Answer')>Short Answer</option>
                        <option value="Essay" @selected(old('type', $question->type) === 'Essay')>Essay</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Topic</label>
                    <select name="topic_id" class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300">
                        <option value="">Default (from Quiz)</option>
                        @foreach(\App\Models\Topic::all() as $topic)
                            <option value="{{ $topic->id }}" @selected(old('topic_id', $question->topic_id) == $topic->id)>{{ $topic->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Question</label>
                <textarea name="question_text" rows="3"
                          class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300"
                          required>{{ old('question_text', $question->question_text) }}</textarea>
            </div>

            <div class="grid gap-3">
                <input type="text" name="option_a" class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300"
                       value="{{ old('option_a', $question->option_a) }}" placeholder="Option A">
                <input type="text" name="option_b" class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300"
                       value="{{ old('option_b', $question->option_b) }}" placeholder="Option B">
                <input type="text" name="option_c" class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300"
                       value="{{ old('option_c', $question->option_c) }}" placeholder="Option C">
                <input type="text" name="option_d" class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300"
                       value="{{ old('option_d', $question->option_d) }}" placeholder="Option D">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Correct Option</label>
                    <select name="correct_option" class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300" required>
                        <option value="A" @selected(old('correct_option', strtoupper($question->correct_option)) === 'A')>Option A / True</option>
                        <option value="B" @selected(old('correct_option', strtoupper($question->correct_option)) === 'B')>Option B / False</option>
                        <option value="C" @selected(old('correct_option', strtoupper($question->correct_option)) === 'C')>Option C</option>
                        <option value="D" @selected(old('correct_option', strtoupper($question->correct_option)) === 'D')>Option D</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Difficulty</label>
                    <select name="difficulty_level" class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300" required>
                        <option value="Easy" @selected(old('difficulty_level', $question->difficulty_level) === 'Easy')>Easy</option>
                        <option value="Medium" @selected(old('difficulty_level', $question->difficulty_level) === 'Medium')>Medium</option>
                        <option value="Hard" @selected(old('difficulty_level', $question->difficulty_level) === 'Hard')>Hard</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Explanation</label>
                <textarea name="explanation" rows="2"
                          class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300">{{ old('explanation', $question->explanation) }}</textarea>
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
