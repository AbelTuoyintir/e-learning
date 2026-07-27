@extends('layouts.app')
<style>
       @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }

        @keyframes progress {
            0% { width: 0%; }
            100% { width: 100%; }
        }

        @keyframes checkmark {
            0% { stroke-dashoffset: 50; }
            100% { stroke-dashoffset: 0; }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .pulse-animation {
            animation: pulse 0.5s ease-in-out;
        }

        .progress-bar {
            height: 4px;
            animation: progress 2s ease-in-out;
        }

        .checkmark {
            stroke-dasharray: 50;
            stroke-dashoffset: 50;
            animation: checkmark 0.5s ease-in-out forwards;
            animation-delay: 0.5s;
        }

        .file-info {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .show-info {
            opacity: 1;
            transform: translateY(0);
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
</style>
@section('content')

<div class="container mx-auto px-4 py-8 max-w-5xl">

    <!-- Page Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900">
            Add Question to <span class="text-blue-600">{{ $quiz->title }}</span>
        </h1>
        <p class="text-gray-600 mt-2">You can add a single question manually or upload multiple via CSV.</p>
    </div>

    @php
        $currentCount = $quiz->questions()->count();
        $questionLimit = $quiz->question_limit ?? 60;
        $bankIsFull = $currentCount >= $questionLimit;
    @endphp
    <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800">
        Question bank usage: <strong>{{ $currentCount }}</strong> / <strong>{{ $questionLimit }}</strong>
    </div>

    @if(session('error'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid md:grid-cols-2 gap-8">
        <!-- Manual Form Card -->
        <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
            <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                ➕ Add New Question
            </h2>

            <form action="{{ route('questions.store', ['quiz' => $quiz]) }}" method="POST" class="space-y-5">
                @csrf

                <!-- Question -->
                <div>
                    <label for="question_text" class="block text-sm font-medium text-gray-700 mb-1">Question</label>
                    <input type="text" name="question_text" id="question_text"
                           value="{{ old('question_text') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm p-2.5" required>
                </div>

                <!-- Options -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                    <div class="grid grid-cols-1 gap-3">
                        <input type="text" name="option_a" placeholder="Option A"
                               value="{{ old('option_a') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm p-2.5" required>
                        <input type="text" name="option_b" placeholder="Option B"
                               value="{{ old('option_b') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm p-2.5" required>
                        <input type="text" name="option_c" placeholder="Option C"
                               value="{{ old('option_c') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm p-2.5" required>
                        <input type="text" name="option_d" placeholder="Option D"
                               value="{{ old('option_d') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm p-2.5" required>
                    </div>
                </div>

                <!-- Correct Option -->
                <div>
                    <label for="correct_option" class="block text-sm font-medium text-gray-700 mb-1">Correct Option</label>
                    <select name="correct_option" id="correct_option"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm p-2.5" required>
                        <option value="" disabled @selected(!old('correct_option'))>-- Select the correct option --</option>
                        <option value="A" @selected(old('correct_option') === 'A')>Option A</option>
                        <option value="B" @selected(old('correct_option') === 'B')>Option B</option>
                        <option value="C" @selected(old('correct_option') === 'C')>Option C</option>
                        <option value="D" @selected(old('correct_option') === 'D')>Option D</option>
                    </select>
                </div>

                <!-- Points -->
                <div>
                    <label for="points" class="block text-sm font-medium text-gray-700 mb-1">Points</label>
                    <input type="number" name="points" id="points" min="1"
                           value="{{ old('points', 1) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm p-2.5">
                </div>

                <!-- Submit -->
                <div class="pt-2">
                    <button type="submit"
                            @disabled($bankIsFull)
                            class="w-full md:w-auto px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                        {{ $bankIsFull ? 'Question Limit Reached' : 'Save Question' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- CSV Upload Card -->
       <!-- CSV Upload Card -->
        <div id="bulk-upload" class="bg-white p-8 rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
            <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                📂 Bulk Upload via CSV
            </h2>
            <p class="text-sm text-gray-600 mb-4">
                Upload a CSV file with questions, options, correct answer, and points.
                <span class="text-red-500 font-medium">Important: Use A, B, C, D for correct_option (not 1, 2, 3, 4)</span>
            </p>

            <!-- Success/Error Alert Area -->
            <div id="alert-container" class="mb-4"></div>

            <form action="{{ route('questions.import', $quiz->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="upload-form">
                @csrf

                <!-- Drag & Drop File Upload -->
                <div class="w-full">
                    <label for="file-upload" class="block text-sm font-medium text-gray-700 mb-2">Upload CSV/XLSX File</label>

                    <div id="drop-area"
                        class="relative flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition group">

                        <!-- Upload Icon -->
                        <svg id="upload-icon" class="w-12 h-12 text-gray-400 group-hover:text-blue-500 transition mb-2"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5.002 5.002 0 0115 7h1a4 4 0 110 8h-1v1a4 4 0 01-8 0v-1H7z"/>
                        </svg>

                        <!-- Success Checkmark (Hidden initially) -->
                        <svg id="success-icon" class="w-12 h-12 text-green-500 mb-2 hidden" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path class="checkmark" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>

                        <!-- Error Icon (Hidden initially) -->
                        <svg id="error-icon" class="w-12 h-12 text-red-500 mb-2 hidden" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>

                        <!-- Text -->
                        <p id="upload-text" class="text-sm text-gray-600">
                            <span class="font-semibold text-blue-600">Click to upload</span> or drag & drop
                        </p>
                        <p class="text-xs text-gray-500 mt-1">CSV, XLSX, XLS up to 5MB</p>

                        <!-- Progress Bar (Hidden initially) -->
                        <div id="progress-container" class="w-full h-4 mt-4 bg-gray-200 rounded-full overflow-hidden hidden">
                            <div id="progress-bar" class="progress-bar bg-blue-600 h-full rounded-full"></div>
                        </div>

                        <!-- File Info (Hidden initially) -->
                        <div id="file-info" class="file-info mt-4 text-center">
                            <p id="file-name" class="text-sm font-medium text-gray-700"></p>
                            <p id="file-size" class="text-xs text-gray-500 mt-1"></p>
                        </div>

                        <!-- Hidden Input -->
                        <input id="file-upload" type="file" name="file" accept=".csv,.xlsx,.xls"
                            class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>

                    <!-- Error Message (Hidden initially) -->
                    <div id="error-message" class="mt-2 text-sm text-red-600 hidden"></div>
                </div>

                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

                <div class="flex gap-3">
                    <button type="submit" id="submit-button"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg shadow font-medium transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                        ⬆ Upload CSV
                    </button>

                    <!-- Reset Button (Hidden initially) -->
                    <button type="button" id="reset-button"
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-6 py-2.5 rounded-lg shadow font-medium transition hidden">
                        Upload Another File
                    </button>
                </div>
            </form>

            <!-- Example format -->
            <div class="mt-6">
                <p class="font-semibold text-gray-700">CSV Format:</p>
                <pre class="bg-gray-100 p-4 rounded-lg mt-2 text-xs text-gray-800 overflow-x-auto">
        question_text,option_a,option_b,option_c,option_d,correct_option,points
        "What is 2+2?","3","4","5","6","B",1
        "What is the capital of France?","London","Berlin","Paris","Madrid","C",2
                </pre>
                <p class="text-xs text-gray-600 mt-2">
                    <span class="font-medium text-red-500">Note:</span>
                    <code>correct_option</code> must be "A", "B", "C", or "D" (not numbers 1, 2, 3, 4)
                </p>
            </div>
        </div>
    </div>
</div>
 <script>
document.addEventListener('DOMContentLoaded', function() {
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('file-upload');
    const uploadIcon = document.getElementById('upload-icon');
    const successIcon = document.getElementById('success-icon');
    const errorIcon = document.getElementById('error-icon');
    const uploadText = document.getElementById('upload-text');
    const progressContainer = document.getElementById('progress-container');
    const progressBar = document.getElementById('progress-bar');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const errorMessage = document.getElementById('error-message');
    const submitButton = document.getElementById('submit-button');
    const resetButton = document.getElementById('reset-button');
    const form = document.getElementById('upload-form');
    const alertContainer = document.getElementById('alert-container');

    // Variables to track upload state
    let isUploading = false;
    let currentFile = null;

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });

    // Handle dropped files
    dropArea.addEventListener('drop', handleDrop, false);

    // Handle click to select file
    dropArea.addEventListener('click', function() {
        if (!isUploading) {
            fileInput.click();
        }
    });

    // Handle file selection via click
    fileInput.addEventListener('change', function() {
        if (this.files.length) {
            handleFiles(this.files);
        }
    });

    // Handle reset button
    resetButton.addEventListener('click', resetForm);

    // Handle form submission with AJAX
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous alerts
        clearAlerts();

        // If no file is selected, show error
        if (!currentFile) {
            showError('Please select a file to upload.');
            return;
        }

        // Start upload process
        await uploadFile();
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight() {
        if (!isUploading) {
            dropArea.classList.add('border-blue-500', 'bg-blue-50');
        }
    }

    function unhighlight() {
        if (!isUploading) {
            dropArea.classList.remove('border-blue-500', 'bg-blue-50');
        }
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length && !isUploading) {
            handleFiles(files);
        }
    }

    function handleFiles(files) {
        const file = files[0];
        currentFile = file;

        // Clear any previous errors
        hideError();

        // Validate file type
        const validTypes = ['text/csv', 'application/vnd.ms-excel',
                           'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        if (!validTypes.includes(file.type) && !file.name.match(/\.(csv|xlsx|xls)$/)) {
            showError('Please upload a CSV, XLSX, or XLS file.');
            currentFile = null;
            return;
        }

        // Validate file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            showError('File size exceeds 5MB limit.');
            currentFile = null;
            return;
        }

        // Display file info
        displayFileInfo(file);
    }

    function displayFileInfo(file) {
        // Show file info
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);

        // Show file info with animation
        fileInfo.classList.remove('hidden');

        // Change border color
        dropArea.classList.remove('border-gray-300');
        dropArea.classList.add('border-blue-500');

        // Update text
        uploadText.innerHTML = 'File selected. Click "Upload CSV" to proceed.';

        // Hide upload icon, show success icon
        uploadIcon.classList.add('hidden');
        successIcon.classList.remove('hidden');
        errorIcon.classList.add('hidden');

        // Enable submit button
        submitButton.disabled = false;
        submitButton.classList.remove('hidden');
        resetButton.classList.add('hidden');
    }

    async function uploadFile() {
        isUploading = true;

        // Disable interactions
        submitButton.disabled = true;
        submitButton.innerHTML = 'Uploading...';
        dropArea.classList.add('opacity-50', 'cursor-not-allowed');

        // Show progress bar
        progressContainer.classList.remove('hidden');
        uploadText.innerHTML = 'Uploading file to server...';

        // Hide icons
        successIcon.classList.add('hidden');
        errorIcon.classList.add('hidden');

        // Simulate progress animation (for user feedback)
        let width = 0;
        const progressInterval = setInterval(() => {
            if (width < 90) {
                width += 10;
                progressBar.style.width = width + '%';
            }
        }, 500);

        try {
            // Create FormData
            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('quiz_id', document.querySelector('input[name="quiz_id"]').value);
            formData.append('file', currentFile);

            // Send AJAX request
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            clearInterval(progressInterval);
            progressBar.style.width = '100%';

            const result = await response.json();

            if (response.ok) {
                // Success
                showSuccess('Questions imported successfully! ' +
                           (result.count ? result.count + ' questions added.' : ''));

                // Update UI for success
                uploadText.innerHTML = '<span class="font-semibold text-green-600">Upload successful!</span>';
                dropArea.classList.remove('border-blue-500');
                dropArea.classList.add('border-green-500');
                successIcon.classList.remove('hidden');

                // Hide submit button, show reset button
                submitButton.classList.add('hidden');
                resetButton.classList.remove('hidden');

                // Reset form after 3 seconds
                setTimeout(() => {
                    resetForm();
                }, 5000);

            } else {
                // Server returned an error
                throw new Error(result.message || result.error || 'Upload failed');
            }

        } catch (error) {
            // Clear progress interval if still running
            clearInterval(progressInterval);

            // Show error state
            showError('Upload failed: ' + error.message);

            // Update UI for error
            uploadText.innerHTML = '<span class="font-semibold text-red-600">Upload failed!</span>';
            dropArea.classList.remove('border-blue-500');
            dropArea.classList.add('border-red-500');
            errorIcon.classList.remove('hidden');
            progressBar.style.width = '0%';

            // Show reset button
            submitButton.classList.add('hidden');
            resetButton.classList.remove('hidden');

        } finally {
            isUploading = false;
            submitButton.disabled = false;
            submitButton.innerHTML = '⬆ Upload CSV';
            dropArea.classList.remove('opacity-50', 'cursor-not-allowed');
            progressContainer.classList.add('hidden');
        }
    }

    function showError(message) {
        // Remove any existing alerts
        clearAlerts();

        // Create error alert
        const alertDiv = document.createElement('div');
        alertDiv.className = 'bg-red-50 border-l-4 border-red-500 p-4 mb-4';
        alertDiv.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">${message}</p>
                </div>
            </div>
        `;

        alertContainer.appendChild(alertDiv);

        // Auto-remove after 10 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 10000);
    }

    function showSuccess(message) {
        // Remove any existing alerts
        clearAlerts();

        // Create success alert
        const alertDiv = document.createElement('div');
        alertDiv.className = 'bg-green-50 border-l-4 border-green-500 p-4 mb-4';
        alertDiv.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">${message}</p>
                </div>
            </div>
        `;

        alertContainer.appendChild(alertDiv);
    }

    function clearAlerts() {
        alertContainer.innerHTML = '';
    }

    function resetForm() {
        // Reset all elements to initial state
        fileInput.value = '';
        currentFile = null;
        isUploading = false;

        successIcon.classList.add('hidden');
        errorIcon.classList.add('hidden');
        uploadIcon.classList.remove('hidden');
        progressContainer.classList.add('hidden');
        progressBar.style.width = '0%';
        fileInfo.classList.add('hidden');
        resetButton.classList.add('hidden');
        submitButton.classList.remove('hidden');
        submitButton.disabled = true;

        // Reset border color
        dropArea.classList.remove('border-blue-500', 'border-green-500', 'border-red-500',
                                 'opacity-50', 'cursor-not-allowed');
        dropArea.classList.add('border-gray-300');

        // Reset text
        uploadText.innerHTML = '<span class="font-semibold text-blue-600">Click to upload</span> or drag & drop';

        // Hide error message if shown
        hideError();

        // Clear alerts
        clearAlerts();
    }

    function hideError() {
        errorMessage.classList.add('hidden');
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
});
</script>

@endsection

