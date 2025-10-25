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
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm p-2.5" required>
                </div>

                <!-- Options -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                    <div class="grid grid-cols-1 gap-3">
                        <input type="text" name="option_a" placeholder="Option A"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm p-2.5" required>
                        <input type="text" name="option_b" placeholder="Option B"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm p-2.5" required>
                        <input type="text" name="option_c" placeholder="Option C"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm p-2.5" required>
                        <input type="text" name="option_d" placeholder="Option D"
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm p-2.5" required>
                    </div>
                </div>

                <!-- Correct Option -->
                <div>
                    <label for="correct_option" class="block text-sm font-medium text-gray-700 mb-1">Correct Option</label>
                    <select name="correct_option" id="correct_option"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm p-2.5" required>
                        <option value="" disabled selected>-- Select the correct option --</option>
                        <option value="option_a">Option A</option>
                        <option value="option_b">Option B</option>
                        <option value="option_c">Option C</option>
                        <option value="option_d">Option D</option>
                    </select>
                </div>

                <!-- Points -->
                <div>
                    <label for="points" class="block text-sm font-medium text-gray-700 mb-1">Points</label>
                    <input type="number" name="points" id="points" min="1"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm p-2.5">
                </div>

                <!-- Submit -->
                <div class="pt-2">
                    <button type="submit"
                            class="w-full md:w-auto px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                        💾 Save Question
                    </button>
                </div>
            </form>
        </div>

        <!-- CSV Upload Card -->
        <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
            <h2 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                📂 Bulk Upload via CSV
            </h2>
            <p class="text-sm text-gray-600 mb-4">
                Upload a CSV file with questions, options, correct answer, and points.
            </p>
<form action="{{ route('questions.import', $quiz->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="upload-form">
            @csrf
            <!-- Drag & Drop File Upload -->
            <div class="w-full">
                <label for="file-upload" class="block text-sm font-medium text-gray-700 mb-2">Upload CSV/XLSX File</label>

                <div id="drop-area"
                    class="relative flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition group">

                    <!-- Upload Icon -->
                    <svg id="upload-icon" class="w-12 h-12 text-gray-400 group-hover:text-blue-500 transition mb-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5.002 5.002 0 0115 7h1a4 4 0 110 8h-1v1a4 4 0 01-8 0v-1H7z"/>
                    </svg>

                    <!-- Success Checkmark (Hidden initially) -->
                    <svg id="success-icon" class="w-12 h-12 text-green-500 mb-2 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path class="checkmark" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
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
                        class="absolute inset-0 opacity-0 cursor-pointer" required>
                </div>

                <!-- Error Message (Hidden initially) -->
                <div id="error-message" class="mt-2 text-sm text-red-600 hidden"></div>
            </div>

            <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

            <button type="submit" id="submit-button"
                    class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg shadow font-medium transition">
                ⬆ Upload CSV
            </button>

            <!-- Reset Button (Hidden initially) -->
            <button type="submit" id="reset-button" class="w-full md:w-auto bg-gray-600 hover:bg-gray-700 text-white px-6 py-2.5 rounded-lg shadow font-medium transition hidden">
                Upload Another File
            </button>
        </form>


            <!-- Example format -->
            <div class="mt-6">
                <p class="font-semibold text-gray-700">CSV Format:</p>
                <pre class="bg-gray-100 p-4 rounded-lg mt-2 text-xs text-gray-800 overflow-x-auto">
                    question_text,option1,option2,option3,option4,correct_option,points
                    "What is 2+2?", "3", "4", "5", "6", 2, 1
                    "Capital of France?", "London", "Berlin", "Paris", "Madrid", 3, 2
                </pre>
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
                fileInput.click();
            });

            // Handle file selection via click
            fileInput.addEventListener('change', function() {
                if (this.files.length) {
                    handleFiles(this.files);
                }
            });

            // Handle reset button
            resetButton.addEventListener('click', resetForm);

            // Handle form submission
            form.addEventListener('submit', function(e) {
                // If no file is selected, prevent form submission
                if (!fileInput.files.length) {
                    e.preventDefault();
                    showError('Please select a file to upload.');
                }
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight() {
                dropArea.classList.add('border-blue-500', 'bg-blue-50');
            }

            function unhighlight() {
                dropArea.classList.remove('border-blue-500', 'bg-blue-50');
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (files.length) {
                    handleFiles(files);
                }
            }

            function handleFiles(files) {
                const file = files[0];

                // Clear any previous errors
                hideError();

                // Validate file type
                const validTypes = ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
                if (!validTypes.includes(file.type) && !file.name.match(/\.(csv|xlsx|xls)$/)) {
                    showError('Please upload a CSV, XLSX, or XLS file.');
                    return;
                }

                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    showError('File size exceeds 5MB limit.');
                    return;
                }

                // Start upload animation
                startUploadAnimation(file);
            }

            function startUploadAnimation(file) {
                // Show file info
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);

                // Pulse animation
                dropArea.classList.add('pulse-animation');

                // Hide upload icon, show progress
                uploadIcon.classList.add('hidden');
                progressContainer.classList.remove('hidden');

                // Change border color
                dropArea.classList.remove('border-gray-300');
                dropArea.classList.add('border-blue-500');

                // Update text
                uploadText.innerHTML = 'File selected. Click "Upload CSV" to proceed.';

                // Show file info with animation
                setTimeout(() => {
                    fileInfo.classList.add('show-info');
                }, 300);

                // Remove pulse animation after it completes
                setTimeout(() => {
                    dropArea.classList.remove('pulse-animation');
                }, 500);

                // Enable submit button
                submitButton.disabled = false;
            }

            function showError(message) {
                errorMessage.textContent = message;
                errorMessage.classList.remove('hidden');

                // Shake animation for error
                dropArea.classList.add('pulse-animation');
                setTimeout(() => {
                    dropArea.classList.remove('pulse-animation');
                }, 500);
            }

            function hideError() {
                errorMessage.classList.add('hidden');
            }

            function resetForm() {
                // Reset all elements to initial state
                fileInput.value = '';
                successIcon.classList.add('hidden');
                uploadIcon.classList.remove('hidden');
                progressContainer.classList.add('hidden');
                progressBar.style.width = '0%';
                fileInfo.classList.remove('show-info');
                resetButton.classList.add('hidden');
                submitButton.classList.remove('hidden');

                // Reset border color
                dropArea.classList.remove('border-blue-500', 'border-green-500');
                dropArea.classList.add('border-gray-300');

                // Reset text
                uploadText.innerHTML = '<span class="font-semibold text-blue-600">Click to upload</span> or drag & drop';

                // Hide error message if shown
                hideError();

                // Enable submit button
                submitButton.disabled = false;
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Simulate form submission success (for demonstration)
            // In a real application, you would remove this and handle actual form submission
            document.getElementById('upload-form').addEventListener('submit', function(e) {
                e.preventDefault();

                // Simulate upload process
                submitButton.disabled = true;
                uploadText.innerHTML = 'Uploading file...';

                // Simulate progress
                let width = 0;
                const interval = setInterval(() => {
                    if (width >= 100) {
                        clearInterval(interval);
                        simulateUploadSuccess();
                    } else {
                        width += 5;
                        progressBar.style.width = width + '%';
                    }
                }, 100);
            });

            function simulateUploadSuccess() {
                // Complete the progress bar
                progressBar.style.width = '100%';

                // Show success state after a short delay
                setTimeout(() => {
                    progressContainer.classList.add('hidden');
                    successIcon.classList.remove('hidden');

                    // Change border color to green
                    dropArea.classList.remove('border-blue-500');
                    dropArea.classList.add('border-green-500');

                    // Update text
                    uploadText.innerHTML = '<span class="font-semibold text-green-600">Upload successful!</span>';

                    // Hide submit button, show reset button
                    submitButton.classList.add('hidden');
                    resetButton.classList.remove('hidden');
                }, 500);
            }
        });
    </script>

@endsection
