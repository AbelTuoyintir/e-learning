<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Portal')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Toast animation */
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .toast-notification {
            animation: slideIn 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <!-- Top Navigation Bar -->


    <!-- Menu Bar -->
    <div class="bg-blue-600 dark:bg-gray-800 shadow-md sticky top-0 z-40" x-data="{ open: false }">
        <div class="container mx-auto">
            <div class="flex items-center justify-between px-4 md:px-6 py-3">
                 <nav class="text-white">
                    <div class="flex items-center">
                        <h1 class="text-lg md:text-2xl font-bold truncate"><i class="fas fa-graduation-cap mr-2"></i><span class="hidden sm:inline">Student Course Portal</span><span class="sm:hidden">Portal</span></h1>
                    </div>
                </nav>

                <!-- Desktop Menu Items -->
                <div class="hidden lg:flex items-center space-x-4">
                    <a href="{{ route("students.dashboard") }}" class="menu-item flex items-center px-3 py-2 rounded-md {{ request()->routeIs('students.dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-200 hover:bg-blue-500' }} transition">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('students.courses') }}" class="menu-item flex items-center px-3 py-2 rounded-md {{ request()->routeIs('students.courses') ? 'text-blue-600 bg-blue-50' : 'text-gray-200 hover:bg-blue-500' }} transition">
                        <i class="fas fa-book-open mr-2"></i>Courses
                    </a>
                    <a href="{{ route('students.enrolledcourses') }}" class="menu-item flex items-center px-3 py-2 rounded-md {{ request()->routeIs('students.enrolledcourses') ? 'text-blue-600 bg-blue-50' : 'text-gray-200 hover:bg-blue-500' }} transition">
                        <i class="fas fa-books mr-2"></i> My Courses
                    </a>
                    <a href="{{ route('student.quizzes') }}" class="menu-item flex items-center px-3 py-2 rounded-md {{ request()->routeIs('student.quizzes') ? 'text-blue-600 bg-blue-50' : 'text-gray-200 hover:bg-blue-500' }} transition">
                        <i class="fas fa-tasks mr-2"></i>Quizzes
                    </a>
                    <a href="{{ route('results.index') }}" class="menu-item flex items-center px-3 py-2 rounded-md {{ request()->routeIs('results.index') ? 'text-blue-600 bg-blue-50' : 'text-gray-200 hover:bg-blue-500' }} transition">
                        <i class="fas fa-chart-bar mr-2"></i>Results
                    </a>
                </div>

                <!-- Right Menu Items -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications Dropdown -->
                    <div class="relative">
                        <button onclick="toggleNotifications()" class="relative p-2 text-gray-200 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                            <i class="fas fa-bell text-xl"></i>
                            <span id="notificationCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                        </button>
                        <div id="notificationsDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-700 rounded-md shadow-lg z-50 max-h-96 overflow-y-auto">
                            <div class="py-2">
                                <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-200">
                                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Notifications</h3>
                                </div>
                                <div id="notificationsList" class="divide-y divide-gray-200 dark:divide-gray-600">
                                    <!-- Notifications will be loaded here -->
                                </div>
                                <div class="px-4 py-2 border-t border-gray-200 dark:border-gray-600">
                                    <button onclick="markAllAsRead()" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                        Mark all as read
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="flex items-center space-x-2 text-white dark:text-gray-300 hover:text-blue-100 transition">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->firstname ?? 'Student') }}&background=3B82F6&color=fff"
                                 alt="Profile" class="w-8 h-8 rounded-full border border-white/50">
                            <i class="fas fa-chevron-down text-xs hidden sm:inline"></i>
                        </button>
                        <div id="profileDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg py-1 z-50 border dark:border-gray-600">
                            <a href="{{ route('students.profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600"><i class="fas fa-user mr-2"></i>Profile</a>
                            <a href="{{ route('students.settings') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600"><i class="fas fa-cog mr-2"></i>Settings</a>
                            <form action="{{ route('student.logout') }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="open = !open" class="lg:hidden p-2 text-white hover:bg-blue-500 rounded-lg transition">
                        <i class="fas" :class="open ? 'fa-times' : 'fa-bars'"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="lg:hidden" x-show="open" x-transition @click.away="open = false">
                <div class="px-4 pt-2 pb-4 space-y-1 border-t border-blue-500">
                    <a href="{{ route("students.dashboard") }}" class="block px-3 py-2 rounded-md text-white hover:bg-blue-500 transition">
                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('students.courses') }}" class="block px-3 py-2 rounded-md text-white hover:bg-blue-500 transition">
                        <i class="fas fa-book-open mr-2"></i>Courses
                    </a>
                    <a href="{{ route('students.enrolledcourses') }}" class="block px-3 py-2 rounded-md text-white hover:bg-blue-500 transition">
                        <i class="fas fa-books mr-2"></i> My Courses
                    </a>
                    <a href="{{ route('student.quizzes') }}" class="block px-3 py-2 rounded-md text-white hover:bg-blue-500 transition">
                        <i class="fas fa-tasks mr-2"></i>Quizzes
                    </a>
                    <a href="{{ route('results.index') }}" class="block px-3 py-2 rounded-md text-white hover:bg-blue-500 transition">
                        <i class="fas fa-chart-bar mr-2"></i>Results
                    </a>
                </div>
            </div>
        </div>
    </div>

    <main class="min-h-screen">
        @if(session('success'))
            <div id="session-success" data-message="{{ session('success') }}"></div>
        @endif
        @if(session('error'))
            <div id="session-error" data-message="{{ session('error') }}"></div>
        @endif
        @if(session('info'))
            <div id="session-info" data-message="{{ session('info') }}"></div>
        @endif
        @if($errors->any())
            <div id="validation-errors" data-errors='@json($errors->all())'></div>
        @endif
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('hidden');
        }

        function toggleNotifications() {
            const dropdown = document.getElementById('notificationsDropdown');
            dropdown.classList.toggle('hidden');
            if (!dropdown.classList.contains('hidden')) {
                loadNotifications();
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const profileDropdown = document.getElementById('profileDropdown');
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            const profileButton = document.querySelector('[onclick="toggleDropdown()"]');
            const notificationsButton = document.querySelector('[onclick="toggleNotifications()"]');

            if (!profileDropdown.contains(event.target) && !profileButton.contains(event.target)) {
                profileDropdown.classList.add('hidden');
            }

            if (!notificationsDropdown.contains(event.target) && !notificationsButton.contains(event.target)) {
                notificationsDropdown.classList.add('hidden');
            }
        });

        // Theme management
        function applyTheme(theme) {
            const body = document.body;
            if (theme === 'dark') {
                body.classList.add('dark');
            } else {
                body.classList.remove('dark');
            }
        }

        // Global SweetAlert configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Apply theme on page load based on user preference
        document.addEventListener('DOMContentLoaded', function() {
            const userTheme = '{{ Auth::user()->theme_preference ?? 'light' }}';
            applyTheme(userTheme);
            updateNotificationCount();

            // Session success
            const successDiv = document.getElementById('session-success');
            if (successDiv && successDiv.dataset.message) {
                Toast.fire({
                    icon: 'success',
                    title: successDiv.dataset.message,
                    background: '#10b981',
                    color: '#fff',
                    iconColor: '#fff'
                });
            }

            // Session error
            const errorDiv = document.getElementById('session-error');
            if (errorDiv && errorDiv.dataset.message) {
                Toast.fire({
                    icon: 'error',
                    title: errorDiv.dataset.message,
                    background: '#ef4444',
                    color: '#fff',
                    iconColor: '#fff'
                });
            }

            // Session info
            const infoDiv = document.getElementById('session-info');
            if (infoDiv && infoDiv.dataset.message) {
                Toast.fire({
                    icon: 'info',
                    title: infoDiv.dataset.message,
                    background: '#3b82f6',
                    color: '#fff',
                    iconColor: '#fff'
                });
            }

            // Validation errors
            const errorsDiv = document.getElementById('validation-errors');
            if (errorsDiv && errorsDiv.dataset.errors) {
                const errors = JSON.parse(errorsDiv.dataset.errors);
                if (errors.length === 1) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: errors[0],
                        confirmButtonColor: '#3b82f6'
                    });
                } else if (errors.length > 1) {
                    let errorList = '<ul class="text-left">';
                    errors.forEach(error => {
                        errorList += `<li>• ${error}</li>`;
                    });
                    errorList += '</ul>';
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Errors',
                        html: errorList,
                        confirmButtonColor: '#3b82f6'
                    });
                }
            }
        });

        // Notification functions
        function loadNotifications() {
            fetch('{{ route("students.notifications") }}')
                .then(response => response.json())
                .then(notifications => {
                    displayNotifications(notifications);
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                });
        }

        function displayNotifications(notifications) {
            const list = document.getElementById('notificationsList');
            list.innerHTML = '';

            if (notifications.length === 0) {
                list.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center">No notifications</div>';
                return;
            }

            notifications.forEach(notification => {
                const item = document.createElement('div');
                item.className = `px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer ${!notification.is_read ? 'bg-blue-50 dark:bg-blue-900/20' : ''}`;
                item.onclick = () => markAsRead(notification.id);

                const typeColors = {
                    'success': 'text-green-600 dark:text-green-400',
                    'error': 'text-red-600 dark:text-red-400',
                    'warning': 'text-yellow-600 dark:text-yellow-400',
                    'info': 'text-blue-600 dark:text-blue-400'
                };

                item.innerHTML = `
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-bell ${typeColors[notification.type] || 'text-gray-400'}"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">${notification.title}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">${notification.message}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">${new Date(notification.created_at).toLocaleDateString()}</p>
                        </div>
                        ${!notification.is_read ? '<div class="flex-shrink-0"><div class="w-2 h-2 bg-blue-500 rounded-full"></div></div>' : ''}
                    </div>
                `;

                list.appendChild(item);
            });
        }

        function markAsRead(notificationId) {
            fetch(`{{ url('/students/notifications') }}/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                    updateNotificationCount();
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        }

        function markAllAsRead() {
            fetch('{{ route("students.notifications.markAllRead") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                    updateNotificationCount();
                }
            })
            .catch(error => {
                console.error('Error marking all notifications as read:', error);
            });
        }

        function updateNotificationCount() {
            fetch('{{ route("students.notifications") }}')
                .then(response => response.json())
                .then(notifications => {
                    const unreadCount = notifications.filter(n => !n.is_read).length;
                    const countElement = document.getElementById('notificationCount');

                    if (unreadCount > 0) {
                        countElement.textContent = unreadCount > 99 ? '99+' : unreadCount;
                        countElement.classList.remove('hidden');
                    } else {
                        countElement.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error updating notification count:', error);
                });
        }
    </script>
</body>
</html>
