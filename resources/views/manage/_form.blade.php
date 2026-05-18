@csrf
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg p-6 md:p-8 space-y-6">

    <!-- Title -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Quiz Title</label>
        <input type="text" name="title" required
               value="{{ old('title', $quiz->title ?? '') }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
    </div>

    <!-- Description -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
        <textarea name="description" rows="4"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">{{ old('description', $quiz->description ?? '') }}</textarea>
    </div>

    <!-- Image (styled drop zone + preview) -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Quiz Cover Image</label>

        <!-- File drop zone -->
        <label
            for="image-upload"
            class="group relative flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition">
            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 group-hover:text-blue-500 mb-2"></i>
            <span class="text-sm text-gray-600 group-hover:text-blue-600">Click or drag & drop to upload</span>
            <span class="text-xs text-gray-400 mt-1">PNG, JPG, GIF up to 2 MB</span>
            <input id="image-upload" type="file" name="image" class="sr-only" accept="image/*"
                   onchange="previewImage(this)">
        </label>

        <!-- Image preview -->
        @if (!empty($quiz->image))
            <div class="mt-4">
                <img id="image-preview" src="{{ asset('storage/' . $quiz->image) }}" alt="Quiz Image"
                     class="h-32 rounded-lg shadow-md">
            </div>
        @else
            <img id="image-preview" class="mt-4 h-32 rounded-lg shadow-md hidden" alt="Preview">
        @endif
    </div>

    <!-- Course -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Course</label>
        <select name="course_id" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            <option value="">-- Select a Course --</option>
            @foreach ($courses as $course)
                <option value="{{ $course->id }}" @selected(old('course_id', $quiz->course_id ?? '') == $course->id)>
                    {{ $course->title }}
                </option>
            @endforeach
        </select>
    </div>
    <!-- Module-->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Module</label>
        <select name="module_id"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            <option value="">-- Select a Module --</option>
            @foreach ($modules as $module)
                <option value="{{ $module->id }}" @selected(old('course_id', $quiz->module_id ?? '') == $module->id)>
                    {{ $module->title }}
                </option>
            @endforeach
        </select>
    </div>
    <!--Topic-->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Topic</label>
        <select name="topic_id"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            <option value="">-- Select a Topic --</option>
            @foreach ($topics as $topic)
                <option value="{{ $topic->id }}" @selected(old('course_id', $quiz->topic_id ?? '') == $topic->id)>
                    {{ $topic->title }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Difficulty -->
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Difficulty</label>
        <select name="difficulty"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
            @foreach(['easy'=>'Easy','medium'=>'Medium','hard'=>'Hard'] as $val => $label)
                <option value="{{ $val }}" @selected(old('difficulty', $quiz->difficulty ?? 'easy') == $val)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Time Limits -->
    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Total Time (minutes)</label>
            <input type="number" name="time_limit" min="1" required
                   value="{{ old('time_limit', $quiz->time_limit ?? 30) }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Time per Question (seconds)</label>
            <input type="number" name="time_per_question" min="5" required
                   value="{{ old('time_per_question', $quiz->time_per_question ?? 30) }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        </div>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Question Bank Limit</label>
        <input type="number" name="question_limit" min="1" max="1000" required
               value="{{ old('question_limit', $quiz->question_limit ?? 60) }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
        <p class="text-xs text-gray-500 mt-1">Set how many questions this quiz can hold (e.g. 60 or 120).</p>
    </div>

    <!-- Submit -->
    <div class="pt-4">
        <button type="submit"
                class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
            {{ $submitText }}
        </button>
    </div>
</div>

{{-- Preview script (vanilla JS, no extra libs) --}}
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
