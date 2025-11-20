<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Topic</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        indigo: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        },
                        purple: {
                            600: '#9333ea',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-text {
            background: linear-gradient(to right, #4f46e5, #9333ea);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .form-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .btn-loading {
            position: relative;
            color: transparent !important;
        }
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Topic Management</h1>
                <p class="text-gray-600">Edit existing topic details</p>
            </div>
            <a href="#" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Topics
            </a>
        </div>

        <!-- Edit Form -->
        <div class="bg-white w-full max-w-4xl mx-auto rounded-2xl form-shadow overflow-hidden">
            <!-- Form Header -->
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-extrabold tracking-tight gradient-text">Edit Topic</h2>
                    <button class="text-gray-500 hover:text-gray-700 text-xl transition">
                        &times;
                    </button>
                </div>
            </div>

            <!-- Form Content -->
            <form method="POST" action="{{ route('admin.topics.update', $topic->id) }}" enctype="multipart/form-data" class="p-6 space-y-6" id="editTopicForm">
                @csrf
                @method('PUT')

                <input type="hidden" name="module_id" value="{{ $topic->module_id }}">

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Left Column: Topic Details -->
                    <div class="md:col-span-2 space-y-6">
                        <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6 space-y-5">
                            <!-- Title -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Topic Title *</label>
                                <input type="text" name="title" value="{{ old('title', $topic->title) }}" required
                                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <p class="text-red-600 text-sm mt-1 hidden">The title field is required.</p>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                                <textarea name="description" rows="4"
                                          class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">{{ old('description', $topic->description) }}</textarea>
                            </div>

                            <!-- Order -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Order</label>
                                <input type="number" name="order" min="0"
                                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                       value="{{ old('order', $topic->order) }}">
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                <select name="is_active"
                                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    <option value="1" {{ $topic->is_active ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$topic->is_active ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                    <button type="button" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium">
                        Cancel
                    </button>
                    <button type="submit" id="submitBtn" class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-medium flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>Update Topic
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editTopicForm');
            const submitBtn = document.getElementById('submitBtn');

            // Close button functionality
            document.querySelector('button.text-xl').addEventListener('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You have unsaved changes that will be lost.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, close it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '#'; // Replace with actual back URL
                    }
                });
            });

            // Cancel button functionality
            document.querySelector('button.bg-white').addEventListener('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You have unsaved changes that will be lost.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, cancel!',
                    cancelButtonText: 'Continue editing'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '#'; // Replace with actual back URL
                    }
                });
            });

            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Show loading state
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;

                // Simulate form submission (replace with actual AJAX/fetch call)
                setTimeout(() => {
                    // This is where you would make your actual API call
                    // For demonstration, we'll simulate both success and error scenarios
                    const isSuccess = Math.random() > 0.3; // 70% success rate for demo

                    if (isSuccess) {
                        // Success message
                        Swal.fire({
                            title: 'Success!',
                            text: 'Topic has been updated successfully.',
                            icon: 'success',
                            confirmButtonColor: '#4f46e5',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Redirect or do something else on success
                            window.location.href = '#'; // Replace with success URL
                        });
                    } else {
                        // Error message
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was a problem updating the topic. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#4f46e5',
                            confirmButtonText: 'Try Again'
                        });

                        // Remove loading state on error
                        submitBtn.classList.remove('btn-loading');
                        submitBtn.disabled = false;
                    }
                }, 1500); // Simulate network delay
            });

            // Validation example
            const titleInput = document.querySelector('input[name="title"]');
            const errorMessage = document.querySelector('.text-red-600');

            titleInput.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    errorMessage.classList.remove('hidden');
                    this.classList.add('border-red-500');
                } else {
                    errorMessage.classList.add('hidden');
                    this.classList.remove('border-red-500');
                }
            });
        });
    </script>
</body>
</html>
