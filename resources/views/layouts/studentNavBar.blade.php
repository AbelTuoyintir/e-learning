<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Student Course Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Your existing styles -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
<body class="bg-gray-50">
    <!-- Top Navigation Bar -->
    <nav class="bg-blue-600 text-white p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold"><i class="fas fa-graduation-cap mr-2"></i>Student Course Portal</h1>
            <div class="flex items-center space-x-4">
                <span class="text-blue-100">Welcome, Student</span>
               <form action="{{ route('student.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded transition flex items-center">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>


            </div>
        </div>
    </nav>

    <!-- Menu Bar -->
    <div class="bg-white shadow-md sticky top-0 z-40">
        <div class="container mx-auto">
            <div class="flex items-center justify-between px-6 py-3">
                <!-- Left Menu Items -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route("students.dashboard") }}" class="menu-item flex items-center px-3 py-2 rounded-md text-blue-600 bg-blue-50">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('students.courses') }}" class="menu-item flex items-center px-3 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition">
                        <i class="fas fa-book-open mr-2"></i>Courses
                    </a>

                    <a href="{{ route('students.enrolledcourses') }}" class="menu-item flex items-center px-3 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition">
                        <i class="fas fa-books mr-2"></i> My Courses
                    </a>

                    <a href="#" class="menu-item flex items-center px-3 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition">
                        <i class="fas fa-tasks mr-2"></i>Quizzes
                    </a>
                    <button  class="menu-item flex items-center px-3 py-2 rounded-md text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition">
                        <i class="fas fa-chart-bar mr-2"></i>Results
                    </button>
                </div>

                <!-- Right Menu Items -->
                <div class="flex items-center space-x-4">
                    <button class="relative p-2 text-gray-600 hover:text-blue-600 transition">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                    </button>
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition">
                            <img src="https://via.placeholder.com/32" alt="Profile" class="w-8 h-8 rounded-full">
                            <i class="fas fa-chevron-down text-sm"></i>
                        </button>
                        <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><i class="fas fa-user mr-2"></i>Profile</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><i class="fas fa-cog mr-2"></i>Settings</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @yield('content')
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
