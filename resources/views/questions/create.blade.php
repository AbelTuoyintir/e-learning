@extends('layouts.app')

@section('title', 'Add Question')

@section('content')
<div class="space-y-6 max-w-3xl mx-auto">

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
        <div>
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 via-indigo-600 to-purple-600 text-white flex items-center justify-center font-bold shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-plus text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-heading">Add Question</h1>
                    <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Quiz: <span class="font-bold text-indigo-400">{{ $quiz->title }}</span></p>
                </div>
            </div>
        </div>

        <a href="{{ route('questions.index', $quiz->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border border-slate-700/80 bg-slate-800/80 hover:bg-slate-800 text-slate-300 font-bold text-xs transition">
            <i class="fas fa-arrow-left"></i>
            <span>Question Bank</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
        <form action="{{ route('questions.store', ['quiz' => $quiz]) }}" method="POST" class="space-y-5">
            @csrf

            <!-- Question -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2 font-heading">Question Text</label>
                <textarea name="question_text" rows="3"
                          class="w-full px-4 py-3 bg-slate-900/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/50 font-medium placeholder-slate-500"
                          placeholder="Enter question prompt..." required>{{ old('question_text') }}</textarea>
            </div>

            <!-- Options Grid -->
            <div class="space-y-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 font-heading">Answer Options (A, B, C, D)</label>

                <div class="space-y-2.5">
                    <input type="text" name="option_a" placeholder="Option A" value="{{ old('option_a') }}"
                           class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/50 font-medium placeholder-slate-500" required>
                    <input type="text" name="option_b" placeholder="Option B" value="{{ old('option_b') }}"
                           class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/50 font-medium placeholder-slate-500" required>
                    <input type="text" name="option_c" placeholder="Option C" value="{{ old('option_c') }}"
                           class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/50 font-medium placeholder-slate-500" required>
                    <input type="text" name="option_d" placeholder="Option D" value="{{ old('option_d') }}"
                           class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/50 font-medium placeholder-slate-500" required>
                </div>
            </div>

            <!-- Correct Option & Points Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2 font-heading">Correct Option</label>
                    <select name="correct_option" class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/50 font-bold" required>
                        <option value="" disabled @selected(!old('correct_option'))>-- Select Correct Answer --</option>
                        <option value="A" @selected(old('correct_option') === 'A')>Option A</option>
                        <option value="B" @selected(old('correct_option') === 'B')>Option B</option>
                        <option value="C" @selected(old('correct_option') === 'C')>Option C</option>
                        <option value="D" @selected(old('correct_option') === 'D')>Option D</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2 font-heading">Points</label>
                    <input type="number" name="points" min="1" value="{{ old('points', 1) }}"
                           class="w-full px-4 py-2.5 bg-slate-900/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500/50 font-bold" required>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
                <a href="{{ route('questions.index', $quiz->id) }}" class="px-5 py-2.5 rounded-2xl border border-slate-700/80 bg-slate-800/80 hover:bg-slate-800 text-slate-300 font-bold text-xs transition">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 text-white font-bold text-xs rounded-2xl shadow-lg shadow-indigo-500/30 transition">
                    <i class="fas fa-floppy-disk text-xs"></i>
                    <span>Save Question</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
