<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Workspace - Sign In</title>

    <!-- Tailwind CSS & Assets -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome & Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-heading {
            font-family: 'Space Grotesk', 'Plus Jakarta Sans', sans-serif;
        }

        .ambient-glow {
            background-color: #0b0f19;
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.18) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.18) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(14, 165, 233, 0.1) 0px, transparent 50%);
        }
    </style>
</head>
<body class="h-full antialiased selection:bg-indigo-500 selection:text-white ambient-glow flex items-center justify-center p-4 sm:p-6 lg:p-8">

    <!-- Container Card -->
    <div class="w-full max-w-5xl bg-slate-900/90 rounded-3xl shadow-2xl overflow-hidden border border-white/10 grid grid-cols-1 lg:grid-cols-12 min-h-[640px] backdrop-blur-2xl">

        <!-- Left Visual Showcase -->
        <div class="lg:col-span-6 relative bg-gradient-to-br from-slate-950 via-indigo-950 to-purple-950 p-8 sm:p-12 text-white flex flex-col justify-between overflow-hidden border-r border-white/10">
            <!-- Glow background overlay -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-500/25 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-purple-500/25 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Top Brand -->
            <div class="relative z-10 flex items-center space-x-3.5">
                <div class="relative flex items-center justify-center">
                    <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl blur-sm opacity-80"></div>
                    <div class="relative w-11 h-11 rounded-2xl bg-slate-950 border border-white/20 flex items-center justify-center text-indigo-400">
                        <i class="fas fa-shield-halved text-xl"></i>
                    </div>
                </div>
                <div>
                    <span class="font-heading text-xl font-extrabold tracking-tight text-white">LMS Platform</span>
                    <span class="block text-[10px] font-extrabold uppercase tracking-widest text-indigo-300">Admin Workspace</span>
                </div>
            </div>

            <!-- Center Content Feature -->
            <div class="relative z-10 my-8 space-y-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-indigo-200 text-xs font-semibold">
                    <i class="fas fa-lock text-emerald-400"></i>
                    Secured Administrative Control
                </div>

                <h2 class="font-heading text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight text-white">
                    Next-Gen Academic Engine & Control Hub.
                </h2>
                <p class="text-indigo-200/90 text-xs sm:text-sm leading-relaxed font-normal">
                    Manage course catalogs, configure dynamic quiz attempt policies, track student completion metrics, and power AI tutoring assistance from a single centralized dashboard.
                </p>

                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-white/10 text-center">
                    <div class="p-3 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10">
                        <p class="font-heading text-xl font-black text-white">100%</p>
                        <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-wider mt-0.5">Uptime</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10">
                        <p class="font-heading text-xl font-black text-emerald-400">AI</p>
                        <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-wider mt-0.5">Enabled</p>
                    </div>
                    <div class="p-3 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10">
                        <p class="font-heading text-xl font-black text-purple-300">v2.4</p>
                        <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-wider mt-0.5">Pro Engine</p>
                    </div>
                </div>
            </div>

            <!-- Footer Quote -->
            <div class="relative z-10 text-[11px] text-indigo-300/80 font-medium">
                &copy; {{ date('Y') }} LMS Engine. Secured & audited administrative session.
            </div>
        </div>

        <!-- Right Login Form Section -->
        <div class="lg:col-span-6 p-8 sm:p-12 flex flex-col justify-center bg-slate-900">

            <div class="max-w-md mx-auto w-full space-y-6">

                <!-- Heading -->
                <div>
                    <h3 class="font-heading text-2xl font-extrabold text-white tracking-tight">Admin Sign In</h3>
                    <p class="text-xs text-slate-400 mt-1">Please enter your authorized administrative credentials to access your control panel.</p>
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
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-400">
                            Email Address <span class="text-rose-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fas fa-envelope text-xs"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full pl-10 pr-4 py-3 bg-slate-800 border border-white/10 rounded-2xl text-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                   placeholder="admin@example.com">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-400">
                                Password <span class="text-rose-400">*</span>
                            </label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fas fa-lock text-xs"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                   class="w-full pl-10 pr-4 py-3 bg-slate-800 border border-white/10 rounded-2xl text-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-white/20 bg-slate-800 transition">
                            <span class="ml-2 text-xs font-medium text-slate-300">Remember session</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 hover:scale-[1.01] active:scale-95 transition-all duration-200">
                        Sign In to Dashboard
                    </button>

                </form>

                <!-- Footer Student Switch -->
                <div class="pt-4 border-t border-white/10 text-center">
                    <p class="text-xs text-slate-400">
                        Not an administrator?
                        <a href="{{ route('login') }}" class="font-bold text-indigo-400 hover:text-indigo-300 transition">
                            Switch to Student Login
                        </a>
                    </p>
                </div>

            </div>

        </div>

    </div>

</body>
</html>
