@extends('layouts.app')
@section('content')

<!-- Modern Registration Form -->
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-6">
    <div class="w-full max-w-2xl">

        <!-- Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-lg mb-4">
                <i class="fas fa-user-graduate text-3xl text-indigo-600"></i>
            </div>
            <h1 class="text-4xl font-extrabold text-gray-800 tracking-tight">Create Your Account</h1>
            <p class="mt-2 text-gray-500">Join thousands of students already learning with us.</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white/80 backdrop-blur rounded-2xl shadow-xl border border-white/20 p-8">

            <form id="studentForm" action="{{ route('students.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Progress Indicator -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-2">
                        <span class="w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</span>
                        <span class="text-sm text-gray-600">Personal Info</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 rounded-full mx-4"></div>
                    <div class="flex items-center space-x-2">
                        <span class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold">2</span>
                        <span class="text-sm text-gray-400">Security</span>
                    </div>
                </div>

                <!-- Name Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="firstname">First Name</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" name="firstname" id="firstname" placeholder="John"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('firstname') @enderror"
                                   value="{{ old('firstname') }}" required>
                        </div>
                        @error('firstname')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="middlename">Middle Name</label>
                        <input type="text" name="middlename" id="middlename" placeholder="Optional"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent @error('middlename') @enderror transition"
                               value="{{ old('middlename') }}">
                        @error('middlename')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="lastname">Last Name</label>
                        <input type="text" name="lastname" id="lastname" placeholder="Doe"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent @error('lastname') @enderror transition"
                               value="{{ old('lastname') }}" required>
                        @error('lastname')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contact Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="email">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email" id="email" placeholder="you@example.com"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent @error('email') @enderror transition"
                                   value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="phone">Phone Number</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="tel" name="phone" id="phone" placeholder="+1 234 567 890"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent @error('phone') @enderror transition"
                                   value="{{ old('phone') }}">
                        </div>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Program -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="Program">Program</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-graduation-cap"></i>
                        </span>
                        <input type="text" name="Program" id="Program" placeholder="e.g. Computer Science"
                               class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent @error('Program') @enderror transition"
                               value="{{ old('Program') }}">
                    </div>
                    @error('Program')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Terms -->
                <div class="flex items-start">
                    <input type="checkbox" id="terms" class="mt-1 w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 @error('terms') @enderror" required>
                    <label for="terms" class="ml-3 text-sm text-gray-600">
                        I agree to the <a href="#" class="text-indigo-600 hover:underline">Terms & Conditions</a>
                        and <a href="#" class="text-indigo-600 hover:underline">Privacy Policy</a>.
                    </label>
                </div>
                @error('terms')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Submit -->
                <button type="submit" id="submitBtn"
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200">
                    <span id="btnText">Create Account</span>
                    <i id="btnIcon" class="fas fa-arrow-right ml-2"></i>
                </button>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Success Message
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Registration Successful!',
                html: `{!! session('success') !!}
                @if(session('student_data'))
                    <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-200 text-left">
                        <h4 class="font-semibold text-green-800 mb-2">Student Details:</h4>
                        <p><strong>Name:</strong> {{ session('student_data.name') }}</p>
                        <p><strong>Email:</strong> {{ session('student_data.email') }}</p>
                        <p class="text-red-600 font-mono"><strong>Temporary Password:</strong> {{ session('student_data.password') }}</p>
                        <p class="text-xs text-gray-600 mt-2">Please save this password securely.</p>
                    </div>
                @endif
                `,
                confirmButtonText: 'Continue',
                confirmButtonColor: '#4f46e5',
                background: '#f0f9ff',
                iconColor: '#10b981',
                width: '600px'
            });
        @endif

        // Warning Message (Student created but SMS failed)
        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Registration Complete with Note',
                html: `{!! session('warning') !!}
                @if(session('student_data'))
                    <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200 text-left">
                        <h4 class="font-semibold text-yellow-800 mb-2">Student Details:</h4>
                        <p><strong>Name:</strong> {{ session('student_data.name') }}</p>
                        <p><strong>Email:</strong> {{ session('student_data.email') }}</p>
                        <p class="text-red-600 font-mono"><strong>Temporary Password:</strong> {{ session('student_data.password') }}</p>
                        <p class="text-xs text-yellow-600 mt-2">⚠️ Please manually provide this password to the student.</p>
                    </div>
                @endif
                `,
                confirmButtonText: 'Understood',
                confirmButtonColor: '#f59e0b',
                background: '#fffbeb',
                iconColor: '#f59e0b',
                width: '600px'
            });
        @endif

        // Error Message
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: '{{ session('error') }}',
                confirmButtonText: 'Try Again',
                confirmButtonColor: '#dc2626',
                background: '#fef2f2',
                iconColor: '#ef4444'
            });
        @endif

        // Validation Errors
        @if($errors->any())
            @if(!session('error') && !session('success') && !session('warning'))
            const errorMessages = `
                <div class="text-left text-sm text-red-600 mt-2">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            `;

            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: 'Please fix the following errors:' + errorMessages,
                confirmButtonText: 'Fix Errors',
                confirmButtonColor: '#dc2626',
                background: '#fef2f2',
                iconColor: '#ef4444',
                width: '600px'
            });
            @endif
        @endif
    });

    // Enhanced form submission with confirmation
    document.getElementById('studentForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnIcon = document.getElementById('btnIcon');

        // Disable button and show loading state
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

        // Change button content to loading spinner
        btnText.innerHTML = 'Creating Account...';
        btnIcon.classList.remove('fa-arrow-right');
        btnIcon.classList.add('fa-spinner', 'fa-spin');
    });

    // Optional: Add confirmation dialog before submission
    function confirmSubmission() {
        return new Promise((resolve) => {
            Swal.fire({
                title: 'Confirm Registration',
                html: `Are you sure you want to register this student?<br>
                      <small class="text-gray-500">A temporary password will be generated and sent via SMS.</small>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Register Student',
                cancelButtonText: 'Review Details',
                background: '#f8fafc',
                width: '500px'
            }).then((result) => {
                resolve(result.isConfirmed);
            });
        });
    }

    // Uncomment below if you want confirmation dialog
    /*
    document.getElementById('studentForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const isConfirmed = await confirmSubmission();

        if (isConfirmed) {
            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnIcon = document.getElementById('btnIcon');

            submitBtn.disabled = true;
            btnText.innerHTML = 'Creating Account...';
            btnIcon.classList.replace('fa-arrow-right', 'fa-spinner', 'fa-spin');

            // Submit the form
            this.submit();
        }
    });
    */
</script>

@endsection
