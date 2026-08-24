@extends('layouts.app')

@section('title', 'Add Questions')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">

    <!-- Header Card -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
        <div>
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                    <i class="fas fa-plus text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Add Questions to {{ $quiz->title }}</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Add single questions manually or upload bulk items via CSV</p>
                </div>
            </div>
        </div>

        <a href="{{ route('questions.index', $quiz) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold text-xs transition">
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
    <div class="p-4 bg-indigo-50/70 border border-indigo-100 rounded-2xl flex items-center justify-between text-xs text-indigo-900">
        <span class="flex items-center gap-2">
            <i class="fas fa-circle-info text-indigo-600"></i>
            Current Usage: <strong>{{ $currentCount }}</strong> / <strong>{{ $questionLimit }}</strong> questions stored
        </span>
        @if($bankIsFull)
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700">Bank Full</span>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- Manual Entry Card -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-200/80 space-y-5">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-4">
                <i class="fas fa-pen text-indigo-600"></i>
                Manual Question Entry
            </h2>

            <form action="{{ route('questions.store', ['quiz' => $quiz]) }}" method="POST" class="space-y-4">
                @csrf

                <!-- Question -->
                <div>
                    <label for="question_text" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Question Text</label>
                    <textarea name="question_text" id="question_text" rows="2"
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium"
                              placeholder="Enter your question prompt..." required>{{ old('question_text') }}</textarea>
                </div>

                <!-- Options -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">Options (A, B, C, D)</label>
                    <input type="text" name="option_a" placeholder="Option A" value="{{ old('option_a') }}"
                           class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium" required>
                    <input type="text" name="option_b" placeholder="Option B" value="{{ old('option_b') }}"
                           class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium" required>
                    <input type="text" name="option_c" placeholder="Option C" value="{{ old('option_c') }}"
                           class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium" required>
                    <input type="text" name="option_d" placeholder="Option D" value="{{ old('option_d') }}"
                           class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 font-medium" required>
                </div>

                <!-- Correct Option & Points Grid -->
                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div>
                        <label for="correct_option" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Correct Answer</label>
                        <select name="correct_option" id="correct_option"
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 font-bold" required>
                            <option value="" disabled @selected(!old('correct_option'))>Select Answer</option>
                            <option value="A" @selected(old('correct_option') === 'A')>Option A</option>
                            <option value="B" @selected(old('correct_option') === 'B')>Option B</option>
                            <option value="C" @selected(old('correct_option') === 'C')>Option C</option>
                            <option value="D" @selected(old('correct_option') === 'D')>Option D</option>
                        </select>
                    </div>

                    <div>
                        <label for="points" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Points</label>
                        <input type="number" name="points" id="points" min="1"
                               value="{{ old('points', 1) }}"
                               class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-800 text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 font-bold">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-100">
                    <button type="submit" @disabled($bankIsFull)
                            class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-md shadow-indigo-600/20 transition disabled:bg-slate-300 disabled:cursor-not-allowed">
                        {{ $bankIsFull ? 'Question Capacity Reached' : 'Save Question' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- CSV Bulk Import Card -->
        <div id="bulk-upload" class="bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-slate-200/80 space-y-5">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-4">
                <i class="fas fa-file-csv text-purple-600"></i>
                Bulk Import CSV
            </h2>

            <p class="text-xs text-slate-500 leading-relaxed">
                Upload CSV file formatted with options and correct choice.
                <span class="block font-bold text-rose-500 mt-0.5">Note: Use A, B, C, D for correct_option column.</span>
            </p>

            <div id="alert-container"></div>

            <form action="{{ route('questions.import', $quiz->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="upload-form">
                @csrf

                <!-- Drop area -->
                <div id="drop-area" class="relative flex flex-col items-center justify-center p-6 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer bg-slate-50/50 hover:bg-purple-50/30 hover:border-purple-300 transition group text-center">
                    <i id="upload-icon" class="fas fa-cloud-arrow-up text-3xl text-slate-400 group-hover:text-purple-600 mb-2 transition"></i>

                    <p id="upload-text" class="text-xs font-semibold text-slate-700">
                        <span class="text-purple-600 font-bold">Click to select file</span> or drag & drop CSV
                    </p>
                    <p class="text-[10px] text-slate-400 mt-1">Supports .csv up to 5MB</p>

                    <div id="progress-container" class="w-full h-2 mt-3 bg-slate-200 rounded-full overflow-hidden hidden">
                        <div id="progress-bar" class="bg-purple-600 h-full rounded-full transition-all duration-200" style="width: 0%"></div>
                    </div>

                    <div id="file-info" class="mt-3 hidden text-xs font-bold text-slate-800">
                        <p id="file-name"></p>
                        <p id="file-size" class="text-[10px] text-slate-400 font-medium mt-0.5"></p>
                    </div>

                    <input id="file-upload" type="file" name="file" accept=".csv,.xlsx,.xls" class="absolute inset-0 opacity-0 cursor-pointer">
                </div>

                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

                <div class="flex gap-2">
                    <button type="submit" id="submit-button"
                            class="w-full py-2.5 px-4 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md shadow-purple-600/20 transition">
                        Upload CSV File
                    </button>
                    <button type="button" id="reset-button"
                            class="w-full py-2.5 px-4 bg-slate-600 hover:bg-slate-700 text-white font-bold text-xs rounded-xl shadow transition hidden">
                        Select Another File
                    </button>
                </div>
            </form>

            <div class="pt-4 border-t border-slate-100">
                <span class="text-[11px] font-bold text-slate-600">CSV Structure Sample:</span>
                <pre class="bg-slate-50 border border-slate-100 p-3 rounded-xl mt-1.5 text-[10px] text-slate-600 font-mono overflow-x-auto">question_text,option_a,option_b,option_c,option_d,correct_option,points
"What is 2+2?","3","4","5","6","B",1</pre>
            </div>
        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('file-upload');
    const uploadText = document.getElementById('upload-text');
    const progressContainer = document.getElementById('progress-container');
    const progressBar = document.getElementById('progress-bar');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const submitButton = document.getElementById('submit-button');
    const resetButton = document.getElementById('reset-button');
    const form = document.getElementById('upload-form');
    let currentFile = null;

    if (dropArea) {
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => dropArea.classList.add('border-purple-500', 'bg-purple-50/50'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => dropArea.classList.remove('border-purple-500', 'bg-purple-50/50'), false);
        });

        // Handle dropped files
        dropArea.addEventListener('drop', handleDrop, false);
    }

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files && files.length) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    }

    function handleFileSelect(file) {
        currentFile = file;
        fileName.textContent = file.name;
        fileSize.textContent = (file.size / 1024).toFixed(1) + ' KB';
        fileInfo.classList.remove('hidden');
    }

    fileInput?.addEventListener('change', function() {
        if (this.files.length) {
            handleFileSelect(this.files[0]);
        }
    });

    resetButton?.addEventListener('click', function() {
        fileInput.value = '';
        currentFile = null;
        fileInfo.classList.add('hidden');
        resetButton.classList.add('hidden');
        submitButton.classList.remove('hidden');
    });
});
</script>
@endsection
