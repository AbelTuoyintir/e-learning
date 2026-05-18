<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Login | LearnSmart</title>

   @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        /* Custom backdrop blur and smooth animations */
        .glass-card {
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        }
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.22);
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.3);
        }
        input:focus {
            transform: scale(1.01);
            transition: all 0.2s;
        }
        .hero-image {
            transition: transform 0.4s ease;
        }
        .hero-image:hover {
            transform: scale(1.01);
        }
        @keyframes fadeSlideUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-up {
            animation: fadeSlideUp 0.7s ease forwards;
        }
        .delay-100 {
            animation-delay: 0.1s;
        }
        .delay-200 {
            animation-delay: 0.2s;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-900 via-indigo-800 to-purple-900 antialiased">

    <div class="min-h-screen flex flex-col md:flex-row">

        <!-- LEFT SIDE: REAL STUDENT LEARNING PHOTO + INSPIRATIONAL CONTENT -->
        <div class="hidden md:flex md:w-1/2 items-center justify-center p-6 lg:p-10 relative overflow-hidden">
            <!-- subtle gradient overlay to make text pop -->
            <div class="absolute inset-0 bg-gradient-to-tr from-indigo-900/40 via-purple-800/30 to-blue-900/40 z-0"></div>
            
            <div class="relative z-10 text-center text-white max-w-xl animate-fade-up">
                <!-- REAL PHOTO: Students collaborating in a modern library / classroom (high-quality unsplash student image) 
                     This is a genuine photo of university students learning together — authentic and engaging. -->
                <div class="mb-6 rounded-2xl shadow-2xl overflow-hidden border-2 border-white/30 hero-image">
                    <img
                        src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80"
                        alt="Real students learning together in a bright classroom"
                        class="w-full h-[380px] lg:h-[420px] object-cover object-center"
                        loading="eager"
                    >
                </div>
                
                <div class="space-y-3">
                    <h2 class="text-4xl lg:text-5xl font-extrabold tracking-tight drop-shadow-lg">Shape Your Future</h2>
                    <div class="h-1 w-24 bg-yellow-400 rounded-full mx-auto my-3"></div>
                    <p class="text-indigo-50 text-lg lg:text-xl font-medium max-w-md mx-auto">
                        Join thousands of active learners. Access quizzes, track progress, and master new skills daily.
                    </p>
                    <div class="flex justify-center gap-4 mt-6 text-sm font-semibold">
                        <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full"><i class="fas fa-chalkboard-user mr-2"></i> 120+ Quizzes</span>
                        <span class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full"><i class="fas fa-trophy mr-2"></i> Real-time results</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: LOGIN FORM (glassmorphism enhanced) -->
        <div class="md:w-1/2 flex items-center justify-center p-6 md:p-8 lg:p-12">
            <div class="w-full max-w-md glass-card rounded-3xl p-8 lg:p-10 shadow-2xl animate-fade-up delay-100">
                
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full mx-auto flex items-center justify-center shadow-lg ring-4 ring-white/30">
                        <i class="fas fa-user-graduate text-3xl text-white"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-white mt-5 tracking-tight">Student Portal</h1>
                    <p class="text-blue-100 text-sm mt-1">Sign in to continue your learning journey</p>
                </div>

                @if(session('status'))
                    <div class="bg-green-100/90 backdrop-blur-sm border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-xl mb-5 flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100/90 backdrop-blur-sm border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-xl mb-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <p class="flex items-center gap-2"><i class="fas fa-exclamation-triangle text-red-500"></i> {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- LOGIN FORM -->
                <form method="POST" action="{{ route('student.login.submit') }}" class="space-y-5">
                    @csrf

                    <!-- EMAIL FIELD -->
                    <div>
                        <label class="text-white font-semibold block mb-1.5 text-sm"><i class="fas fa-envelope mr-2"></i> Student Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full px-5 py-3 rounded-xl bg-white/90 text-gray-800 placeholder-gray-500 focus:ring-4 focus:ring-indigo-300 outline-none transition-all border border-transparent focus:border-indigo-400"
                            placeholder="student@example.com"
                            required
                            autofocus
                        >
                    </div>

                    <!-- PASSWORD FIELD WITH TOGGLE -->
                    <div>
                        <label class="text-white font-semibold block mb-1.5 text-sm"><i class="fas fa-lock mr-2"></i> Password</label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="w-full px-5 py-3 pr-12 rounded-xl bg-white/90 text-gray-800 placeholder-gray-500 focus:ring-4 focus:ring-indigo-300 outline-none transition-all"
                                placeholder="Enter your password"
                                required
                            >
                            <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-indigo-700 transition">
                                <i id="toggleIcon" class="fas fa-eye text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- REMEMBER ME & FORGOT PASSWORD ROW -->
                    <div class="flex justify-between items-center text-sm">
                        <label class="flex items-center gap-2 text-white/90 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-white/30 bg-white/20 text-indigo-600 focus:ring-indigo-400">
                            <span>Remember me</span>
                        </label>
                        <a href="{{ route('student.forgot.password') }}" class="text-indigo-100 hover:text-white font-medium transition underline-offset-2 hover:underline">Forgot password?</a>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <button
                        type="submit"
                        class="w-full bg-white text-indigo-800 mt-4 py-3.5 rounded-xl font-bold text-lg shadow-lg hover:bg-indigo-50 hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2 group"
                    >
                        Login As 
                    </button>
                </form>

                <!-- DIVIDER -->
                <div class="relative my-7">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/20"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="bg-transparent px-3 text-white/70 backdrop-blur-sm">secure access</span>
                    </div>
                </div>

                <!-- ADMIN ACCESS LINK WITH ICON -->
                <p class="text-center text-white/90 text-sm">
                    <i class="fas fa-shield-alt mr-1"></i> Admin?
                    <a href="{{ route('admin.login') }}" class="font-bold text-white hover:text-indigo-200 transition ml-1 underline decoration-1">Login to Admin Hub</a>
                </p>

                <!-- ADDITIONAL HELPER TEXT: MODERN TOUCH -->
                <p class="text-center text-white/60 text-xs mt-6">
                    <i class="far fa-clock mr-1"></i> 24/7 access · Learn at your own pace
                </p>
            </div>
        </div>
    </div>

    <!-- MOBILE BOTTOM BANNER (for small devices: show a compact real image) -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-indigo-900/90 backdrop-blur-md p-2 text-center text-white text-xs z-20 flex justify-center gap-4">
        <span><i class="fas fa-users"></i> 2,400+ students</span>
        <span><i class="fas fa-question-circle"></i> 120+ quizzes</span>
    </div>

    <script>
        // Toggle password visibility with proper icon swap
        function togglePassword() {
            const field = document.getElementById("password");
            const icon = document.getElementById("toggleIcon");

            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                field.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        // Add smooth interaction for form elements and dynamic year (optional)
        document.addEventListener('DOMContentLoaded', function() {
            // If there are any flashing server errors, we can optionally autofocus or log
            const errorBox = document.querySelector('.bg-red-100');
            if (errorBox) {
                setTimeout(() => {
                    errorBox.style.opacity = '1';
                }, 100);
            }
            console.log("Student login page with authentic learning imagery ready");
        });
    </script>
    <!-- SweetAlert2 CDN not fully utilized but kept for any future extension -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>