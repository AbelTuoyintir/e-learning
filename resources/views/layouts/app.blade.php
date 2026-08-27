<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Portal - @yield('title', 'Dashboard')</title>

    <!-- Tailwind CSS & App Assets -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome & Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    <!-- SweetAlert2, AlpineJS & Chart.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <style>
        :root {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .font-heading {
            font-family: 'Space Grotesk', 'Plus Jakarta Sans', sans-serif;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* Ambient glowing background grid pattern */
        .ambient-canvas {
            background-color: #0b0f19;
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.12) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(14, 165, 233, 0.08) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(236, 72, 153, 0.06) 0px, transparent 50%);
        }

        .dark-glass-card {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .light-glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        /* Shimmer & Glow Effects */
        .glow-indigo {
            box-shadow: 0 0 25px -5px rgba(99, 102, 241, 0.4);
        }

        .glow-emerald {
            box-shadow: 0 0 25px -5px rgba(16, 185, 129, 0.4);
        }

        .glow-purple {
            box-shadow: 0 0 25px -5px rgba(168, 85, 247, 0.4);
        }

        .hover-lift {
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease;
        }

        .hover-lift:hover {
            transform: translateY(-3px);
        }
    </style>
</head>
<body class="h-full text-slate-100 ambient-canvas flex flex-col antialiased selection:bg-indigo-500 selection:text-white"
      x-data="{ searchModalOpen: false }"
      @keydown.window.cmd.k.prevent="searchModalOpen = true"
      @keydown.window.ctrl.k.prevent="searchModalOpen = true">

    <!-- Top Navigation Header -->
    <header class="sticky top-0 z-40 bg-slate-900/80 backdrop-blur-xl border-b border-white/10 shadow-lg shadow-black/20" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Left: Brand Identity -->
                <div class="flex items-center space-x-4">
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('tutor.dashboard') }}" class="group flex items-center space-x-3.5 transition-transform duration-200 active:scale-95">
                        <div class="relative flex items-center justify-center">
                            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl blur-sm opacity-70 group-hover:opacity-100 transition duration-300"></div>
                            <div class="relative w-10 h-10 rounded-xl bg-slate-900 border border-white/20 flex items-center justify-center text-indigo-400 group-hover:text-white transition-colors">
                                <i class="fas fa-shield-halved text-lg"></i>
                            </div>
                        </div>
                        <div>
                            <span class="font-heading text-white font-extrabold text-lg tracking-tight flex items-center gap-2">
                                {{ Auth::user()->role === 'admin' ? 'LMS Admin Portal' : 'Tutor Workspace' }}
                                <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-widest rounded-full bg-gradient-to-r from-indigo-500/20 to-purple-500/20 text-indigo-300 border border-indigo-500/30">v2.4 Pro</span>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Right: Actions & Profile -->
                <div class="flex items-center space-x-3 sm:space-x-4">

                    <!-- Command Palette Search Button -->
                    <button @click="searchModalOpen = true" class="hidden md:flex items-center space-x-3 px-3.5 py-1.5 bg-slate-800/80 hover:bg-slate-800 border border-white/10 text-slate-400 hover:text-white text-xs font-medium rounded-xl transition-all shadow-inner group">
                        <i class="fas fa-search text-slate-400 group-hover:text-indigo-400 transition-colors"></i>
                        <span>Search routes, students, courses...</span>
                        <kbd class="px-2 py-0.5 bg-slate-900 border border-white/15 text-slate-400 rounded-lg text-[10px] font-mono shadow-xs">⌘K</kbd>
                    </button>

                    <!-- Quick Action Trigger -->
                    <a href="{{ route('quizzes.create') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold text-xs shadow-md shadow-indigo-600/30 hover:scale-[1.02] active:scale-95 transition-all">
                        <i class="fas fa-plus text-[10px]"></i>
                        <span>New Quiz</span>
                    </a>

                    <!-- Notifications Button -->
                    <div class="relative">
                        <button id="notificationBtn" class="relative p-2.5 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl transition-all duration-200 focus:outline-none">
                            <i class="fas fa-bell text-base"></i>
                            <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-indigo-500 rounded-full ring-2 ring-slate-900 animate-pulse"></span>
                        </button>
                    </div>

                    <div class="h-6 w-px bg-white/10 hidden sm:block"></div>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-3 p-1.5 rounded-xl hover:bg-white/5 transition-all duration-200 focus:outline-none border border-transparent hover:border-white/10">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=ffffff&bold=true"
                                 alt="{{ Auth::user()->name }}"
                                 class="w-9 h-9 rounded-xl ring-2 ring-indigo-500/40 object-cover shadow-sm">
                            <div class="text-left hidden md:block">
                                <p class="text-xs font-bold text-white leading-tight truncate max-w-[120px]">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] font-medium text-indigo-300 capitalize leading-tight mt-0.5">{{ Auth::user()->role }} Account</p>
                            </div>
                            <i class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                             x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
                             class="absolute right-0 mt-2 w-60 bg-slate-900/95 border border-white/15 rounded-2xl shadow-2xl py-2 z-50 backdrop-blur-2xl divide-y divide-white/10"
                             style="display: none;">
                            <div class="px-4 py-3">
                                <p class="text-[11px] text-slate-400 font-medium">Signed in as</p>
                                <p class="text-xs font-bold text-white truncate">{{ Auth::user()->email ?? Auth::user()->name }}</p>
                            </div>
                            <div class="py-1.5">
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5 px-4 py-2 text-xs font-semibold text-slate-300 hover:bg-indigo-600/20 hover:text-indigo-300 transition">
                                    <i class="fas fa-chart-pie w-4 text-indigo-400"></i>
                                    <span>Control Center</span>
                                </a>
                                <a href="{{ route('students.index') }}" class="flex items-center space-x-2.5 px-4 py-2 text-xs font-semibold text-slate-300 hover:bg-indigo-600/20 hover:text-indigo-300 transition">
                                    <i class="fas fa-users-gear w-4 text-blue-400"></i>
                                    <span>Students Directory</span>
                                </a>
                                <a href="{{ route('courses.index') }}" class="flex items-center space-x-2.5 px-4 py-2 text-xs font-semibold text-slate-300 hover:bg-indigo-600/20 hover:text-indigo-300 transition">
                                    <i class="fas fa-layer-group w-4 text-emerald-400"></i>
                                    <span>Courses & Catalog</span>
                                </a>
                            </div>
                            <div class="py-1.5">
                                <form method="POST" action="{{ route('admin.logout') }}" id="logoutForm">
                                    @csrf
                                    <button type="button" onclick="confirmLogout()" class="flex items-center space-x-2.5 px-4 py-2 text-xs font-bold text-rose-400 hover:bg-rose-500/10 w-full text-left transition">
                                        <i class="fas fa-arrow-right-from-bracket w-4 text-rose-400"></i>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Toggle -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl transition">
                        <i class="fas" :class="mobileMenuOpen ? 'fa-xmark text-lg' : 'fa-bars text-lg'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div x-show="mobileMenuOpen" x-transition class="lg:hidden border-t border-white/10 bg-slate-900/95 backdrop-blur-xl px-4 py-5 shadow-2xl space-y-3">
            <nav class="space-y-1">
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-white/5' }}">
                    <i class="fas fa-chart-pie w-5 text-indigo-400"></i>
                    <span>Dashboard</span>
                </a>
                @else
                <a href="{{ route('tutor.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-bold {{ request()->routeIs('tutor.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-white/5' }}">
                    <i class="fas fa-chart-pie w-5 text-indigo-400"></i>
                    <span>Tutor Dashboard</span>
                </a>
                @endif
                <a href="{{ route('quizzes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-bold {{ request()->routeIs('quizzes.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-white/5' }}">
                    <i class="fas fa-circle-question w-5 text-purple-400"></i>
                    <span>Manage Quizzes</span>
                </a>
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('students.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-bold {{ request()->routeIs('students.*') || request()->routeIs('admin.students') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-white/5' }}">
                    <i class="fas fa-user-graduate w-5 text-blue-400"></i>
                    <span>Manage Students</span>
                </a>
                @endif
                <a href="{{ route('courses.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-bold {{ request()->routeIs('courses.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-white/5' }}">
                    <i class="fas fa-book-bookmark w-5 text-emerald-400"></i>
                    <span>Manage Courses</span>
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Container Layout -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full flex flex-col lg:flex-row gap-8">

        <!-- Sidebar Navigation -->
        <aside class="w-64 hidden lg:block shrink-0">
            <div class="sticky top-24 dark-glass-card rounded-3xl p-4 space-y-6 border border-white/10 shadow-xl">

                <!-- Main Nav Group -->
                <div>
                    <p class="px-3 text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-3">Control Panel</p>
                    <nav class="space-y-1.5">
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-chart-pie text-sm {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-indigo-400 group-hover:text-indigo-300' }} transition-colors"></i>
                                <span>Dashboard</span>
                            </div>
                            @if(request()->routeIs('admin.dashboard'))
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            @endif
                        </a>
                        @else
                        <a href="{{ route('tutor.dashboard') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('tutor.dashboard') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-chart-pie text-sm {{ request()->routeIs('tutor.dashboard') ? 'text-white' : 'text-indigo-400 group-hover:text-indigo-300' }} transition-colors"></i>
                                <span>Tutor Dashboard</span>
                            </div>
                        </a>
                        @endif

                        <a href="{{ route('quizzes.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('quizzes.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-clipboard-question text-sm {{ request()->routeIs('quizzes.*') ? 'text-white' : 'text-purple-400 group-hover:text-purple-300' }} transition-colors"></i>
                                <span>Quizzes</span>
                            </div>
                            <span class="px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-slate-800 text-purple-300 border border-purple-500/20">
                                {{ \App\Models\Quiz::count() }}
                            </span>
                        </a>

                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('students.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('students.*') || request()->routeIs('admin.students') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-user-graduate text-sm {{ request()->routeIs('students.*') || request()->routeIs('admin.students') ? 'text-white' : 'text-blue-400 group-hover:text-blue-300' }} transition-colors"></i>
                                <span>Students</span>
                            </div>
                            <span class="px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-slate-800 text-blue-300 border border-blue-500/20">
                                {{ \App\Models\Student::count() }}
                            </span>
                        </a>
                        @endif

                        <a href="{{ route('courses.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('courses.*') ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-book-bookmark text-sm {{ request()->routeIs('courses.*') ? 'text-white' : 'text-emerald-400 group-hover:text-emerald-300' }} transition-colors"></i>
                                <span>Courses</span>
                            </div>
                            <span class="px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-slate-800 text-emerald-300 border border-emerald-500/20">
                                {{ \App\Models\Course::count() }}
                            </span>
                        </a>
                    </nav>
                </div>

                <!-- Secondary Nav Group -->
                <div class="pt-4 border-t border-white/10">
                    <p class="px-3 text-[10px] font-extrabold uppercase tracking-widest text-slate-400 mb-3">Quick Actions</p>
                    <nav class="space-y-1">
                        <a href="{{ route('quizzes.create') }}" class="group flex items-center space-x-3 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:bg-white/5 hover:text-white transition">
                            <i class="fas fa-plus text-xs text-indigo-400 group-hover:rotate-90 transition-transform"></i>
                            <span>Add New Quiz</span>
                        </a>
                        <button onclick="showInfo('Audit activity stream is running live in SQLite background.', 'System Log')" class="w-full group flex items-center space-x-3 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:bg-white/5 hover:text-white transition text-left">
                            <i class="fas fa-list-check text-xs text-amber-400"></i>
                            <span>Audit Activity</span>
                        </button>
                    </nav>
                </div>

                <!-- Live Engine System Badge Card -->
                <div class="p-4 rounded-2xl bg-gradient-to-br from-indigo-950 via-slate-900 to-purple-950 border border-indigo-500/30 shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-300">Engine Health</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span> Active
                        </span>
                    </div>
                    <div class="space-y-1.5 text-[11px] text-slate-400">
                        <div class="flex justify-between">
                            <span>AI Tutor:</span>
                            <span class="text-white font-bold">OpenAI / Ollama</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Database:</span>
                            <span class="text-white font-bold">SQLite Connected</span>
                        </div>
                    </div>
                </div>

            </div>
        </aside>

        <!-- Main View Container -->
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

    <!-- Command Palette Search Modal -->
    <div x-show="searchModalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-start justify-center p-4 sm:p-6 lg:p-20"
         style="display: none;">

        <div @click.away="searchModalOpen = false" class="bg-slate-900 border border-white/20 w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden mt-10">
            <div class="p-4 border-b border-white/10 flex items-center space-x-3">
                <i class="fas fa-search text-indigo-400 text-base"></i>
                <input type="text" id="commandSearchInput" placeholder="Type a command or search feature..."
                       class="w-full bg-transparent text-white placeholder-slate-400 text-sm focus:outline-none font-medium">
                <button @click="searchModalOpen = false" class="px-2.5 py-1 rounded-lg bg-slate-800 text-slate-400 text-xs font-bold hover:text-white">
                    ESC
                </button>
            </div>

            <div class="p-4 max-h-96 overflow-y-auto space-y-2 text-xs">
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-500 px-3 py-1">Navigation Shortcuts</p>

                <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-white/10 text-slate-200 hover:text-white transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <span class="font-bold">Admin Control Center Dashboard</span>
                    </div>
                    <span class="text-[10px] text-slate-400">Go to Dashboard</span>
                </a>

                <a href="{{ route('quizzes.index') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-white/10 text-slate-200 hover:text-white transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-purple-500/20 text-purple-400 flex items-center justify-center">
                            <i class="fas fa-clipboard-question"></i>
                        </div>
                        <span class="font-bold">Quiz Management & Question Limit</span>
                    </div>
                    <span class="text-[10px] text-slate-400">Manage Quizzes</span>
                </a>

                <a href="{{ route('students.index') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-white/10 text-slate-200 hover:text-white transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <span class="font-bold">Student Profiles Directory</span>
                    </div>
                    <span class="text-[10px] text-slate-400">Manage Students</span>
                </a>

                <a href="{{ route('courses.index') }}" class="flex items-center justify-between p-3 rounded-2xl hover:bg-white/10 text-slate-200 hover:text-white transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                            <i class="fas fa-book-bookmark"></i>
                        </div>
                        <span class="font-bold">Course Catalog & Module Management</span>
                    </div>
                    <span class="text-[10px] text-slate-400">Manage Courses</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-auto border-t border-white/10 bg-slate-950/60 backdrop-blur-md py-6 text-slate-400 text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-2.5">
                <div class="w-6 h-6 rounded-lg bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-extrabold text-[10px]">LMS</div>
                <p class="font-semibold text-slate-300">&copy; {{ date('Y') }} LMS Engine Pro. Built with performance & AI assistance.</p>
            </div>
            <div class="flex items-center space-x-6 text-slate-400 font-semibold">
                <a href="#" class="hover:text-indigo-400 transition">Documentation</a>
                <a href="#" class="hover:text-indigo-400 transition">API System</a>
                <a href="#" class="hover:text-indigo-400 transition">Audit Logs</a>
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
                    let errorList = '<ul class="text-left space-y-1 mt-2 text-xs text-slate-300">';
                    errors.forEach(error => {
                        errorList += `<li class="flex items-center gap-2"><i class="fas fa-circle-exclamation text-rose-400 text-xs"></i> ${error}</li>`;
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
                text: 'Are you sure you want to end your current session?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Yes, Sign Out',
                cancelButtonText: 'Cancel',
                background: '#0f172a',
                color: '#fff',
                customClass: {
                    popup: 'rounded-3xl border border-white/10',
                    confirmButton: 'rounded-xl px-5 py-2.5 font-bold',
                    cancelButton: 'rounded-xl px-5 py-2.5 font-bold'
                }
            });

            if (result.isConfirmed) {
                document.getElementById('logoutForm')?.submit();
            }
        };

        // Notification center mock
        document.getElementById('notificationBtn')?.addEventListener('click', function() {
            Swal.fire({
                title: 'System Notifications',
                html: `
                    <div class="text-left space-y-3 mt-3">
                        <div class="p-3 bg-indigo-950/80 rounded-2xl border border-indigo-500/30 flex items-start space-x-3">
                            <div class="p-2 bg-indigo-600 text-white rounded-xl text-xs shrink-0"><i class="fas fa-user-plus"></i></div>
                            <div>
                                <p class="font-bold text-white text-xs">New Student Registrations</p>
                                <p class="text-xs text-slate-400 mt-0.5">5 new students joined the platform today.</p>
                            </div>
                        </div>
                        <div class="p-3 bg-emerald-950/80 rounded-2xl border border-emerald-500/30 flex items-start space-x-3">
                            <div class="p-2 bg-emerald-600 text-white rounded-xl text-xs shrink-0"><i class="fas fa-check-double"></i></div>
                            <div>
                                <p class="font-bold text-white text-xs">Assessment Submissions</p>
                                <p class="text-xs text-slate-400 mt-0.5">18 quiz attempts graded automatically.</p>
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
