<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Portal - @yield('title', 'Dashboard')</title>

    <!-- Tailwind CSS & App Assets -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Chart.js & SweetAlert2 & AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .heading-font {
            font-family: 'Space Grotesk', sans-serif;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #090d16; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #334155; }

        /* Dark glassmorphism utility classes */
        .glass-panel {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.45);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.07);
        }

        .glass-card-hover {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-card-hover:hover {
            transform: translateY(-3px);
            background: rgba(30, 41, 59, 0.65);
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 12px 30px -10px rgba(99, 102, 241, 0.25);
        }

        /* Ambient glow background */
        .ambient-bg {
            background-color: #030712;
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.12) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(59, 130, 246, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.06) 0px, transparent 50%);
        }
    </style>
</head>
<body class="h-full text-slate-100 ambient-bg flex flex-col antialiased selection:bg-indigo-500 selection:text-white"
      x-data="{ commandPaletteOpen: false }"
      @keydown.window.cmd.k.prevent="commandPaletteOpen = true"
      @keydown.window.ctrl.k.prevent="commandPaletteOpen = true">

    <!-- Top Navigation Header -->
    <header class="sticky top-0 z-40 glass-panel border-b border-white/10 shadow-lg shadow-black/40" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">

                <!-- Left: Logo & Brand -->
                <div class="flex items-center space-x-4">
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('tutor.dashboard') }}" class="group flex items-center space-x-3 transition-transform duration-200 active:scale-95">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-gradient-to-tr from-indigo-500 via-indigo-600 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 group-hover:scale-105 transition-all duration-300">
                            <i class="fas fa-shield-cat text-white text-xl"></i>
                        </div>
                        <div>
                            <span class="text-white font-extrabold text-lg tracking-tight heading-font flex items-center gap-2">
                                {{ Auth::user()->role === 'admin' ? 'Admin Workspace' : 'Tutor Workspace' }}
                                <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full bg-gradient-to-r from-indigo-500/20 to-purple-500/20 text-indigo-300 border border-indigo-500/30">
                                    <i class="fas fa-bolt text-amber-400 mr-1 text-[9px]"></i>PRO v2.4
                                </span>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Right: Quick Actions & User Info -->
                <div class="flex items-center space-x-3 sm:space-x-4">

                    <!-- Command Palette quick button -->
                    <button @click="commandPaletteOpen = true" class="hidden md:flex items-center space-x-2 px-3.5 py-2 bg-slate-800/80 hover:bg-slate-800 border border-white/10 text-slate-300 hover:text-white text-xs font-semibold rounded-xl transition-all shadow-inner">
                        <i class="fas fa-magnifying-glass text-indigo-400"></i>
                        <span>Search commands...</span>
                        <kbd class="px-1.5 py-0.5 bg-slate-900 border border-white/10 text-slate-400 rounded text-[10px] font-mono">⌘K</kbd>
                    </button>

                    <!-- Notifications Dropdown -->
                    <div class="relative">
                        <button id="notificationBtn" class="relative p-2.5 text-slate-300 hover:text-white hover:bg-slate-800/80 rounded-xl border border-transparent hover:border-white/10 transition-all duration-200 focus:outline-none">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-indigo-500 rounded-full ring-2 ring-slate-900 animate-pulse"></span>
                        </button>
                    </div>

                    <div class="h-6 w-px bg-white/10 hidden sm:block"></div>

                    <!-- User Profile Dropdown -->
                    <div class="relative group" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-3 p-1.5 rounded-xl hover:bg-slate-800/80 border border-transparent hover:border-white/10 transition-all duration-200 focus:outline-none">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=ffffff&bold=true"
                                 alt="{{ Auth::user()->name }}"
                                 class="w-9 h-9 rounded-xl ring-2 ring-indigo-500/30 object-cover shadow-md">
                            <div class="text-left hidden md:block">
                                <p class="text-xs font-bold text-white leading-tight heading-font">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] font-medium text-indigo-300 uppercase tracking-wider leading-tight">{{ Auth::user()->role }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-60 bg-slate-900/95 backdrop-blur-2xl rounded-2xl shadow-2xl border border-white/10 py-2 z-50 divide-y divide-white/10"
                             style="display: none;">
                            <div class="px-4 py-3">
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Signed in as</p>
                                <p class="text-xs font-bold text-white truncate mt-0.5">{{ Auth::user()->email ?? Auth::user()->name }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5 px-4 py-2.5 text-xs font-semibold text-slate-300 hover:bg-slate-800 hover:text-white transition">
                                    <i class="fas fa-chart-pie w-4 text-indigo-400"></i>
                                    <span>Control Dashboard</span>
                                </a>
                                <a href="{{ route('students.index') }}" class="flex items-center space-x-2.5 px-4 py-2.5 text-xs font-semibold text-slate-300 hover:bg-slate-800 hover:text-white transition">
                                    <i class="fas fa-users-gear w-4 text-emerald-400"></i>
                                    <span>Student Directory</span>
                                </a>
                            </div>
                            <div class="py-1">
                                <form method="POST" action="{{ route('admin.logout') }}" id="logoutForm">
                                    @csrf
                                    <button type="button" onclick="confirmLogout()" class="flex items-center space-x-2.5 px-4 py-2.5 text-xs font-semibold text-rose-400 hover:bg-rose-500/10 w-full text-left transition">
                                        <i class="fas fa-right-from-bracket w-4 text-rose-500"></i>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-slate-300 hover:bg-slate-800 rounded-xl transition-all border border-white/10">
                        <i class="fas" :class="mobileMenuOpen ? 'fa-xmark text-lg' : 'fa-bars text-lg'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div x-show="mobileMenuOpen" x-transition class="lg:hidden border-t border-white/10 bg-slate-900/95 backdrop-blur-2xl px-4 py-5 shadow-2xl space-y-3">
            <nav class="space-y-1">
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-bold tracking-wide {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600/30 text-indigo-300 border border-indigo-500/40' : 'text-slate-300 hover:bg-slate-800' }}">
                    <i class="fas fa-chart-pie w-5 text-indigo-400"></i>
                    <span>Dashboard</span>
                </a>
                @else
                <a href="{{ route('tutor.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-bold tracking-wide {{ request()->routeIs('tutor.dashboard') ? 'bg-indigo-600/30 text-indigo-300 border border-indigo-500/40' : 'text-slate-300 hover:bg-slate-800' }}">
                    <i class="fas fa-chart-pie w-5 text-indigo-400"></i>
                    <span>Tutor Dashboard</span>
                </a>
                @endif
                <a href="{{ route('quizzes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-bold tracking-wide {{ request()->routeIs('quizzes.*') ? 'bg-indigo-600/30 text-indigo-300 border border-indigo-500/40' : 'text-slate-300 hover:bg-slate-800' }}">
                    <i class="fas fa-circle-question w-5 text-purple-400"></i>
                    <span>Manage Quizzes</span>
                </a>
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('students.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-bold tracking-wide {{ request()->routeIs('students.*') || request()->routeIs('admin.students') ? 'bg-indigo-600/30 text-indigo-300 border border-indigo-500/40' : 'text-slate-300 hover:bg-slate-800' }}">
                    <i class="fas fa-user-graduate w-5 text-blue-400"></i>
                    <span>Students</span>
                </a>
                @endif
                <a href="{{ route('courses.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-bold tracking-wide {{ request()->routeIs('courses.*') ? 'bg-indigo-600/30 text-indigo-300 border border-indigo-500/40' : 'text-slate-300 hover:bg-slate-800' }}">
                    <i class="fas fa-book-bookmark w-5 text-emerald-400"></i>
                    <span>Manage Courses</span>
                </a>
            </nav>
        </div>
    </header>

    <!-- Command Palette Modal -->
    <div x-show="commandPaletteOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-start justify-center pt-20 px-4 bg-black/80 backdrop-blur-md"
         style="display: none;"
         @keydown.escape.window="commandPaletteOpen = false">
        <div class="bg-slate-900 border border-white/15 rounded-3xl shadow-2xl max-w-xl w-full overflow-hidden" @click.away="commandPaletteOpen = false">
            <div class="p-4 border-b border-white/10 flex items-center space-x-3">
                <i class="fas fa-magnifying-glass text-indigo-400 text-lg"></i>
                <input type="text" placeholder="Type a command or search section..." class="w-full bg-transparent text-sm text-white placeholder-slate-400 focus:outline-none font-medium">
                <kbd class="px-2 py-1 bg-slate-800 border border-white/10 text-slate-400 rounded text-[10px] font-mono">ESC</kbd>
            </div>
            <div class="p-4 space-y-2 max-h-80 overflow-y-auto">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3">Navigation Shortcuts</p>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-indigo-600/20 hover:border-indigo-500/30 border border-transparent transition">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-chart-pie text-indigo-400"></i>
                        <span class="text-xs font-bold text-white">Go to Admin Dashboard</span>
                    </div>
                    <i class="fas fa-arrow-right text-xs text-slate-500"></i>
                </a>
                <a href="{{ route('students.index') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-indigo-600/20 hover:border-indigo-500/30 border border-transparent transition">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-user-graduate text-emerald-400"></i>
                        <span class="text-xs font-bold text-white">Manage Registered Students</span>
                    </div>
                    <i class="fas fa-arrow-right text-xs text-slate-500"></i>
                </a>
                <a href="{{ route('courses.index') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-indigo-600/20 hover:border-indigo-500/30 border border-transparent transition">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-layer-group text-blue-400"></i>
                        <span class="text-xs font-bold text-white">Manage Course Catalog</span>
                    </div>
                    <i class="fas fa-arrow-right text-xs text-slate-500"></i>
                </a>
                <a href="{{ route('quizzes.create') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-indigo-600/20 hover:border-indigo-500/30 border border-transparent transition">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-plus text-purple-400"></i>
                        <span class="text-xs font-bold text-white">Create New Assessment Quiz</span>
                    </div>
                    <i class="fas fa-arrow-right text-xs text-slate-500"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Container Layout -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full flex flex-col lg:flex-row gap-8">

        <!-- Sidebar Navigation -->
        <aside class="w-64 hidden lg:block shrink-0">
            <div class="sticky top-28 glass-panel rounded-3xl p-5 space-y-6 shadow-xl border border-white/10">

                <!-- Main Nav Group -->
                <div>
                    <p class="px-3 text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-3">Core Engine</p>
                    <nav class="space-y-1.5">
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center justify-between px-4 py-3 rounded-2xl text-xs font-bold tracking-wide transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-indigo-600/30 to-purple-600/30 text-indigo-300 border border-indigo-500/40 shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-chart-pie text-sm {{ request()->routeIs('admin.dashboard') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors"></i>
                                <span>Dashboard</span>
                            </div>
                            @if(request()->routeIs('admin.dashboard'))
                            <span class="w-2 h-2 rounded-full bg-indigo-400 shadow-sm shadow-indigo-400 animate-pulse"></span>
                            @endif
                        </a>
                        @else
                        <a href="{{ route('tutor.dashboard') }}" class="group flex items-center justify-between px-4 py-3 rounded-2xl text-xs font-bold tracking-wide transition-all duration-200 {{ request()->routeIs('tutor.dashboard') ? 'bg-gradient-to-r from-indigo-600/30 to-purple-600/30 text-indigo-300 border border-indigo-500/40 shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-chart-pie text-sm {{ request()->routeIs('tutor.dashboard') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-indigo-400' }} transition-colors"></i>
                                <span>Tutor Dashboard</span>
                            </div>
                        </a>
                        @endif

                        <a href="{{ route('quizzes.index') }}" class="group flex items-center justify-between px-4 py-3 rounded-2xl text-xs font-bold tracking-wide transition-all duration-200 {{ request()->routeIs('quizzes.*') ? 'bg-gradient-to-r from-indigo-600/30 to-purple-600/30 text-indigo-300 border border-indigo-500/40 shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-circle-question text-sm {{ request()->routeIs('quizzes.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-purple-400' }} transition-colors"></i>
                                <span>Quizzes</span>
                            </div>
                        </a>

                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('students.index') }}" class="group flex items-center justify-between px-4 py-3 rounded-2xl text-xs font-bold tracking-wide transition-all duration-200 {{ request()->routeIs('students.*') || request()->routeIs('admin.students') ? 'bg-gradient-to-r from-indigo-600/30 to-purple-600/30 text-indigo-300 border border-indigo-500/40 shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-user-graduate text-sm {{ request()->routeIs('students.*') || request()->routeIs('admin.students') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-blue-400' }} transition-colors"></i>
                                <span>Students</span>
                            </div>
                        </a>
                        @endif

                        <a href="{{ route('courses.index') }}" class="group flex items-center justify-between px-4 py-3 rounded-2xl text-xs font-bold tracking-wide transition-all duration-200 {{ request()->routeIs('courses.*') ? 'bg-gradient-to-r from-indigo-600/30 to-purple-600/30 text-indigo-300 border border-indigo-500/40 shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-book-bookmark text-sm {{ request()->routeIs('courses.*') ? 'text-indigo-400' : 'text-slate-500 group-hover:text-emerald-400' }} transition-colors"></i>
                                <span>Courses</span>
                            </div>
                        </a>
                    </nav>
                </div>

                <!-- Secondary Management Group -->
                <div class="pt-4 border-t border-white/10">
                    <p class="px-3 text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-3">Management</p>
                    <nav class="space-y-1.5">
                        <button onclick="showInfo('Advanced system analytics report generated dynamically in main dashboard view.', 'Analytics Engine')" class="w-full group flex items-center space-x-3 px-4 py-2.5 rounded-2xl text-xs font-semibold text-slate-400 hover:bg-white/5 hover:text-white transition">
                            <i class="fas fa-chart-line text-slate-500 group-hover:text-indigo-400 transition-colors"></i>
                            <span>Analytics</span>
                        </button>
                        <button onclick="showInfo('System configurations can be customized per role.', 'System Settings')" class="w-full group flex items-center space-x-3 px-4 py-2.5 rounded-2xl text-xs font-semibold text-slate-400 hover:bg-white/5 hover:text-white transition">
                            <i class="fas fa-sliders text-slate-500 group-hover:text-indigo-400 transition-colors"></i>
                            <span>Settings</span>
                        </button>
                    </nav>
                </div>

                <!-- Quick Status Card -->
                <div class="p-4 bg-gradient-to-br from-indigo-950 via-slate-900 to-purple-950 rounded-2xl border border-white/10 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold uppercase text-indigo-300 tracking-wider">Engine Status</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1 animate-pulse"></span> Optimal
                        </span>
                    </div>
                    <p class="text-[11px] font-semibold text-slate-300 heading-font">AI & Assessment Engine Active</p>
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

    <!-- Modern Clean Footer -->
    <footer class="mt-auto border-t border-white/10 glass-panel py-6 text-slate-400 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold shadow-md shadow-indigo-500/20">
                    <i class="fas fa-shield-cat"></i>
                </div>
                <p class="text-xs font-medium text-slate-300 heading-font">&copy; {{ date('Y') }} LMS Pro Administrative Suite. All rights reserved.</p>
            </div>
            <div class="flex items-center space-x-6 text-xs text-slate-400 font-medium">
                <a href="{{ route('privacy') }}" class="hover:text-indigo-400 transition">Privacy Policy</a>
                <a href="{{ route('about') }}" class="hover:text-indigo-400 transition">About System</a>
                <a href="{{ route('contact') }}" class="hover:text-indigo-400 transition">Support</a>
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
                    background: '#0f172a',
                    color: '#fff',
                    iconColor: '#10b981'
                });
            }
            
            const errorDiv = document.getElementById('session-error');
            if (errorDiv && errorDiv.dataset.message) {
                Toast.fire({
                    icon: 'error',
                    title: errorDiv.dataset.message,
                    background: '#0f172a',
                    color: '#fff',
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
            Toast.fire({ icon: 'success', title: message, background: '#0f172a', color: '#fff', iconColor: '#10b981' });
        };
        window.showError = function(message) {
            Toast.fire({ icon: 'error', title: message, background: '#0f172a', color: '#fff', iconColor: '#ef4444' });
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
                text: 'Are you sure you want to end your current admin session?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#334155',
                confirmButtonText: 'Yes, Sign Out',
                cancelButtonText: 'Cancel',
                background: '#0f172a',
                color: '#fff',
                customClass: {
                    popup: 'rounded-3xl border border-white/10',
                    confirmButton: 'rounded-xl px-5 py-2.5 font-semibold text-xs',
                    cancelButton: 'rounded-xl px-5 py-2.5 font-semibold text-xs'
                }
            });
            
            if (result.isConfirmed) {
                document.getElementById('logoutForm')?.submit();
            }
        };

        // Notification center modal
        document.getElementById('notificationBtn')?.addEventListener('click', function() {
            Swal.fire({
                title: 'System Notifications',
                html: `
                    <div class="text-left space-y-3 mt-3">
                        <div class="p-3 bg-indigo-500/10 rounded-2xl border border-indigo-500/20 flex items-start space-x-3">
                            <div class="p-2 bg-indigo-600 text-white rounded-xl text-xs shrink-0"><i class="fas fa-user-plus"></i></div>
                            <div>
                                <p class="font-bold text-white text-xs">New Student Registrations</p>
                                <p class="text-xs text-slate-300 mt-0.5">5 new students joined the platform today.</p>
                            </div>
                        </div>
                        <div class="p-3 bg-emerald-500/10 rounded-2xl border border-emerald-500/20 flex items-start space-x-3">
                            <div class="p-2 bg-emerald-600 text-white rounded-xl text-xs shrink-0"><i class="fas fa-check-double"></i></div>
                            <div>
                                <p class="font-bold text-white text-xs">Assessment Submissions</p>
                                <p class="text-xs text-slate-300 mt-0.5">18 quiz attempts graded automatically.</p>
                            </div>
                        </div>
                    </div>
                `,
                confirmButtonColor: '#6366f1',
                confirmButtonText: 'Close',
                background: '#0f172a',
                color: '#fff',
                customClass: {
                    popup: 'rounded-3xl border border-white/10'
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
