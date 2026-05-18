<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Portal - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        * { transition: all 0.2s ease; }

        /* Glass morphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

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
<body class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">

    <!-- Top Navigation -->
    <nav class="bg-white/80 backdrop-blur-md shadow-md border-b border-white/20 sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">

            <!-- Logo -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-shield-halved text-white text-xl"></i>
                </div>
                <span class="text-slate-800 font-bold text-xl tracking-tight">Admin Portal</span>
            </a>

            <!-- Right Side -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <button id="notificationBtn" class="relative p-2 text-slate-600 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 transition">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center shadow-sm">3</span>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative group">
                    <button class="flex items-center space-x-2 p-2 rounded-lg hover:bg-slate-100 transition">
                        <img src="https://i.pravatar.cc/40?img=7" alt="Admin" class="w-10 h-10 rounded-full ring-2 ring-indigo-200">
                        <span class="text-slate-700 font-medium hidden md:inline">Admin User</span>
                        <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                    </button>
                    <!-- Dropdown menu -->
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <a href="#" class="block px-4 py-3 text-slate-700 hover:bg-slate-50 rounded-t-xl transition"><i class="fas fa-user mr-2"></i>Profile</a>
                        <a href="#" class="block px-4 py-3 text-slate-700 hover:bg-slate-50 transition"><i class="fas fa-cog mr-2"></i>Settings</a>
                        <hr class="my-1">
                        <form method="POST" action="{{ route('admin.logout') }}" id="logoutForm">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 rounded-b-xl transition">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </button>
                        </form>
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
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                            <i class="fas fa-chart-pie w-5"></i>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('quizzes.index') }}" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                            <i class="fas fa-question-circle w-5"></i>
                            <span class="font-medium">Manage Quizzes</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('students.index') }}" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                            <i class="fas fa-users w-5"></i>
                            <span class="font-medium">Students</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('courses.index') }}" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                            <i class="fas fa-book w-5"></i>
                            <span class="font-medium">Manage Courses</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                            <i class="fas fa-chart-bar w-5"></i>
                            <span class="font-medium">Results & Analytics</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-xl text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                            <i class="fas fa-cog w-5"></i>
                            <span class="font-medium">Settings</span>
                        </a>
                    </li>
                    <li class="pt-4 border-t border-slate-200">
                        <button onclick="confirmLogout()" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 hover:text-red-700 w-full text-left transition">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span class="font-medium">Logout</span>
                        </button>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
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

    <!-- Footer -->
    <footer class="bg-slate-800 text-slate-300 py-6 mt-16">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; {{ date('Y') }} Admin Portal. All rights reserved.</p>
            <div class="mt-3 flex justify-center space-x-4">
                <a href="#" class="hover:text-white transition"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-white transition"><i class="fab fa-github"></i></a>
                <a href="#" class="hover:text-white transition"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
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

        // Display success messages from session
        document.addEventListener('DOMContentLoaded', function() {
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
            
            // Validation errors
            const errorsDiv = document.getElementById('validation-errors');
            if (errorsDiv && errorsDiv.dataset.errors) {
                const errors = JSON.parse(errorsDiv.dataset.errors);
                if (errors.length === 1) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: errors[0],
                        confirmButtonColor: '#3b82f6',
                        background: '#fff',
                        iconColor: '#ef4444'
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

        // Global function to show success toast
        window.showSuccess = function(message, title = 'Success!') {
            Toast.fire({
                icon: 'success',
                title: message || title,
                background: '#10b981',
                color: '#fff',
                iconColor: '#fff'
            });
        };

        // Global function to show error toast
        window.showError = function(message, title = 'Error!') {
            Toast.fire({
                icon: 'error',
                title: message || title,
                background: '#ef4444',
                color: '#fff',
                iconColor: '#fff'
            });
        };

        // Global function to show warning
        window.showWarning = function(message, title = 'Warning!') {
            Toast.fire({
                icon: 'warning',
                title: message || title,
                background: '#f59e0b',
                color: '#fff',
                iconColor: '#fff'
            });
        };

        // Global function to show info
        window.showInfo = function(message, title = 'Info') {
            Toast.fire({
                icon: 'info',
                title: message || title,
                background: '#3b82f6',
                color: '#fff',
                iconColor: '#fff'
            });
        };

        // Confirmation dialog for delete actions
        window.confirmDelete = async function(entityName, deleteCallback) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete "${entityName}". This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            });
            
            if (result.isConfirmed) {
                if (deleteCallback && typeof deleteCallback === 'function') {
                    deleteCallback();
                }
                return true;
            }
            return false;
        };

        // Confirmation dialog for logout
        window.confirmLogout = async function() {
            const result = await Swal.fire({
                title: 'Logout Confirmation',
                text: 'Are you sure you want to logout from admin panel?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel'
            });
            
            if (result.isConfirmed) {
                document.getElementById('logoutForm')?.submit();
            }
        };

        // Generic AJAX request with SweetAlert loading
        window.ajaxRequest = async function(url, method = 'GET', data = null, successMessage = null) {
            // Show loading
            Swal.fire({
                title: 'Processing...',
                html: 'Please wait while we process your request',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            try {
                const options = {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                };
                
                if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
                    options.body = JSON.stringify(data);
                }
                
                const response = await fetch(url, options);
                const result = await response.json();
                
                Swal.close();
                
                if (response.ok && result.success !== false) {
                    if (successMessage) {
                        showSuccess(successMessage);
                    } else if (result.message) {
                        showSuccess(result.message);
                    }
                    return { success: true, data: result };
                } else {
                    const errorMsg = result.message || 'An error occurred. Please try again.';
                    showError(errorMsg);
                    return { success: false, error: errorMsg };
                }
            } catch (error) {
                Swal.close();
                showError('Network error: ' + error.message);
                return { success: false, error: error.message };
            }
        };

        // Handle form submissions with SweetAlert
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-detect forms with class 'ajax-form' for AJAX submission
            const ajaxForms = document.querySelectorAll('form.ajax-form');
            ajaxForms.forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(form);
                    const formObject = {};
                    formData.forEach((value, key) => {
                        formObject[key] = value;
                    });
                    
                    const url = form.action;
                    const method = form.method.toUpperCase();
                    const successMsg = form.dataset.successMessage || 'Operation completed successfully!';
                    
                    const result = await ajaxRequest(url, method, formObject, successMsg);
                    
                    if (result.success && form.dataset.redirect) {
                        setTimeout(() => {
                            window.location.href = form.dataset.redirect;
                        }, 1500);
                    } else if (result.success && form.dataset.resetOnSuccess) {
                        form.reset();
                    }
                });
            });
            
            // Handle delete buttons with class 'delete-btn'
            const deleteBtns = document.querySelectorAll('.delete-btn');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const deleteUrl = this.dataset.url;
                    const entityName = this.dataset.entity || 'this item';
                    const redirectUrl = this.dataset.redirect;
                    
                    const confirmed = await confirmDelete(entityName);
                    if (confirmed && deleteUrl) {
                        const result = await ajaxRequest(deleteUrl, 'DELETE', null, 'Deleted successfully!');
                        if (result.success && redirectUrl) {
                            setTimeout(() => {
                                window.location.href = redirectUrl;
                            }, 1500);
                        } else if (result.success) {
                            location.reload();
                        }
                    }
                });
            });
            
            // Handle bulk action buttons
            const bulkActionBtns = document.querySelectorAll('.bulk-action-btn');
            bulkActionBtns.forEach(btn => {
                btn.addEventListener('click', async function() {
                    const action = this.dataset.action;
                    const selectedIds = [];
                    document.querySelectorAll('.bulk-checkbox:checked').forEach(cb => {
                        selectedIds.push(cb.value);
                    });
                    
                    if (selectedIds.length === 0) {
                        showWarning('Please select at least one item');
                        return;
                    }
                    
                    const result = await Swal.fire({
                        title: 'Confirm Bulk Action',
                        text: `Are you sure you want to ${action} ${selectedIds.length} item(s)?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#6b7280'
                    });
                    
                    if (result.isConfirmed) {
                        const ajaxResult = await ajaxRequest(this.dataset.url, 'POST', { 
                            action: action, 
                            ids: selectedIds 
                        }, `${action} completed successfully!`);
                        
                        if (ajaxResult.success) {
                            setTimeout(() => location.reload(), 1500);
                        }
                    }
                });
            });
        });

        // Notification button handler
        document.getElementById('notificationBtn')?.addEventListener('click', function() {
            Swal.fire({
                title: 'Notifications',
                html: `
                    <div class="text-left space-y-3">
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <p class="font-semibold text-blue-800">New student registration</p>
                            <p class="text-sm text-gray-600">5 new students joined today</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <p class="font-semibold text-green-800">Quiz completed</p>
                            <p class="text-sm text-gray-600">15 quizzes were submitted today</p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-lg">
                            <p class="font-semibold text-yellow-800">System update</p>
                            <p class="text-sm text-gray-600">New version available</p>
                        </div>
                    </div>
                `,
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'Dismiss'
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>