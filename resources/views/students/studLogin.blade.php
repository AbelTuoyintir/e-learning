<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <!-- Main Container -->
    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-8 text-center">
                <div class="w-20 h-20 bg-white rounded-full mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-3xl text-blue-600"></i>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Student Portal</h1>
                <p class="text-blue-100">Sign in to your account</p>
            </div>

            <!-- Login Form -->
            <form class="p-8 space-y-6" onsubmit="handleLogin(event)">
                <!-- Student ID Input -->
                <div class="space-y-2">
                    <label for="studentId" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-id-card mr-2 text-blue-600"></i>Student email
                    </label>
                    <input 
                        type="text" 
                        id="email" 
                        name="email" 
                        required
                        placeholder="Enter your student ID"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                    >
                </div>

                <!-- Password Input -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            placeholder="Enter your password"
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword()"
                            class="absolute right-3 top-3 text-gray-500 hover:text-gray-700 focus:outline-none"
                        >
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 transition duration-200">
                        Forgot password?
                    </a>
                </div>

                <!-- Login Button -->
                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transform hover:scale-[1.02] transition duration-200 shadow-lg"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </button>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">or</span>
                    </div>
                </div>

                <!-- Alternative Login Options -->
                <div class="grid grid-cols-2 gap-4">
                    <button 
                        type="button"
                        class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200"
                    >
                        <i class="fab fa-google text-red-500 mr-2"></i>
                        Google
                    </button>
                    <button 
                        type="button"
                        class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200"
                    >
                        <i class="fab fa-microsoft text-blue-500 mr-2"></i>
                        Microsoft
                    </button>
                </div>

                <!-- Sign Up Link -->
                {{-- <p class="text-center text-sm text-gray-600">
                    Don't have an account? 
                    <a href="#" class="text-blue-600 hover:text-blue-800 font-medium transition duration-200">
                        Sign up here
                    </a>
                </p> --}}
            </form>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500">
                <i class="fas fa-shield-alt mr-1"></i>
                Your connection is secure and encrypted
            </p>
        </div>
    </div>

    <!-- Error Toast (Hidden by default) -->
    <div id="errorToast" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg hidden transform transition-all duration-300">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span id="errorMessage">Invalid credentials</span>
        </div>
    </div>

    <!-- Success Toast (Hidden by default) -->
    <div id="successToast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg hidden transform transition-all duration-300">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>Login successful! Redirecting...</span>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

       async function handleLogin(event) {
            event.preventDefault();

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            // Simple validation
            if (email.length < 5 || !email.includes('@')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Email',
                    text: 'Please enter a valid email address.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            if (password.length < 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Weak Password',
                    text: 'Password must be at least 6 characters.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            // Get the button and show loading state
            const loginButton = event.target.querySelector('button[type="submit"]');
            const originalText = loginButton.innerHTML;
            loginButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Signing In...';
            loginButton.disabled = true;

            try {
                // Send POST request to backend
                const response = await fetch('/student/login/submit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password
                    })
                });

                if (response.ok) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Login Successful!',
                        text: 'Redirecting to your dashboard...',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    window.location.href = '/students/dashboard';
                } else {
                    // Parse error response
                    const errorData = await response.json().catch(() => ({}));
                    const status = response.status;
                    let errorMsg = 'Invalid email or password';

                    if (status === 404) {
                        errorMsg = 'You don’t have an account. Please sign up first.';
                    } else if (status === 401) {
                        errorMsg = 'Incorrect email or password.';
                    } else if (errorData.message) {
                        errorMsg = errorData.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: errorMsg,
                        confirmButtonColor: '#d33'
                    });
                }
            } catch (error) {
                console.error('Login error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'Please check your internet connection and try again.',
                    confirmButtonColor: '#d33'
                });
            }

            // Reset button
            loginButton.innerHTML = originalText;
            loginButton.disabled = false;
        }

    </script>
</body>
</html>