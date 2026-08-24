@extends('layouts.app')

@section('title', 'Add Question')

@section('content')
<div class="space-y-6 max-w-3xl mx-auto">

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div>
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <i class="fas fa-plus text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Add Question</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Quiz: <span class="font-bold text-indigo-600">{{ $quiz->title }}</span></p>
                </div>
            </div>
        </div>

        <a href="{{ route('questions.index', $quiz->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs transition">
            <i class="fas fa-arrow-left"></i>
            <span>Question Bank</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <form action="{{ route('questions.store', ['quiz' => $quiz]) }}" method="POST" class="space-y-5">
            @csrf

            <!-- Question -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Question Text</label>
                <textarea name="question_text" rows="3"
                          class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium"
                          placeholder="Enter question prompt..." required>{{ old('question_text') }}</textarea>
            </div>

            <!-- Options Grid -->
            <div class="space-y-3">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">Answer Options (A, B, C, D)</label>

                <div class="space-y-2">
                    <input type="text" name="option_a" placeholder="Option A" value="{{ old('option_a') }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium" required>
                    <input type="text" name="option_b" placeholder="Option B" value="{{ old('option_b') }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium" required>
                    <input type="text" name="option_c" placeholder="Option C" value="{{ old('option_c') }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium" required>
                    <input type="text" name="option_d" placeholder="Option D" value="{{ old('option_d') }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium" required>
                </div>
            </div>

            <!-- Correct Option & Points Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Correct Option</label>
                    <select name="correct_option" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 font-bold" required>
                        <option value="" disabled @selected(!old('correct_option'))>-- Select Correct Answer --</option>
                        <option value="A" @selected(old('correct_option') === 'A')>Option A</option>
                        <option value="B" @selected(old('correct_option') === 'B')>Option B</option>
                        <option value="C" @selected(old('correct_option') === 'C')>Option C</option>
                        <option value="D" @selected(old('correct_option') === 'D')>Option D</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Points</label>
                    <input type="number" name="points" min="1" value="{{ old('points', 1) }}"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 font-bold" required>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('questions.index', $quiz->id) }}" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs transition">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition">
                    <i class="fas fa-floppy-disk text-xs"></i>
                    <span>Save Question</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
