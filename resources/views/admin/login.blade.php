<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In - Quiz & LMS System</title>

    <!-- Tailwind CSS & Assets -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome & Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-heading {
            font-family: 'Space Grotesk', 'Plus Jakarta Sans', sans-serif;
        }

        .ambient-glow {
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.2) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.2) 0px, transparent 50%);
        }
    </style>
</head>
<body class="h-full antialiased selection:bg-indigo-500 selection:text-white ambient-glow flex items-center justify-center p-4 sm:p-6 lg:p-8">

    <!-- Container Card -->
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100 grid grid-cols-1 lg:grid-cols-12 min-h-[620px]">

        <!-- Left Visual Feature Section -->
        <div class="lg:col-span-6 relative bg-gradient-to-br from-slate-900 via-indigo-950 to-purple-950 p-8 sm:p-12 text-white flex flex-col justify-between overflow-hidden">
            <!-- Glow background overlay -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Top Brand -->
            <div class="relative z-10 flex items-center space-x-3">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-shield-halved text-white text-xl"></i>
                </div>
                <div>
                    <span class="text-xl font-extrabold tracking-tight text-white">LMS Portal</span>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-indigo-300">Admin Workspace</span>
                </div>
            </div>

            <!-- Center Content Feature -->
            <div class="relative z-10 my-8 space-y-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-indigo-200 text-xs font-semibold">
                    <i class="fas fa-lock text-emerald-400"></i>
                    Secured Administrative Access
                </div>

                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight text-white font-heading">
                    Empowering Next-Gen Learning Management.
                </h2>
                <p class="text-indigo-200/90 text-sm leading-relaxed">
                    Streamline course creation, monitor student progression in real time, and deliver AI-assisted tutoring experiences from one centralized dashboard.
                </p>

                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-white/10 text-center">
                    <div class="p-3 rounded-2xl bg-white/5 backdrop-blur-md">
                        <p class="text-lg font-extrabold text-white">100%</p>
                        <p class="text-[10px] text-indigo-300 font-medium">Uptime</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/5 backdrop-blur-md">
                        <p class="text-lg font-extrabold text-white">AI</p>
                        <p class="text-[10px] text-indigo-300 font-medium">Enabled</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/5 backdrop-blur-md">
                        <p class="text-lg font-extrabold text-white">2.4</p>
                        <p class="text-[10px] text-indigo-300 font-medium">Engine v2.4</p>
                    </div>
                </div>
            </div>

            <!-- Footer Quote -->
            <div class="relative z-10 text-xs text-indigo-300/80">
                &copy; {{ date('Y') }} LMS Platform. All administrative actions are audited.
            </div>
        </div>

        <!-- Right Login Form Section -->
        <div class="lg:col-span-6 p-8 sm:p-12 flex flex-col justify-center bg-white">

            <div class="max-w-md mx-auto w-full space-y-6">

                <!-- Heading -->
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight font-heading">Admin Sign In</h3>
                    <p class="text-xs text-slate-500 mt-1">Please enter your authorized administrative credentials to continue.</p>
                </div>

                <!-- Session Error Alert script if any -->
                @if($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Authentication Failed',
                            html: `{!! implode('<br>', $errors->all()) !!}`,
                            confirmButtonColor: '#4f46e5',
                            customClass: {
                                popup: 'rounded-2xl'
                            }
                        });
                    });
                </script>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600">
                            Email Address <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-envelope text-sm"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium"
                                   placeholder="admin@example.com">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600">
                                Password <span class="text-rose-500">*</span>
                            </label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-lock text-sm"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                   class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-slate-800 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 transition">
                            <span class="ml-2 text-xs font-medium text-slate-600">Remember session</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold text-sm shadow-lg shadow-indigo-600/25 hover:shadow-indigo-600/40 hover:scale-[1.01] active:scale-95 transition-all duration-200">
                        Sign In to Dashboard
                    </button>

                </form>

                <!-- Footer Student Switch -->
                <div class="pt-4 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-500">
                        Not an administrator?
                        <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition">
                            Switch to Student Login
                        </a>
                    </p>
                </div>

            </div>

        </div>

    </div>

</body>
</html>
