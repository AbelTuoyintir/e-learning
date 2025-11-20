<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }

        /* Smooth transitions */
        * { transition: all 0.3s ease; }

        /* Glass morphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">

    <!-- Top Navigation -->
    <nav class="bg-white/80 backdrop-blur-md shadow-md border-b border-white/20 sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">

            <!-- Logo -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-shield-halved text-white text-xl"></i>
                </div>
                <span class="text-slate-800 font-bold text-xl">Admin Portal</span>
            </a>

            <!-- Right Side -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <button class="relative p-2 text-slate-600 hover:text-indigo-600 rounded-lg hover:bg-indigo-50">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative group">
                    <button class="flex items-center space-x-2 p-2 rounded-lg hover:bg-slate-100">
                        <img src="https://i.pravatar.cc/40?img=7" alt="Admin" class="w-10 h-10 rounded-full">
                        <span class="text-slate-700 font-medium hidden md:inline">Admin User</span>
                        <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                    </button>
                    <!-- Dropdown menu -->
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible">
                        <a href="#" class="block px-4 py-3 text-slate-700 hover:bg-slate-50 rounded-t-xl"><i class="fas fa-user mr-2"></i>Profile</a>
                        <a href="#" class="block px-4 py-3 text-slate-700 hover:bg-slate-50"><i class="fas fa-cog mr-2"></i>Settings</a>
                        <hr class="my-1">
                        {{-- <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 rounded-b-xl">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form> --}}
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Layout -->
    <div class="container mx-auto px-6 py-8 flex gap-8">

        <!-- Sidebar -->
        <aside class="w-64 hidden lg:block">
            <div class="bg-white/70 backdrop-blur-lg rounded-2xl shadow-lg border border-white/30 p-4 sticky top-24">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">
                            <i class="fas fa-chart-pie"></i>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('quizzes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">
                            <i class="fas fa-question-circle"></i>
                            <span class="font-medium">Manage Quizzes</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.students') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">
                            <i class="fas fa-users"></i>
                            <span class="font-medium">Students</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('courses.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">
                            <i class="fas fa-book"></i>
                            <span class="font-medium">Manage Courses</span>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">
                            <i class="fas fa-chart-bar"></i>
                            <span class="font-medium">Results & Analytics</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-indigo-50 hover:text-indigo-600">
                            <i class="fas fa-cog"></i>
                            <span class="font-medium">Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

    </div>

    <!-- Footer -->
    <footer class="bg-slate-800 text-slate-300 py-6 mt-16">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; {{ date('Y') }} Admin Portal. All rights reserved.</p>
            <div class="mt-3 flex justify-center space-x-4">
                <a href="#" class="hover:text-white"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-white"><i class="fab fa-github"></i></a>
                <a href="#" class="hover:text-white"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
