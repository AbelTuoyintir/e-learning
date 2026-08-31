<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Workspace - Sign In</title>

    <!-- Tailwind CSS & Assets -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .heading-font {
            font-family: 'Space Grotesk', sans-serif;
        }

        .ambient-glow {
            background-color: #030712;
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.25) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.25) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(16, 185, 129, 0.1) 0px, transparent 50%);
        }
    </style>
</head>
<body class="h-full antialiased selection:bg-indigo-500 selection:text-white ambient-glow flex items-center justify-center p-4 sm:p-6 lg:p-8">

    <!-- Container Card -->
    <div class="w-full max-w-5xl bg-slate-900/80 backdrop-blur-2xl rounded-3xl shadow-2xl overflow-hidden border border-white/10 grid grid-cols-1 lg:grid-cols-12 min-h-[620px]">

        <!-- Left Visual Feature Section -->
        <div class="lg:col-span-6 relative bg-gradient-to-br from-slate-950 via-indigo-950 to-purple-950 p-8 sm:p-12 text-white flex flex-col justify-between overflow-hidden border-b lg:border-b-0 lg:border-r border-white/10">
            <!-- Glow background overlay -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Top Brand -->
            <div class="relative z-10 flex items-center space-x-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 via-indigo-600 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 border border-white/20">
                    <i class="fas fa-shield-cat text-white text-2xl"></i>
                </div>
                <div>
                    <span class="text-xl font-extrabold tracking-tight text-white heading-font">LMS Admin Pro</span>
                    <span class="block text-[10px] font-bold uppercase tracking-widest text-indigo-300">Management Suite v2.4</span>
                </div>
            </div>

            <!-- Center Content Feature -->
            <div class="relative z-10 my-8 space-y-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 backdrop-blur-md border border-indigo-500/30 text-indigo-200 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Secured Access Control
                </div>

                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight text-white heading-font">
                    Empowering Next-Gen Learning Operations.
                </h2>
                <p class="text-indigo-200/90 text-xs sm:text-sm leading-relaxed">
                    Streamline course creation, monitor real-time student progression, evaluate assessment analytics, and manage AI tutoring from one central dashboard.
                </p>

                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-white/10 text-center">
                    <div class="p-3 rounded-2xl bg-white/5 backdrop-blur-md border border-white/5">
                        <p class="text-lg font-extrabold text-white heading-font">100%</p>
                        <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-wider">Uptime</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/5 backdrop-blur-md border border-white/5">
                        <p class="text-lg font-extrabold text-white heading-font">AI</p>
                        <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-wider">Tutor Suite</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/5 backdrop-blur-md border border-white/5">
                        <p class="text-lg font-extrabold text-white heading-font">2.4.0</p>
                        <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-wider">Engine</p>
                    </div>
                </div>
            </div>

            <!-- Footer Quote -->
            <div class="relative z-10 text-[11px] text-slate-400">
                &copy; {{ date('Y') }} LMS Platform. All administrative sessions are encrypted and audited.
            </div>
        </div>

        <!-- Right Login Form Section -->
        <div class="lg:col-span-6 p-8 sm:p-12 flex flex-col justify-center bg-slate-900/60">

            <div class="max-w-md mx-auto w-full space-y-6">

                <!-- Heading -->
                <div>
                    <h3 class="text-2xl font-extrabold text-white tracking-tight heading-font">Admin Sign In</h3>
                    <p class="text-xs text-slate-400 mt-1">Please enter your authorized administrator credentials to proceed.</p>
                </div>

                <!-- Session Error Alert script if any -->
                @if($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Authentication Failed',
                            html: `{!! implode('<br>', $errors->all()) !!}`,
                            confirmButtonColor: '#6366f1',
                            background: '#0f172a',
                            color: '#fff',
                            customClass: {
                                popup: 'rounded-3xl border border-white/10'
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
                        <label for="email" class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-300">
                            Email Address <span class="text-rose-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-envelope text-xs"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full pl-10 pr-4 py-3 bg-slate-950 border border-white/10 rounded-2xl text-white text-xs focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium placeholder-slate-500"
                                   placeholder="admin@example.com">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-[10px] font-extrabold uppercase tracking-widest text-slate-300">
                                Password <span class="text-rose-400">*</span>
                            </label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-lock text-xs"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                   class="w-full pl-10 pr-4 py-3 bg-slate-950 border border-white/10 rounded-2xl text-white text-xs focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium placeholder-slate-500"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-950 border-white/10 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-900 transition">
                            <span class="ml-2 text-xs font-semibold text-slate-400">Remember session</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-bold text-xs shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/40 hover:scale-[1.01] active:scale-95 transition-all duration-200 heading-font">
                        Sign In to Dashboard
                    </button>

                </form>

                <!-- Footer Student Switch -->
                <div class="pt-4 border-t border-white/10 text-center">
                    <p class="text-xs text-slate-400">
                        Not an administrator?
                        <a href="{{ route('login') }}" class="font-bold text-indigo-400 hover:text-indigo-300 transition">
                            Switch to Student Portal
                        </a>
                    </p>
                </div>

            </div>

        </div>

    </div>

</body>
</html>
