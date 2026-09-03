@extends('layouts.app')

@section('title', 'Add Questions')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
        <div>
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 text-indigo-400 flex items-center justify-center font-bold shadow-inner">
                    <i class="fas fa-plus text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight font-heading">Add Questions to {{ $quiz->title }}</h1>
                    <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Add questions manually or upload bulk items via CSV file</p>
                </div>
            </div>
        </div>

        <a href="{{ route('questions.index', $quiz) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl border border-slate-700/80 bg-slate-800/80 hover:bg-slate-800 text-slate-300 hover:text-white font-bold text-xs transition">
            <i class="fas fa-arrow-left"></i>
            <span>Question Bank</span>
        </a>
    </div>

    @php
        $currentCount = $quiz->questions()->count();
        $questionLimit = $quiz->question_limit ?? 60;
        $bankIsFull = $currentCount >= $questionLimit;
    @endphp

    <!-- Info Usage Pill -->
    <div class="p-4 glass-card rounded-2xl flex items-center justify-between text-xs text-slate-300 shadow-md">
        <span class="flex items-center gap-2">
            <i class="fas fa-circle-info text-indigo-400"></i>
            Current Usage: <strong class="text-white">{{ $currentCount }}</strong> / <strong class="text-white">{{ $questionLimit }}</strong> questions stored
        </span>
        @if($bankIsFull)
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30">Bank Full</span>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- Manual Entry Card -->
        <div class="glass-panel p-6 sm:p-8 rounded-3xl shadow-xl space-y-5">
            <h2 class="text-base font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-4 font-heading">
                <i class="fas fa-pen text-indigo-400"></i>
                Manual Question Entry
            </h2>

            <form action="{{ route('questions.store', ['quiz' => $quiz]) }}" method="POST" class="space-y-4">
                @csrf

                <!-- Question -->
                <div>
                    <label for="question_text" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5 font-heading">Question Text</label>
                    <textarea name="question_text" id="question_text" rows="2"
                              class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-medium placeholder-slate-500"
                              placeholder="Enter your question prompt..." required>{{ old('question_text') }}</textarea>
                </div>

                <!-- Options -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 font-heading">Options (A, B, C, D)</label>
                    <input type="text" name="option_a" placeholder="Option A" value="{{ old('option_a') }}"
                           class="w-full px-4 py-2 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-xs focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-medium placeholder-slate-500" required>
                    <input type="text" name="option_b" placeholder="Option B" value="{{ old('option_b') }}"
                           class="w-full px-4 py-2 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-xs focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-medium placeholder-slate-500" required>
                    <input type="text" name="option_c" placeholder="Option C" value="{{ old('option_c') }}"
                           class="w-full px-4 py-2 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-xs focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-medium placeholder-slate-500" required>
                    <input type="text" name="option_d" placeholder="Option D" value="{{ old('option_d') }}"
                           class="w-full px-4 py-2 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-xs focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-medium placeholder-slate-500" required>
                </div>

                <!-- Correct Option & Points Grid -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div>
                        <label for="correct_option" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1 font-heading">Correct Answer</label>
                        <select name="correct_option" id="correct_option"
                                class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-xs focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-bold" required>
                            <option value="" disabled @selected(!old('correct_option'))>Select Answer</option>
                            <option value="A" @selected(old('correct_option') === 'A')>Option A</option>
                            <option value="B" @selected(old('correct_option') === 'B')>Option B</option>
                            <option value="C" @selected(old('correct_option') === 'C')>Option C</option>
                            <option value="D" @selected(old('correct_option') === 'D')>Option D</option>
                        </select>
                    </div>

                    <div>
                        <label for="points" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1 font-heading">Points</label>
                        <input type="number" name="points" id="points" min="1"
                               value="{{ old('points', 1) }}"
                               class="w-full px-3 py-2 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-xs focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 font-bold">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-800">
                    <button type="submit" @disabled($bankIsFull)
                            class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-2xl shadow-lg shadow-indigo-600/30 transition disabled:bg-slate-800 disabled:text-slate-500 disabled:cursor-not-allowed">
                        {{ $bankIsFull ? 'Question Capacity Reached' : 'Save Question' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- CSV Bulk Import Card -->
        <div id="bulk-upload" class="glass-panel p-6 sm:p-8 rounded-3xl shadow-xl space-y-5">
            <h2 class="text-base font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-4 font-heading">
                <i class="fas fa-file-csv text-purple-400"></i>
                Bulk Import CSV
            </h2>

            <p class="text-xs text-slate-400 leading-relaxed">
                Upload CSV file formatted with options and correct choice.
                <span class="block font-bold text-amber-400 mt-0.5">Note: Use A, B, C, D for correct_option column.</span>
            </p>

            <form action="{{ route('questions.import', $quiz->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="upload-form">
                @csrf

                <!-- Drop area -->
                <div id="drop-area" class="relative flex flex-col items-center justify-center p-6 border-2 border-dashed border-slate-700 rounded-2xl cursor-pointer bg-slate-800/40 hover:bg-slate-800 hover:border-purple-500 transition group text-center">
                    <i id="upload-icon" class="fas fa-cloud-arrow-up text-3xl text-slate-400 group-hover:text-purple-400 mb-2 transition"></i>

                    <p id="upload-text" class="text-xs font-semibold text-slate-300">
                        <span class="text-purple-400 font-bold">Click to select file</span> or drag & drop CSV
                    </p>
                    <p class="text-[10px] text-slate-500 mt-1">Supports .csv up to 5MB</p>

                    <input id="file-upload" type="file" name="file" accept=".csv,.xlsx,.xls" class="absolute inset-0 opacity-0 cursor-pointer">
                </div>

                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

                <div class="flex gap-2">
                    <button type="submit" id="submit-button"
                            class="w-full py-2.5 px-4 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-2xl shadow-lg shadow-purple-600/30 transition">
                        Upload CSV File
                    </button>
                </div>
            </form>

            <div class="pt-4 border-t border-slate-800">
                <span class="text-[11px] font-bold text-slate-400">CSV Structure Sample:</span>
                <pre class="bg-slate-900 border border-slate-800 p-3 rounded-2xl mt-1.5 text-[10px] text-slate-300 font-mono overflow-x-auto">question_text,option_a,option_b,option_c,option_d,correct_option,points
"What is 2+2?","3","4","5","6","B",1</pre>
            </div>
        </div>

    </div>

</div>
@endsection
