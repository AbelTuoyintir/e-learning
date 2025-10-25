@extends('layouts.app')
@section('content')

<!-- Courses Management Container -->
<div class="space-y-8">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Manage Courses</h1>
            <p class="text-slate-500 mt-1">Create, edit and organise courses for students</p>
        </div>
        <button onclick="openCourseModal()" class="px-5 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition flex items-center gap-2 shadow-lg hover:shadow-xl">
            <i class="fas fa-plus"></i> Add New Course
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Total Courses</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalCourses }}</p>
                </div>
                <div class="w-10 h-10 grid place-items-center rounded-lg bg-indigo-100 text-indigo-600"><i class="fas fa-book"></i></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Published</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalPublishedCourses }}</p>
                </div>
                <div class="w-10 h-10 grid place-items-center rounded-lg bg-green-100 text-green-600"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Drafts</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">6</p>
                </div>
                <div class="w-10 h-10 grid place-items-center rounded-lg bg-orange-100 text-orange-600"><i class="fas fa-file-alt"></i></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm">Total Enrolments</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">1,432</p>
                </div>
                <div class="w-10 h-10 grid place-items-center rounded-lg bg-purple-100 text-purple-600"><i class="fas fa-user-graduate"></i></div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-4 flex flex-wrap items-center gap-4">
        <input type="text" id="searchInput" placeholder="Search courses..." class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-transparent">
        <select id="categoryFilter" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-transparent">
            <option>All Categories</option>
            <option>Technology</option>
            <option>Science</option>
            <option>Business</option>
        </select>
        <select id="statusFilter" class="px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-transparent">
            <option>All Status</option>
            <option>Published</option>
            <option>Draft</option>
            <option>Archived</option>
        </select>
        <button class="ml-auto px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition">
            <i class="fas fa-filter mr-2"></i>Filter
        </button>
    </div>

    <!-- Courses Table -->
    <div class="bg-white rounded-2xl shadow-md border border-slate-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-700">Course</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-700">Category</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-700">Duration</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-700">Enrolled</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-700">Status</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-700 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y max-h-[90vh] overflow-y-auto divide-slate-100">
                <!-- Row 1 -->
                @foreach ($courses as $course)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-600 grid place-items-center text-white text-lg">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">{{$course->title}}</p>
                                <p class="text-sm text-slate-500">
                                    {{$course->description}}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $course->category }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $course->duration }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">324</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            <i class="fas fa-circle text-[6px]"></i>Published
                        </span>
                    </td>
                     <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition" title="Duplicate">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

 <div class="flex items-center justify-between mt-6">
    <p class="text-sm text-slate-600">
        Showing {{ $courses->firstItem() }} to {{ $courses->lastItem() }} of {{ $courses->total() }} courses
    </p>
    <div class="flex items-center gap-2">
        {{-- Previous Page Link --}}
        @if ($courses->onFirstPage())
            <button class="px-3 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 transition disabled:opacity-50" disabled>
                <i class="fas fa-chevron-left"></i>
            </button>
        @else
            <a href="{{ $courses->previousPageUrl() }}" class="px-3 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        {{-- Page Numbers --}}
        @for ($page = 1; $page <= $courses->lastPage(); $page++)
            @if ($page == $courses->currentPage())
                <span class="px-3 py-2 bg-indigo-600 text-white rounded-lg">{{ $page }}</span>
            @else
                <a href="{{ $courses->url($page) }}" class="px-3 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 transition">{{ $page }}</a>
            @endif
        @endfor

        {{-- Next Page Link --}}
        @if ($courses->hasMorePages())
            <a href="{{ $courses->nextPageUrl() }}" class="px-3 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <button class="px-3 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 transition disabled:opacity-50" disabled>
                <i class="fas fa-chevron-right"></i>
            </button>
        @endif
    </div>
</div>
</div>

<!-- Course Modal -->
<div id="courseModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-slate-200">
            <h3 class="text-xl font-bold text-slate-800" id="modalTitle">Add New Course</h3>
            <button type="button" onclick="closeCourseModal()" class="text-slate-400 hover:text-slate-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body - Scrollable Content -->
        <div class="flex-1 overflow-y-auto">
            <form id="courseForm" action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                <div class="space-y-6">
                    <!-- Course Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Course Name *</label>
                        <input type="text" name="title" id="name"
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('name') border-red-300 @enderror"
                               value="{{ old('title') }}" required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('description') border-red-300 @enderror"
                                  placeholder="Enter course description...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Two Column Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Instructor -->
                        <div>
                            <label for="instructor" class="block text-sm font-semibold text-slate-700 mb-2">Instructor</label>
                            <input type="text" name="instructor" id="instructor"
                                   class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('instructor') border-red-300 @enderror"
                                   value="{{ old('instructor') }}"
                                   placeholder="Instructor name">
                            @error('instructor')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div>
                            <label for="duration" class="block text-sm font-semibold text-slate-700 mb-2">Duration (hours)</label>
                            <input type="number" name="duration" id="duration" min="0"
                                   class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('duration') border-red-300 @enderror"
                                   value="{{ old('duration') }}"
                                   placeholder="Course duration">
                            @error('duration')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Two Column Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-semibold text-slate-700 mb-2">Category</label>
                            <input type="text" name="category" id="category"
                                   class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('category') border-red-300 @enderror"
                                   value="{{ old('category') }}"
                                   placeholder="Course category">
                            @error('category')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label for="image" class="block text-sm font-semibold text-slate-700 mb-2">Course Image</label>
                            <input type="file" name="image" id="image"
                                   accept="image/*"
                                   class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition @error('image') border-red-300 @enderror">
                            @error('image')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-slate-500">Supported: JPEG, PNG, JPG, GIF. Max: 2MB</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-slate-200">
                    <button type="button" onclick="closeCourseModal()"
                            class="px-5 py-2.5 border border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 transition duration-200 font-medium">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 transition duration-200 font-medium">
                        <span class="btn-text">Save Course</span>
                        <i class="btn-icon fas fa-save ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Removed extra closing </div> -->

<!-- Include SweetAlert2 in your layout or add it here -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Function to close modal
    function closeCourseModal() {
        const modal = document.getElementById('courseModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        // Reset form when closingyy
        const form = document.getElementById('courseForm');
        if (form) {
            form.reset();
        }
    }

    // Function to open modal
    function openCourseModal() {
        const modal = document.getElementById('courseModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Enhanced form submission with loading state
    document.addEventListener('DOMContentLoaded', function() {
        const courseForm = document.getElementById('courseForm');
        if (courseForm) {
            courseForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                const btnText = submitBtn.querySelector('.btn-text');
                const btnIcon = submitBtn.querySelector('.btn-icon');

                if (submitBtn && btnText && btnIcon) {
                    // Disable button and show loading state
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

                    // Change button content to loading spinner
                    btnText.innerHTML = 'Saving...';
                    btnIcon.classList.remove('fa-save');
                    btnIcon.classList.add('fa-spinner', 'fa-spin');
                }
            });
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCourseModal();
            }
        });

        // Close modal when clicking outside
        const modal = document.getElementById('courseModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeCourseModal();
                }
            });
        }

        // SweetAlert2 for success messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#4f46e5',
                background: '#f0f9ff',
                iconColor: '#10b981'
            }).then(() => {
                closeCourseModal();
                // Optional: refresh the page to show the new course
                window.location.reload();
            });
        @endif

        // SweetAlert2 for error messages
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonText: 'Try Again',
                confirmButtonColor: '#dc2626',
                background: '#fef2f2',
                iconColor: '#ef4444'
            });
        @endif

        // SweetAlert2 for validation errors
        @if($errors->any())
            @if(!session('success') && !session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `
                    <div class="text-left text-sm text-red-600 mt-2">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                `,
                confirmButtonText: 'Fix Errors',
                confirmButtonColor: '#dc2626',
                background: '#fef2f2',
                iconColor: '#ef4444',
                width: '600px'
            });
            @endif
        @endif
    });

   function renderCourses(courses) {
    const container = document.getElementById("coursesContainer");
    container.innerHTML = "";

    if (courses.length === 0) {
        container.innerHTML = "<p>No courses found</p>";
        return;
    }

    courses.forEach(course => {
        const div = document.createElement("div");
        div.classList.add("p-4", "border", "rounded-lg", "mb-2");
        div.innerHTML = `
            <h3 class="font-bold">${course.title}</h3>
            <p>${course.description}</p>
            <small>${course.category} - ${course.status}</small>
        `;
        container.appendChild(div);
    });
}

function filterAndSearchCourses() {
    const search = document.getElementById("searchInput").value;
    const category = document.getElementById("categoryFilter").value;
    const status = document.getElementById("statusFilter").value;

    fetch(`/courses/filter-search?search=${search}&category=${category}&status=${status}`)
        .then(res => res.json())
        .then(data => renderCourses(data))
        .catch(err => console.error(err));
}

// 🔹 Bind both search + filter
document.getElementById("searchInput").addEventListener("keyup", filterAndSearchCourses);
document.getElementById("filterButton").addEventListener("click", filterAndSearchCourses);

</script>
@endsection
