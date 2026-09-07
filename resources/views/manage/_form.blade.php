@csrf
<div class="space-y-6">

    <!-- Title -->
    <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">
            Quiz Title <span class="text-rose-400">*</span>
        </label>
        <input type="text" name="title" required
               value="{{ old('title', $quiz->title ?? '') }}"
               placeholder="e.g. Fundamental Computer Science Assessment"
               class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition font-medium">
    </div>

    <!-- Description -->
    <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Description</label>
        <textarea name="description" rows="3"
                  placeholder="Provide an overview of what learning topics this quiz evaluates..."
                  class="w-full px-4 py-3 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition font-medium">{{ old('description', $quiz->description ?? '') }}</textarea>
    </div>

    <!-- Image (styled drop zone + preview) -->
    <div>
        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Quiz Cover Image</label>

        <!-- File drop zone -->
        <label
            for="image-upload"
            class="group relative flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-slate-700/80 rounded-2xl cursor-pointer hover:border-indigo-500 hover:bg-slate-800/60 transition">
            <div class="w-10 h-10 rounded-xl bg-slate-800 text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center mb-1.5 transition shadow-sm">
                <i class="fas fa-cloud-arrow-up text-lg"></i>
            </div>
            <span class="text-xs font-semibold text-slate-300 group-hover:text-indigo-300">Click or drag & drop cover image</span>
            <span class="text-[10px] text-slate-500 mt-0.5">PNG, JPG, WEBP up to 2 MB</span>
            <input id="image-upload" type="file" name="image" class="sr-only" accept="image/*"
                   onchange="previewImage(this)">
        </label>

        <!-- Image preview -->
        @if (!empty($quiz->image))
            <div class="mt-3">
                <img id="image-preview" src="{{ asset('storage/' . $quiz->image) }}" alt="Quiz Image"
                     class="h-28 rounded-xl object-cover ring-2 ring-indigo-500/30 shadow-md">
            </div>
        @else
            <img id="image-preview" class="mt-3 h-28 rounded-xl object-cover ring-2 ring-indigo-500/30 shadow-md hidden" alt="Preview">
        @endif
    </div>

    <!-- Course & Module & Topic Selectors Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Course -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">
                Course <span class="text-rose-400">*</span>
            </label>
            <select name="course_id" required
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium">
                <option value="">-- Select Course --</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id', $quiz->course_id ?? '') == $course->id)>
                        {{ $course->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Module -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Module</label>
            <select name="module_id"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium">
                <option value="">-- Select Module --</option>
                @foreach ($modules as $module)
                    <option value="{{ $module->id }}" @selected(old('module_id', $quiz->module_id ?? '') == $module->id)>
                        {{ $module->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Topic -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Topic</label>
            <select name="topic_id"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium">
                <option value="">-- Select Topic --</option>
                @foreach ($topics as $topic)
                    <option value="{{ $topic->id }}" @selected(old('topic_id', $quiz->topic_id ?? '') == $topic->id)>
                        {{ $topic->title }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Difficulty & Question Limit Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Difficulty -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Difficulty Level</label>
            <select name="difficulty"
                    class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium capitalize">
                @foreach(['easy'=>'Easy','medium'=>'Medium','hard'=>'Hard'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('difficulty', $quiz->difficulty ?? 'easy') == $val)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Question Bank Limit -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Question Bank Limit</label>
            <input type="number" name="question_limit" min="1" max="1000" required
                   value="{{ old('question_limit', $quiz->question_limit ?? 60) }}"
                   class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium">
            <p class="text-[11px] text-slate-500 mt-1">Maximum questions stored per quiz (default 60).</p>
        </div>
    </div>

    <!-- Time Limits Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Total Time (minutes)</label>
            <input type="number" name="time_limit" min="1" required
                   value="{{ old('time_limit', $quiz->time_limit ?? 30) }}"
                   class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Time per Question (seconds)</label>
            <input type="number" name="time_per_question" min="5" required
                   value="{{ old('time_per_question', $quiz->time_per_question ?? 30) }}"
                   class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium">
        </div>
    </div>

    <!-- Pass Score & Max Attempts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Passing Score (%)</label>
            <input type="number" name="passing_score" min="0" max="100" required
                   value="{{ old('passing_score', $quiz->passing_score ?? 70) }}"
                   class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5 font-heading">Max Attempts</label>
            <input type="number" name="max_attempts" min="1" required
                   value="{{ old('max_attempts', $quiz->max_attempts ?? 4) }}"
                   class="w-full px-4 py-2.5 bg-slate-800/80 border border-slate-700/80 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium">
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
        <a href="{{ route('quizzes.index') }}" class="px-5 py-2.5 rounded-2xl border border-slate-700/80 bg-slate-800/80 hover:bg-slate-800 text-slate-300 font-bold text-xs transition">
            Cancel
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 hover:scale-[1.02] active:scale-95 text-white font-bold text-xs rounded-2xl shadow-lg shadow-indigo-500/30 transition-all">
            <i class="fas fa-floppy-disk text-xs"></i>
            <span>{{ $submitText }}</span>
        </button>
    </div>

</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
