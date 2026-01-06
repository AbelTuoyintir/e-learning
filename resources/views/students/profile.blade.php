@extends('layouts.studentNavBar')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-6">My Profile</h1>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center mb-6">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->firstname ?? 'Student') }}&background=3B82F6&color=fff"
                     alt="Profile Picture" class="w-20 h-20 rounded-full mr-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ $student->firstname }} {{ $student->lastname }}</h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ $student->email }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Student ID: {{ $student->id }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Personal Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $student->firstname }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Name</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $student->lastname }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $student->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $student->phone ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Account Information</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Registration Date</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $student->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Login</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $student->last_login_at ? $student->last_login_at->format('M d, Y H:i') : 'Never' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $student->status == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ ucfirst($student->status) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Theme</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                <i class="fas fa-{{ $student->theme_preference == 'dark' ? 'moon' : 'sun' }} mr-1"></i>
                                {{ ucfirst($student->theme_preference) }} Mode
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('students.settings') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                    <i class="fas fa-cog mr-2"></i>Edit Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
