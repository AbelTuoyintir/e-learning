<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal Login - LMS Control Center</title>

    <!-- Tailwind CSS & Assets -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        heading: ['Space Grotesk', 'sans-serif'],
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #07090e;
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Space Grotesk', sans-serif;
        }

        .ambient-glow {
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.18) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.18) 0px, transparent 50%),
                radial-gradient(at 50% 50%, rgba(14, 165, 233, 0.08) 0px, transparent 60%);
        }

        .glass-panel {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
        }
    </style>
</head>
<body class="h-full antialiased selection:bg-indigo-500 selection:text-white ambient-glow flex items-center justify-center p-4 sm:p-6 lg:p-8">

    <!-- Container Card -->
    <div class="w-full max-w-5xl glass-panel rounded-3xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]">

        <!-- Left Visual Feature Section -->
        <div class="lg:col-span-6 relative bg-gradient-to-br from-slate-950 via-indigo-950/90 to-purple-950/80 p-8 sm:p-12 text-white flex flex-col justify-between overflow-hidden border-b lg:border-b-0 lg:border-r border-slate-800/80">
            <!-- Glow background overlays -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Top Brand -->
            <div class="relative z-10 flex items-center space-x-3.5">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-shield-halved text-white text-xl"></i>
                </div>
                <div>
                    <span class="text-xl font-extrabold tracking-tight text-white font-heading">LMS Control Center</span>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-indigo-300 font-heading">Admin Portal</span>
                </div>
            </div>

            <!-- Center Content Feature -->
            <div class="relative z-10 my-8 space-y-6">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 backdrop-blur-md border border-indigo-500/30 text-indigo-200 text-xs font-semibold">
                    <i class="fas fa-lock text-emerald-400"></i>
                    Encrypted Administrative Session
                </div>

                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight text-white font-heading">
                    Empowering Modern Educational Intelligence.
                </h2>
                <p class="text-indigo-200/80 text-sm leading-relaxed">
                    Manage course structures, monitor student progression in real time, grade assessments automatically, and deliver AI-assisted academic tutoring.
                </p>

                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-slate-800/80 text-center">
                    <div class="p-3.5 rounded-2xl bg-slate-900/60 backdrop-blur-md border border-slate-800/80">
                        <p class="text-lg font-extrabold text-white font-heading">100%</p>
                        <p class="text-[10px] text-indigo-300 font-medium">System Uptime</p>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-900/60 backdrop-blur-md border border-slate-800/80">
                        <p class="text-lg font-extrabold text-white font-heading">AI</p>
                        <p class="text-[10px] text-indigo-300 font-medium">Tutor Integrated</p>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-slate-900/60 backdrop-blur-md border border-slate-800/80">
                        <p class="text-lg font-extrabold text-white font-heading">v3.0</p>
                        <p class="text-[10px] text-indigo-300 font-medium">Engine Build</p>
                    </div>
                </div>
            </div>

            <!-- Footer Quote -->
            <div class="relative z-10 text-xs text-slate-400">
                &copy; {{ date('Y') }} LMS Platform. All administrative actions are logged and audited.
            </div>
        </div>

        <!-- Right Login Form Section -->
        <div class="lg:col-span-6 p-8 sm:p-12 flex flex-col justify-center bg-slate-900/50 backdrop-blur-xl">

            <div class="max-w-md mx-auto w-full space-y-6">

                <!-- Heading -->
                <div>
                    <h3 class="text-2xl font-extrabold text-white tracking-tight font-heading">Admin Sign In</h3>
                    <p class="text-xs text-slate-400 mt-1">Please enter your authorized administrative credentials to continue.</p>
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
                            background: '#090d16',
                            color: '#fff',
                            customClass: {
                                popup: 'rounded-2xl border border-slate-800'
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
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">
                            Email Address <span class="text-rose-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fas fa-envelope text-sm"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full pl-10 pr-4 py-3 bg-slate-950/80 border border-slate-800 rounded-2xl text-slate-100 text-sm focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium placeholder-slate-500 shadow-inner"
                                   placeholder="admin@example.com">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">
                                Password <span class="text-rose-400">*</span>
                            </label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fas fa-lock text-sm"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                   class="w-full pl-10 pr-4 py-3 bg-slate-950/80 border border-slate-800 rounded-2xl text-slate-100 text-sm focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all font-medium placeholder-slate-500 shadow-inner"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-700 bg-slate-950 transition">
                            <span class="ml-2 text-xs font-medium text-slate-400">Remember session</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-indigo-500 via-indigo-600 to-purple-600 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 hover:scale-[1.01] active:scale-95 transition-all duration-200">
                        Sign In to Control Center
                    </button>

                </form>

                <!-- Footer Student Switch -->
                <div class="pt-4 border-t border-slate-800/80 text-center">
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
