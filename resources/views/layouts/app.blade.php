<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Portal - @yield('title', 'Dashboard')</title>

    <!-- Tailwind CSS & App Assets -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome & Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- SweetAlert2 & AlpineJS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-heading {
            font-family: 'Space Grotesk', 'Plus Jakarta Sans', sans-serif;
        }

        /* Custom sleek scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #818cf8; }

        /* Glassmorphism utility classes */
        .glass-panel {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .glass-dark {
            background: rgba(15, 23, 42, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Ambient glow background */
        .ambient-bg {
            background-color: #f8fafc;
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.08) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(59, 130, 246, 0.05) 0px, transparent 50%);
        }

        .glow-purple {
            box-shadow: 0 0 40px -10px rgba(168, 85, 247, 0.3);
        }

        .glow-indigo {
            box-shadow: 0 0 40px -10px rgba(99, 102, 241, 0.3);
        }

        /* Custom subtle hover scale effects */
        .card-hover-effect {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .card-hover-effect:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 32px -12px rgba(99, 102, 241, 0.15);
        }
    </style>
</head>
<body class="h-full text-slate-800 ambient-bg flex flex-col antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Top Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-slate-200/80 shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Left: Logo & Brand -->
                <div class="flex items-center space-x-4">
                    <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('tutor.dashboard') }}" class="group flex items-center space-x-3 transition-transform duration-200 active:scale-95">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-purple-600 flex items-center justify-center shadow-md shadow-indigo-500/20 group-hover:shadow-indigo-500/30 transition-all duration-300">
                            <i class="fas fa-shield-halved text-white text-lg"></i>
                        </div>
                        <div>
                            <span class="text-slate-900 font-extrabold text-lg tracking-tight flex items-center gap-1.5">
                                {{ Auth::user()->role === 'admin' ? 'Admin Portal' : 'Tutor Portal' }}
                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100">Pro</span>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Right: Actions & User Info -->
                <div class="flex items-center space-x-3 sm:space-x-4">

                    <!-- Search quick button trigger -->
                    <button class="hidden md:flex items-center space-x-2.5 px-3.5 py-1.5 bg-slate-100/80 hover:bg-indigo-50/80 hover:border-indigo-200 border border-slate-200/90 text-slate-500 hover:text-indigo-600 text-xs font-medium rounded-xl transition-all shadow-2xs" onclick="toggleSearchModal()">
                        <i class="fas fa-search text-slate-400 group-hover:text-indigo-500"></i>
                        <span>Search...</span>
                        <kbd class="px-1.5 py-0.5 bg-white border border-slate-200 text-slate-400 rounded-md text-[10px] font-mono shadow-2xs">⌘K</kbd>
                    </button>

                    <!-- Notifications Dropdown -->
                    <div class="relative">
                        <button id="notificationBtn" class="relative p-2.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50/80 rounded-xl transition-all duration-200 focus:outline-none">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-indigo-600 rounded-full ring-2 ring-white animate-pulse"></span>
                        </button>
                    </div>

                    <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

                    <!-- User Profile Dropdown -->
                    <div class="relative group" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-3 p-1.5 rounded-xl hover:bg-slate-100/80 transition-all duration-200 focus:outline-none">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=ffffff&bold=true"
                                 alt="{{ Auth::user()->name }}"
                                 class="w-9 h-9 rounded-xl ring-2 ring-indigo-500/20 object-cover shadow-xs">
                            <div class="text-left hidden md:block">
                                <p class="text-sm font-semibold text-slate-800 leading-tight">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] font-medium text-slate-500 capitalize leading-tight">{{ Auth::user()->role }}</p>
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
                             class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 divide-y divide-slate-100"
                             style="display: none;">
                            <div class="px-4 py-2.5">
                                <p class="text-xs text-slate-400 font-medium">Signed in as</p>
                                <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->email ?? Auth::user()->name }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition">
                                    <i class="fas fa-chart-pie w-4 text-slate-400 group-hover:text-indigo-600"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="#" class="flex items-center space-x-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition">
                                    <i class="fas fa-sliders w-4 text-slate-400 group-hover:text-indigo-600"></i>
                                    <span>Preferences</span>
                                </a>
                            </div>
                            <div class="py-1">
                                <form method="POST" action="{{ route('admin.logout') }}" id="logoutForm">
                                    @csrf
                                    <button type="button" onclick="confirmLogout()" class="flex items-center space-x-2.5 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 w-full text-left font-medium transition">
                                        <i class="fas fa-arrow-right-from-bracket w-4 text-rose-500"></i>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                        <i class="fas" :class="mobileMenuOpen ? 'fa-xmark text-lg' : 'fa-bars text-lg'"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div x-show="mobileMenuOpen" x-transition class="lg:hidden border-t border-slate-200 bg-white/95 backdrop-blur-xl px-4 py-5 shadow-xl space-y-4">
            <nav class="space-y-1">
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50' }}">
                    <i class="fas fa-chart-pie w-5 text-indigo-500"></i>
                    <span>Dashboard</span>
                </a>
                @else
                <a href="{{ route('tutor.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('tutor.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50' }}">
                    <i class="fas fa-chart-pie w-5 text-indigo-500"></i>
                    <span>Tutor Dashboard</span>
                </a>
                @endif
                <a href="{{ route('quizzes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('quizzes.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50' }}">
                    <i class="fas fa-circle-question w-5 text-purple-500"></i>
                    <span>Manage Quizzes</span>
                </a>
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('students.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('students.*') || request()->routeIs('admin.students') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50' }}">
                    <i class="fas fa-user-graduate w-5 text-blue-500"></i>
                    <span>Students</span>
                </a>
                @endif
                <a href="{{ route('courses.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('courses.*') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-700 hover:bg-slate-50' }}">
                    <i class="fas fa-book-bookmark w-5 text-emerald-500"></i>
                    <span>Manage Courses</span>
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Container Layout -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full flex flex-col lg:flex-row gap-8">

        <!-- Modern Desktop Sidebar Navigation -->
        <aside class="w-64 hidden lg:block shrink-0">
            <div class="sticky top-24 bg-white/80 backdrop-blur-md rounded-2xl shadow-sm border border-slate-200/80 p-4 space-y-6">

                <!-- Main Nav Group -->
                <div>
                    <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Core Menu</p>
                    <nav class="space-y-1">
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-indigo-500/10 to-purple-500/10 text-indigo-600 font-semibold border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-chart-pie text-base {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500' }} transition-colors"></i>
                                <span>Dashboard</span>
                            </div>
                            @if(request()->routeIs('admin.dashboard'))
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                            @endif
                        </a>
                        @else
                        <a href="{{ route('tutor.dashboard') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('tutor.dashboard') ? 'bg-gradient-to-r from-indigo-500/10 to-purple-500/10 text-indigo-600 font-semibold border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-chart-pie text-base {{ request()->routeIs('tutor.dashboard') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500' }} transition-colors"></i>
                                <span>Tutor Dashboard</span>
                            </div>
                        </a>
                        @endif

                        <a href="{{ route('quizzes.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('quizzes.*') ? 'bg-gradient-to-r from-indigo-500/10 to-purple-500/10 text-indigo-600 font-semibold border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-circle-question text-base {{ request()->routeIs('quizzes.*') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500' }} transition-colors"></i>
                                <span>Quizzes</span>
                            </div>
                        </a>

                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('students.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('students.*') || request()->routeIs('admin.students') ? 'bg-gradient-to-r from-indigo-500/10 to-purple-500/10 text-indigo-600 font-semibold border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-user-graduate text-base {{ request()->routeIs('students.*') || request()->routeIs('admin.students') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500' }} transition-colors"></i>
                                <span>Students</span>
                            </div>
                        </a>
                        @endif

                        <a href="{{ route('courses.index') }}" class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 {{ request()->routeIs('courses.*') ? 'bg-gradient-to-r from-indigo-500/10 to-purple-500/10 text-indigo-600 font-semibold border-l-4 border-indigo-600' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' }}">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-book-bookmark text-base {{ request()->routeIs('courses.*') ? 'text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500' }} transition-colors"></i>
                                <span>Courses</span>
                            </div>
                        </a>
                    </nav>
                </div>

                <!-- Secondary Nav Group -->
                <div class="pt-4 border-t border-slate-100">
                    <p class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Management</p>
                    <nav class="space-y-1">
                        <a href="#" class="group flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 transition">
                            <i class="fas fa-chart-line text-base text-slate-400 group-hover:text-indigo-500 transition-colors"></i>
                            <span>Analytics</span>
                        </a>
                        <a href="#" class="group flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 transition">
                            <i class="fas fa-gear text-base text-slate-400 group-hover:text-indigo-500 transition-colors"></i>
                            <span>Settings</span>
                        </a>
                    </nav>
                </div>

                <!-- Quick System Status Badge Card -->
                <div class="p-3 bg-gradient-to-br from-indigo-900 via-indigo-950 to-slate-900 rounded-xl text-white shadow-md">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-indigo-300">System Status</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1 animate-pulse"></span> Optimal
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-300">LMS Engine v2.4 Active</p>
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

    <!-- Quick Search Command Palette Modal -->
    <div id="quickSearchModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 hidden flex items-start justify-center pt-20 px-4 transition-opacity">
        <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full border border-slate-100 overflow-hidden transform transition-all" @click.away="toggleSearchModal()">
            <div class="p-4 border-b border-slate-100 flex items-center space-x-3 bg-slate-50/50">
                <i class="fas fa-search text-slate-400 text-lg ml-2"></i>
                <input type="text" id="quickSearchInput" placeholder="Type to search admin tools, courses, students..."
                       class="w-full bg-transparent border-none text-slate-800 text-sm font-semibold focus:outline-none focus:ring-0 placeholder:text-slate-400"
                       oninput="handleQuickSearch(this.value)">
                <kbd class="px-2 py-1 bg-white border border-slate-200 text-slate-400 rounded-lg text-xs font-mono shadow-xs">ESC</kbd>
            </div>
            <div class="p-4 max-h-80 overflow-y-auto space-y-2" id="searchResultsContainer">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 py-1">Quick Actions</p>
                <a href="{{ route('quizzes.create') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl hover:bg-indigo-50/70 text-slate-700 hover:text-indigo-600 transition group">
                    <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition">
                        <i class="fas fa-plus text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold">Create New Assessment Quiz</p>
                        <p class="text-[11px] text-slate-400">Add questions and assign modules</p>
                    </div>
                </a>
                <a href="{{ route('students.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl hover:bg-blue-50/70 text-slate-700 hover:text-blue-600 transition group">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fas fa-users-gear text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold">Manage Registered Students</p>
                        <p class="text-[11px] text-slate-400">View performance metrics and profiles</p>
                    </div>
                </a>
                <a href="{{ route('courses.index') }}" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-2xl hover:bg-emerald-50/70 text-slate-700 hover:text-emerald-600 transition group">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition">
                        <i class="fas fa-layer-group text-xs"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold">Manage Course Catalog</p>
                        <p class="text-[11px] text-slate-400">Configure modules, topics & pricing</p>
                    </div>
                </a>
            </div>
            <div class="p-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400 px-5 font-medium">
                <span>Navigate with shortcuts</span>
                <span>Press <kbd class="px-1.5 py-0.5 bg-white border border-slate-200 rounded text-slate-500 font-mono">⌘K</kbd> anytime</span>
            </div>
        </div>
    </div>

    <!-- Modern Clean Footer -->
    <footer class="mt-auto border-t border-slate-200/80 bg-white/50 backdrop-blur-md py-6 text-slate-500 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-2">
                <div class="w-6 h-6 rounded-md bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">A</div>
                <p class="text-xs font-medium text-slate-600">&copy; {{ date('Y') }} LMS Admin Engine. Crafted with precision.</p>
            </div>
            <div class="flex items-center space-x-6 text-xs text-slate-400 font-medium">
                <a href="#" class="hover:text-indigo-600 transition">Documentation</a>
                <a href="#" class="hover:text-indigo-600 transition">Support</a>
                <a href="#" class="hover:text-indigo-600 transition">Privacy Policy</a>
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
                        confirmButtonColor: '#4f46e5',
                        background: '#fff',
                        iconColor: '#ef4444'
                    });
                } else if (errors.length > 1) {
                    let errorList = '<ul class="text-left space-y-1 mt-2 text-sm text-slate-600">';
                    errors.forEach(error => {
                        errorList += `<li class="flex items-center gap-2"><i class="fas fa-circle-exclamation text-rose-500 text-xs"></i> ${error}</li>`;
                    });
                    errorList += '</ul>';
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Errors',
                        html: errorList,
                        confirmButtonColor: '#4f46e5'
                    });
                }
            }
        });

        // Quick search palette logic
        window.toggleSearchModal = function() {
            const modal = document.getElementById('quickSearchModal');
            if (!modal) return;
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => document.getElementById('quickSearchInput')?.focus(), 100);
            } else {
                modal.classList.add('hidden');
            }
        };

        document.addEventListener('keydown', function(e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                toggleSearchModal();
            }
            if (e.key === 'Escape') {
                document.getElementById('quickSearchModal')?.classList.add('hidden');
            }
        });

        window.handleQuickSearch = function(query) {
            const container = document.getElementById('searchResultsContainer');
            if (!container) return;
            const items = container.querySelectorAll('a');
            const q = query.toLowerCase();
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(q)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        };

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
                confirmButtonColor: '#4f46e5'
            });
        };

        // Confirmation dialog for logout
        window.confirmLogout = async function() {
            const result = await Swal.fire({
                title: 'Sign Out?',
                text: 'Are you sure you want to end your current session?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Sign Out',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-5 py-2.5 font-semibold',
                    cancelButton: 'rounded-xl px-5 py-2.5 font-semibold'
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
                        <div class="p-3 bg-indigo-50/60 rounded-xl border border-indigo-100 flex items-start space-x-3">
                            <div class="p-2 bg-indigo-600 text-white rounded-lg text-xs shrink-0"><i class="fas fa-user-plus"></i></div>
                            <div>
                                <p class="font-semibold text-slate-800 text-xs">New Student Registrations</p>
                                <p class="text-xs text-slate-500 mt-0.5">5 new students joined the platform today.</p>
                            </div>
                        </div>
                        <div class="p-3 bg-emerald-50/60 rounded-xl border border-emerald-100 flex items-start space-x-3">
                            <div class="p-2 bg-emerald-600 text-white rounded-lg text-xs shrink-0"><i class="fas fa-check-double"></i></div>
                            <div>
                                <p class="font-semibold text-slate-800 text-xs">Assessment Submissions</p>
                                <p class="text-xs text-slate-500 mt-0.5">18 quiz attempts graded automatically.</p>
                            </div>
                        </div>
                    </div>
                `,
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'Close',
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
