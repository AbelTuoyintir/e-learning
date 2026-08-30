<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Portal - @yield('title', 'Dashboard')</title>

    <!-- Tailwind CSS & App Assets -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        heading: ['Space Grotesk', 'sans-serif'],
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome & Google Fonts (Space Grotesk + Plus Jakarta Sans) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Chart.js & SweetAlert2 & AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b0f17;
            color: #f1f5f9;
        }

        h1, h2, h3, h4, .font-heading {
            font-family: 'Space Grotesk', sans-serif;
        }

        /* Custom smooth scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Dark Glassmorphism UI */
        .glass-panel {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.07);
        }

        .glass-card:hover {
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 10px 30px -10px rgba(99, 102, 241, 0.15);
        }

        /* Dark Ambient Glow Background */
        .dark-ambient-bg {
            background-color: #0b0f17;
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.12) 0px, transparent 40%),
                radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.12) 0px, transparent 40%),
                radial-gradient(at 50% 100%, rgba(59, 130, 246, 0.08) 0px, transparent 50%);
        }
    </style>
</head>
<body class="h-full text-slate-100 dark-ambient-bg flex flex-col antialiased selection:bg-indigo-500 selection:text-white"
      x-data="{ cmdPaletteOpen: false }"
      @keydown.window.cmd.k.prevent="cmdPaletteOpen = true"
      @keydown.window.ctrl.k.prevent="cmdPaletteOpen = true">

    <!-- Top Navigation Header -->
    <header class="sticky top-0 z-40 bg-slate-900/80 backdrop-blur-xl border-b border-slate-800 shadow-xl" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Left: Logo & Brand -->
                <div class="flex items-center space-x-4">
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('tutor.dashboard') }}" class="group flex items-center space-x-3 transition-transform duration-200 active:scale-95">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 via-indigo-600 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-all duration-300">
                            <i class="fas fa-shield-halved text-white text-lg"></i>
                        </div>
                        <div>
                            <span class="text-white font-heading font-extrabold text-lg tracking-tight flex items-center gap-2">
                                {{ Auth::user()->role === 'admin' ? 'Admin Control' : 'Tutor Portal' }}
                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">v2.5</span>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Right: Actions & User Info -->
                <div class="flex items-center space-x-3 sm:space-x-4">

                    <!-- Search trigger button -->
                    <button @click="cmdPaletteOpen = true" class="hidden md:flex items-center space-x-3 px-3.5 py-1.5 bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 text-slate-400 hover:text-slate-200 text-xs font-medium rounded-xl transition-all shadow-inner">
                        <i class="fas fa-search text-indigo-400"></i>
                        <span>Search actions, students...</span>
                        <kbd class="px-1.5 py-0.5 bg-slate-900 border border-slate-700 text-slate-400 rounded text-[10px] font-mono shadow-xs">⌘K</kbd>
                    </button>

                    <!-- Notifications Button -->
                    <div class="relative">
                        <button id="notificationBtn" class="relative p-2.5 text-slate-400 hover:text-indigo-400 hover:bg-slate-800/80 rounded-xl transition-all duration-200 focus:outline-none border border-transparent hover:border-slate-700">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-indigo-500 rounded-full ring-2 ring-slate-900 animate-pulse"></span>
                        </button>
                    </div>

                    <div class="h-6 w-px bg-slate-800 hidden sm:block"></div>

                    <!-- User Profile Dropdown -->
                    <div class="relative group" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-3 p-1.5 rounded-xl hover:bg-slate-800/80 border border-transparent hover:border-slate-700/80 transition-all duration-200 focus:outline-none">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=ffffff&bold=true"
                                 alt="{{ Auth::user()->name }}"
                                 class="w-9 h-9 rounded-xl ring-2 ring-indigo-500/40 object-cover shadow-md">
                            <div class="text-left hidden md:block">
                                <p class="text-sm font-semibold text-slate-200 leading-tight">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] font-medium text-slate-400 capitalize leading-tight">{{ Auth::user()->role }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-slate-900/95 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-800 py-2 z-50 divide-y divide-slate-800"
                             style="display: none;">
                            <div class="px-4 py-2.5">
                                <p class="text-xs text-slate-400 font-medium">Signed in as</p>
                                <p class="text-sm font-bold text-slate-200 truncate">{{ Auth::user()->email ?? Auth::user()->name }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5 px-4 py-2 text-sm text-slate-300 hover:bg-indigo-600/10 hover:text-indigo-400 transition">
                                    <i class="fas fa-chart-pie w-4 text-slate-400"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="{{ route('courses.index') }}" class="flex items-center space-x-2.5 px-4 py-2 text-sm text-slate-300 hover:bg-indigo-600/10 hover:text-indigo-400 transition">
                                    <i class="fas fa-book-bookmark w-4 text-slate-400"></i>
                                    <span>Course Catalog</span>
                                </a>
                            </div>
                            <div class="py-1">
                                <form method="POST" action="{{ route('admin.logout') }}" id="logoutForm">
                                    @csrf
                                    <button type="button" onclick="confirmLogout()" class="flex items-center space-x-2.5 px-4 py-2 text-sm text-rose-400 hover:bg-rose-500/10 w-full text-left font-medium transition">
                                        <i class="fas fa-arrow-right-from-bracket w-4 text-rose-500"></i>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-slate-400 hover:bg-slate-800 rounded-xl transition-all">
                        <i class="fas" :class="mobileMenuOpen ? 'fa-xmark text-lg' : 'fa-bars text-lg'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div x-show="mobileMenuOpen" x-transition class="lg:hidden border-t border-slate-800 bg-slate-900/95 backdrop-blur-xl px-4 py-5 shadow-2xl space-y-4">
            <nav class="space-y-1">
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:bg-slate-800' }}">
                    <i class="fas fa-chart-pie w-5 text-indigo-400"></i>
                    <span>Dashboard</span>
                </a>
                @else
                <a href="{{ route('tutor.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('tutor.dashboard') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:bg-slate-800' }}">
                    <i class="fas fa-chart-pie w-5 text-indigo-400"></i>
                    <span>Tutor Dashboard</span>
                </a>
                @endif
                <a href="{{ route('quizzes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('quizzes.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:bg-slate-800' }}">
                    <i class="fas fa-circle-question w-5 text-purple-400"></i>
                    <span>Manage Quizzes</span>
                </a>
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('students.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('students.*') || request()->routeIs('admin.students') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:bg-slate-800' }}">
                    <i class="fas fa-user-graduate w-5 text-blue-400"></i>
                    <span>Students</span>
                </a>
                @endif
                <a href="{{ route('courses.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('courses.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:bg-slate-800' }}">
                    <i class="fas fa-book-bookmark w-5 text-emerald-400"></i>
                    <span>Manage Courses</span>
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Container Layout -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full flex flex-col lg:flex-row gap-8">

        <!-- Modern Desktop Sidebar Navigation -->
        <aside class="w-64 hidden lg:block shrink-0">
            <div class="sticky top-24 glass-panel rounded-3xl p-4 space-y-6 shadow-xl">

                <!-- Main Nav Group -->
                <div>
                    <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2 font-heading">Core Navigation</p>
                    <nav class="space-y-1">
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-indigo-600/30 to-purple-600/20 text-indigo-300 font-bold border border-indigo-500/30 shadow-md shadow-indigo-950/50' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-chart-pie text-base {{ request()->routeIs('admin.dashboard') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors"></i>
                                <span>Dashboard</span>
                            </div>
                            @if(request()->routeIs('admin.dashboard'))
                            <span class="w-2 h-2 rounded-full bg-indigo-400 shadow-sm shadow-indigo-400"></span>
                            @endif
                        </a>
                        @else
                        <a href="{{ route('tutor.dashboard') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('tutor.dashboard') ? 'bg-gradient-to-r from-indigo-600/30 to-purple-600/20 text-indigo-300 font-bold border border-indigo-500/30 shadow-md shadow-indigo-950/50' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-chart-pie text-base {{ request()->routeIs('tutor.dashboard') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors"></i>
                                <span>Tutor Dashboard</span>
                            </div>
                        </a>
                        @endif

                        <a href="{{ route('quizzes.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('quizzes.*') ? 'bg-gradient-to-r from-indigo-600/30 to-purple-600/20 text-indigo-300 font-bold border border-indigo-500/30 shadow-md shadow-indigo-950/50' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-circle-question text-base {{ request()->routeIs('quizzes.*') ? 'text-purple-400' : 'text-slate-500 group-hover:text-purple-400' }} transition-colors"></i>
                                <span>Quizzes</span>
                            </div>
                        </a>

                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('students.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('students.*') || request()->routeIs('admin.students') ? 'bg-gradient-to-r from-indigo-600/30 to-purple-600/20 text-indigo-300 font-bold border border-indigo-500/30 shadow-md shadow-indigo-950/50' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-user-graduate text-base {{ request()->routeIs('students.*') || request()->routeIs('admin.students') ? 'text-blue-400' : 'text-slate-500 group-hover:text-blue-400' }} transition-colors"></i>
                                <span>Students</span>
                            </div>
                        </a>
                        @endif

                        <a href="{{ route('courses.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('courses.*') ? 'bg-gradient-to-r from-indigo-600/30 to-purple-600/20 text-indigo-300 font-bold border border-indigo-500/30 shadow-md shadow-indigo-950/50' : 'text-slate-400 hover:bg-slate-800/60 hover:text-slate-200' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-book-bookmark text-base {{ request()->routeIs('courses.*') ? 'text-emerald-400' : 'text-slate-500 group-hover:text-emerald-400' }} transition-colors"></i>
                                <span>Courses</span>
                            </div>
                        </a>
                    </nav>
                </div>

                <!-- Secondary Nav Group -->
                <div class="pt-4 border-t border-slate-800">
                    <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2 font-heading">Control Tools</p>
                    <nav class="space-y-1">
                        <button @click="cmdPaletteOpen = true" class="w-full text-left group flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl text-sm font-medium text-slate-400 hover:bg-slate-800/60 hover:text-slate-200 transition">
                            <i class="fas fa-terminal text-base text-slate-500 group-hover:text-indigo-400 transition-colors"></i>
                            <span>Command Palette</span>
                        </button>
                    </nav>
                </div>

                <!-- Quick System Status Badge Card -->
                <div class="p-4 bg-gradient-to-br from-slate-900 via-indigo-950/80 to-purple-950/50 rounded-2xl text-white border border-slate-800 shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-slate-400">Platform Status</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span> Optimal
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-300">Engine v2.5 Stable • 99.9% Uptime</p>
                </div>

            </div>
        </aside>

        <!-- Main Content View Container -->
        <main class="flex-1 min-w-0">
            @if(session('success'))
                <div id="session-success" data-message="{{ session('success') }}"></div>
            @endif
            @if(session('error'))
                <div id="session-error" data-message="{{ session('error') }}"></div>
            @endif
            @if($errors->any())
                <div id="validation-errors" data-errors='@json($errors->all())'></div>
            @endif
            
            @yield('content')
        </main>

    </div>

    <!-- Command Palette Modal (Cmd + K) -->
    <div x-show="cmdPaletteOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 md:p-20 bg-slate-950/80 backdrop-blur-md flex items-start justify-center"
         style="display: none;"
         @click.away="cmdPaletteOpen = false">

        <div class="relative w-full max-w-2xl bg-slate-900 rounded-3xl shadow-2xl border border-slate-800 overflow-hidden divide-y divide-slate-800">
            <div class="relative flex items-center px-4">
                <i class="fas fa-search text-indigo-400 text-lg ml-2"></i>
                <input type="text"
                       placeholder="Type a command or search section..."
                       class="w-full bg-transparent border-0 px-4 py-4 text-slate-100 placeholder-slate-500 focus:ring-0 text-sm font-medium">
                <button @click="cmdPaletteOpen = false" class="px-2 py-1 bg-slate-800 text-slate-400 rounded-lg text-xs font-mono">ESC</button>
            </div>

            <div class="p-4 space-y-2 max-h-96 overflow-y-auto">
                <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-500">Quick Actions</p>

                <a href="{{ route('quizzes.create') }}" class="flex items-center justify-between p-3 hover:bg-slate-800/80 rounded-2xl transition text-slate-300 hover:text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center">
                            <i class="fas fa-plus text-xs"></i>
                        </div>
                        <span class="text-sm font-medium">Create New Assessment Quiz</span>
                    </div>
                    <i class="fas fa-arrow-right text-xs text-slate-600"></i>
                </a>

                <a href="{{ route('courses.index') }}" class="flex items-center justify-between p-3 hover:bg-slate-800/80 rounded-2xl transition text-slate-300 hover:text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                            <i class="fas fa-book-bookmark text-xs"></i>
                        </div>
                        <span class="text-sm font-medium">Manage Course Catalog</span>
                    </div>
                    <i class="fas fa-arrow-right text-xs text-slate-600"></i>
                </a>

                @if(Auth::user()->role === 'admin')
                <a href="{{ route('students.index') }}" class="flex items-center justify-between p-3 hover:bg-slate-800/80 rounded-2xl transition text-slate-300 hover:text-white">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center">
                            <i class="fas fa-user-graduate text-xs"></i>
                        </div>
                        <span class="text-sm font-medium">View Student Roster</span>
                    </div>
                    <i class="fas fa-arrow-right text-xs text-slate-600"></i>
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Modern Clean Footer -->
    <footer class="mt-auto border-t border-slate-800/80 bg-slate-900/60 backdrop-blur-md py-6 text-slate-400 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-2">
                <div class="w-6 h-6 rounded-md bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">A</div>
                <p class="text-xs font-medium text-slate-400">&copy; {{ date('Y') }} LMS Control Center. Built with modern precision.</p>
            </div>
            <div class="flex items-center space-x-6 text-xs text-slate-500 font-medium">
                <a href="#" class="hover:text-indigo-400 transition">Documentation</a>
                <a href="#" class="hover:text-indigo-400 transition">System Status</a>
                <a href="#" class="hover:text-indigo-400 transition">Security Policy</a>
            </div>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Global SweetAlert Toast setup
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            background: '#0f172a',
            color: '#f8fafc',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Auto display session messages
        document.addEventListener('DOMContentLoaded', function() {
            const successDiv = document.getElementById('session-success');
            if (successDiv && successDiv.dataset.message) {
                Toast.fire({
                    icon: 'success',
                    title: successDiv.dataset.message,
                    iconColor: '#10b981'
                });
            }
            
            const errorDiv = document.getElementById('session-error');
            if (errorDiv && errorDiv.dataset.message) {
                Toast.fire({
                    icon: 'error',
                    title: errorDiv.dataset.message,
                    iconColor: '#ef4444'
                });
            }
            
            const errorsDiv = document.getElementById('validation-errors');
            if (errorsDiv && errorsDiv.dataset.errors) {
                const errors = JSON.parse(errorsDiv.dataset.errors);
                if (errors.length === 1) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: errors[0],
                        confirmButtonColor: '#6366f1',
                        background: '#0f172a',
                        color: '#fff',
                        iconColor: '#ef4444'
                    });
                } else if (errors.length > 1) {
                    let errorList = '<ul class="text-left space-y-1 mt-2 text-sm text-slate-300">';
                    errors.forEach(error => {
                        errorList += `<li class="flex items-center gap-2"><i class="fas fa-circle-exclamation text-rose-500 text-xs"></i> ${error}</li>`;
                    });
                    errorList += '</ul>';
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Errors',
                        html: errorList,
                        confirmButtonColor: '#6366f1',
                        background: '#0f172a',
                        color: '#fff'
                    });
                }
            }
        });

        // Helper Notification Triggers
        window.showSuccess = function(message) {
            Toast.fire({ icon: 'success', title: message, iconColor: '#10b981' });
        };
        window.showError = function(message) {
            Toast.fire({ icon: 'error', title: message, iconColor: '#ef4444' });
        };
        window.showInfo = function(message, title = 'Notification') {
            Swal.fire({
                title: title,
                text: message,
                icon: 'info',
                confirmButtonColor: '#6366f1',
                background: '#0f172a',
                color: '#fff'
            });
        };

        // Confirmation dialog for logout
        window.confirmLogout = async function() {
            const result = await Swal.fire({
                title: 'Sign Out?',
                text: 'Are you sure you want to end your current session?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#334155',
                confirmButtonText: 'Yes, Sign Out',
                cancelButtonText: 'Cancel',
                background: '#0f172a',
                color: '#fff',
                customClass: {
                    popup: 'rounded-2xl border border-slate-800',
                    confirmButton: 'rounded-xl px-5 py-2.5 font-semibold',
                    cancelButton: 'rounded-xl px-5 py-2.5 font-semibold'
                }
            });
            
            if (result.isConfirmed) {
                document.getElementById('logoutForm')?.submit();
            }
        };

        // Notification center modal trigger
        document.getElementById('notificationBtn')?.addEventListener('click', function() {
            Swal.fire({
                title: 'System Notifications',
                background: '#0f172a',
                color: '#fff',
                html: `
                    <div class="text-left space-y-3 mt-3">
                        <div class="p-3 bg-indigo-500/10 rounded-2xl border border-indigo-500/20 flex items-start space-x-3">
                            <div class="p-2 bg-indigo-600 text-white rounded-xl text-xs shrink-0"><i class="fas fa-user-plus"></i></div>
                            <div>
                                <p class="font-bold text-slate-200 text-xs">New Student Registrations</p>
                                <p class="text-xs text-slate-400 mt-0.5">5 new students joined the platform today.</p>
                            </div>
                        </div>
                        <div class="p-3 bg-emerald-500/10 rounded-2xl border border-emerald-500/20 flex items-start space-x-3">
                            <div class="p-2 bg-emerald-600 text-white rounded-xl text-xs shrink-0"><i class="fas fa-check-double"></i></div>
                            <div>
                                <p class="font-semibold text-slate-200 text-xs">Assessment Submissions</p>
                                <p class="text-xs text-slate-400 mt-0.5">18 quiz attempts graded automatically.</p>
                            </div>
                        </div>
                    </div>
                `,
                confirmButtonColor: '#6366f1',
                confirmButtonText: 'Close',
                customClass: {
                    popup: 'rounded-3xl border border-slate-800'
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
