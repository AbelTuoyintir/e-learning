@extends('layouts.app')

@section('title', 'Quiz Settings')

@section('content')
<div class="space-y-6 max-w-3xl mx-auto">

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <i class="fas fa-sliders text-lg"></i>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Quiz Settings</h1>
                <p class="text-xs text-slate-500 mt-0.5">Custom configurations for {{ $quiz->title }}</p>
            </div>
        </div>

        <a href="{{ route('quizzes.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs transition">
            <i class="fas fa-arrow-left"></i>
            <span>Back</span>
        </a>
    </div>

    <!-- Settings Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <form action="{{ route('quizzes.settings.update', $quiz->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Difficulty -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Difficulty Level</label>
                <select name="difficulty"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 transition font-medium capitalize">
                    <option value="easy" {{ $quiz->difficulty == 'easy' ? 'selected' : '' }}>Easy</option>
                    <option value="medium" {{ $quiz->difficulty == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="hard" {{ $quiz->difficulty == 'hard' ? 'selected' : '' }}>Hard</option>
                </select>
            </div>

            <!-- Time Limit -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Time Limit (minutes)</label>
                <input type="number" name="time_limit"
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 transition font-medium"
                       placeholder="e.g. 30" min="0"
                       value="{{ $quiz->time_limit ?? '' }}">
                <p class="text-[11px] text-slate-400 mt-1">Leave blank for untimed quiz mode.</p>
            </div>

            <!-- Shuffle Options -->
            <div class="p-4 bg-slate-50/70 border border-slate-100 rounded-2xl space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <label for="shuffle_questions" class="text-xs font-bold text-slate-800">Shuffle Questions</label>
                        <p class="text-[11px] text-slate-500">Randomize question order for each attempt</p>
                    </div>
                    <input type="checkbox" id="shuffle_questions" name="shuffle_questions" value="1"
                           {{ $quiz->shuffle_questions ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded transition">
                </div>

                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <div>
                        <label for="shuffle_answers" class="text-xs font-bold text-slate-800">Shuffle Options</label>
                        <p class="text-[11px] text-slate-500">Randomize order of options A, B, C, D</p>
                    </div>
                    <input type="checkbox" id="shuffle_answers" name="shuffle_answers" value="1"
                           {{ $quiz->shuffle_answers ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded transition">
                </div>
            </div>

            <!-- Pass Mark -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Pass Mark (%)</label>
                <input type="number" name="pass_mark"
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 transition font-medium"
                       placeholder="e.g. 70" min="0" max="100"
                       value="{{ $quiz->pass_mark ?? '' }}">
                <p class="text-[11px] text-slate-400 mt-1">Score percentage required to unlock next module.</p>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition">
                    <i class="fas fa-floppy-disk"></i>
                    <span>Save Settings</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
