@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">
    <!-- Page Header -->
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        ⚙️ Quiz Settings for: <span class="text-blue-600">{{ $quiz->title }}</span>
    </h1>

    <!-- Settings Card -->
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <form action="{{ route('quizzes.settings.update', $quiz->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Difficulty -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Difficulty Level</label>
                <select name="difficulty"
                        class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300">
                    <option value="easy" {{ $quiz->difficulty == 'easy' ? 'selected' : '' }}>Easy</option>
                    <option value="medium" {{ $quiz->difficulty == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="hard" {{ $quiz->difficulty == 'hard' ? 'selected' : '' }}>Hard</option>
                </select>
            </div>

            <!-- Time Limit -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Time Limit (minutes)</label>
                <input type="number" name="time_limit"
                       class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300"
                       placeholder="e.g. 30" min="0"
                       value="{{ $quiz->time_limit ?? '' }}">
                <p class="text-sm text-gray-500 mt-1">Leave blank for no time limit.</p>
            </div>

            <!-- Shuffle Options -->
            <div class="flex items-center space-x-3">
                <input type="checkbox" id="shuffle_questions" name="shuffle_questions" value="1"
                       {{ $quiz->shuffle_questions ? 'checked' : '' }}
                       class="h-5 w-5 text-blue-600 focus:ring-blue-500 rounded">
                <label for="shuffle_questions" class="text-gray-700">Shuffle Questions</label>
            </div>

            <div class="flex items-center space-x-3">
                <input type="checkbox" id="shuffle_answers" name="shuffle_answers" value="1"
                       {{ $quiz->shuffle_answers ? 'checked' : '' }}
                       class="h-5 w-5 text-blue-600 focus:ring-blue-500 rounded">
                <label for="shuffle_answers" class="text-gray-700">Shuffle Answer Options</label>
            </div>

            <!-- Pass Mark -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Pass Mark (%)</label>
                <input type="number" name="pass_mark"
                       class="border p-3 w-full rounded-lg focus:ring focus:ring-blue-300"
                       placeholder="e.g. 50" min="0" max="100"
                       value="{{ $quiz->pass_mark ?? '' }}">
                <p class="text-sm text-gray-500 mt-1">Percentage required to pass this quiz.</p>
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
