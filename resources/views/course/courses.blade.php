@extends('layouts.app')

@section('title', 'Manage Courses')

@section('content')
<div class="space-y-8">

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-panel rounded-3xl p-6 sm:p-8 shadow-xl">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                <i class="fas fa-book-bookmark text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight font-heading">Manage Courses</h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-0.5">Create, edit, and organize learning courses and modules</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 shrink-0">
            <button onclick="openCourseModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-500/30 hover:scale-[1.02] active:scale-95 transition-all">
                <i class="fas fa-plus"></i>
                <span>Add Course</span>
            </button>
            <button onclick="openModuleModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold text-xs shadow-lg shadow-emerald-500/30 hover:scale-[1.02] active:scale-95 transition-all">
                <i class="fas fa-layer-group"></i>
                <span>Add Module</span>
            </button>
        </div>
    </div>

    <!-- Stats Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="glass-card rounded-2xl p-5 shadow-lg flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">Total Courses</span>
                <p class="text-2xl font-extrabold text-white mt-1 font-heading">{{ $totalCourses }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-base">
                <i class="fas fa-book"></i>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-5 shadow-lg flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">Published</span>
                <p class="text-2xl font-extrabold text-white mt-1 font-heading">{{ $totalPublishedCourses }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-base">
                <i class="fas fa-circle-check"></i>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-5 shadow-lg flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">Categories</span>
                <p class="text-2xl font-extrabold text-white mt-1 font-heading">Multi-Domain</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-base">
                <i class="fas fa-folder-tree"></i>
            </div>
        </div>

        <div class="glass-card rounded-2xl p-5 shadow-lg flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 font-heading">Enrollments</span>
                <p class="text-2xl font-extrabold text-white mt-1 font-heading">Active Catalog</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-base">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="glass-card rounded-2xl p-4 shadow-lg flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[200px]">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            <input type="text" id="searchInput" placeholder="Search courses by title..."
                   class="w-full pl-9 pr-4 py-2 bg-slate-900/80 border border-slate-700/80 rounded-xl text-xs font-medium text-slate-200 placeholder-slate-500 focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500 transition">
        </div>

        <select id="categoryFilter" class="px-3 py-2 bg-slate-900/80 border border-slate-700/80 rounded-xl text-xs font-medium text-slate-200 focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500 transition">
            <option value="">All Categories</option>
            <option value="Technology">Technology</option>
            <option value="Science">Science</option>
            <option value="Business">Business</option>
        </select>

        <select id="statusFilter" class="px-3 py-2 bg-slate-900/80 border border-slate-700/80 rounded-xl text-xs font-medium text-slate-200 focus:bg-slate-900 focus:ring-2 focus:ring-indigo-500 transition">
            <option value="">All Status</option>
            <option value="Published">Published</option>
            <option value="Draft">Draft</option>
        </select>

        <button id="filterButton" type="button" onclick="filterAndSearchCourses()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition shadow-md">
            <i class="fas fa-filter mr-1.5"></i> Filter
        </button>
    </div>

    <!-- Courses Table Card -->
    <div class="glass-panel rounded-3xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900/90 border-b border-slate-800 text-[11px] font-extrabold uppercase tracking-wider text-slate-400 font-heading">
                        <th class="px-6 py-4">Course Details</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Duration</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80 text-sm" id="coursesContainer">
                    @forelse ($courses as $course)
                    <tr class="hover:bg-slate-800/40 transition-colors group course-row"
                        data-title="{{ strtolower($course->title) }}"
                        data-category="{{ strtolower($course->category ?? '') }}"
                        data-status="published">
                        <!-- Title & Description -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center text-white text-base shadow-md shrink-0">
                                    <i class="fas fa-laptop-code"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-200 group-hover:text-indigo-400 transition-colors course-title font-heading">
                                        {{ $course->title }}
                                    </p>
                                    <p class="text-xs text-slate-400 line-clamp-1 mt-0.5">
                                        {{ $course->description ?: 'No detailed description available.' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <!-- Category -->
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-semibold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 course-category">
                                {{ $course->category ?? 'General' }}
                            </span>
                        </td>

                        <!-- Duration -->
                        <td class="px-6 py-4 text-xs font-semibold text-slate-300">
                            {{ $course->duration ? $course->duration . ' hrs' : 'Self-paced' }}
                        </td>

                        <!-- Price -->
                        <td class="px-6 py-4 text-xs font-bold text-slate-200">
                            @if((float) ($course->price ?? 0) > 0)
                                GHS {{ number_format((float) $course->price, 2) }}
                            @else
                                <span class="text-emerald-400 font-extrabold">Free</span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 course-status">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Published
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-1.5">
                                <a href="{{ route('courses.edit', $course->id) }}"
                                   class="p-2 rounded-xl text-slate-400 hover:text-indigo-400 hover:bg-slate-800 transition-all"
                                   title="Edit Course">
                                    <i class="fas fa-pen text-sm"></i>
                                </a>

                                <a href="{{ route('courses.modules', $course->id) }}"
                                   class="p-2 rounded-xl text-slate-400 hover:text-indigo-400 hover:bg-slate-800 transition-all"
                                   title="View Modules">
                                    <i class="fas fa-layer-group text-sm"></i>
                                </a>

                                <a href="{{ route('courses.show', $course->id) }}"
                                   class="p-2 rounded-xl text-slate-400 hover:text-indigo-400 hover:bg-slate-800 transition-all"
                                   title="View Details">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>

                                <form action="{{ route('courses.destroy', $course->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete course?')"
                                            class="p-2 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all"
                                            title="Delete Course">
                                        <i class="fas fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                            <div class="w-16 h-16 rounded-2xl bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-book-open text-2xl"></i>
                            </div>
                            <p class="font-bold text-slate-200">No courses created yet</p>
                            <p class="text-xs text-slate-400 mt-1">Get started by creating your first course.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($courses->hasPages())
    <div class="pt-4">
        {{ $courses->links() }}
    </div>
    @endif

</div>

<!-- Course Modal -->
<div id="courseModal" class="hidden fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
    <div class="bg-slate-900 rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col border border-slate-800">
        <div class="flex items-center justify-between p-6 border-b border-slate-800">
            <h3 class="text-lg font-bold text-white font-heading">Add New Course</h3>
            <button type="button" onclick="closeCourseModal()" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 flex items-center justify-center transition">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex-1">
            <form id="courseForm" action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1 font-heading">Course Title *</label>
                    <input type="text" name="title" required
                           class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1 font-heading">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium"
                              placeholder="Course summary..."></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1 font-heading">Instructor</label>
                        <input type="text" name="instructor"
                               class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1 font-heading">Duration (hrs)</label>
                        <input type="number" name="duration" min="0"
                               class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1 font-heading">Price (GHS)</label>
                        <input type="number" name="price" min="0" step="0.01" value="0"
                               class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1 font-heading">Category</label>
                    <input type="text" name="category" placeholder="e.g. Computer Science"
                           class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium">
                </div>

                <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeCourseModal()" class="px-4 py-2.5 bg-slate-800 text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-700 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition">
                        Save Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Module Modal -->
<div id="moduleModal" class="hidden fixed inset-0 bg-slate-950/80 backdrop-blur-md z-50 flex items-center justify-center p-4">
    <div class="bg-slate-900 rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col border border-slate-800">
        <div class="flex items-center justify-between p-6 border-b border-slate-800">
            <h3 class="text-lg font-bold text-white font-heading">Add New Module</h3>
            <button type="button" onclick="closeModuleModal()" class="w-8 h-8 rounded-xl bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 flex items-center justify-center transition">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex-1">
            <form id="moduleForm" action="{{ route('module.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1 font-heading">Module Title *</label>
                    <input type="text" name="title" required
                           class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1 font-heading">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-2xl text-slate-100 text-sm focus:bg-slate-800 focus:ring-2 focus:ring-indigo-500 transition font-medium"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1 font-heading">Select Course *</label>
                        <select name="course_id" required class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 font-medium">
                            <option value="">Select Course</option>
                            @foreach ($cour as $c)
                                <option value="{{ $c->id }}">{{ $c->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1 font-heading">Duration (minutes)</label>
                        <input type="number" name="duration_minutes" min="0"
                               class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-xl text-xs text-slate-100 font-medium">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-800 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModuleModal()" class="px-4 py-2.5 bg-slate-800 text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-700 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition">
                        Save Module
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openCourseModal() {
        document.getElementById('courseModal').classList.remove('hidden');
    }
    function closeCourseModal() {
        document.getElementById('courseModal').classList.add('hidden');
    }
    function openModuleModal() {
        document.getElementById('moduleModal').classList.remove('hidden');
    }
    function closeModuleModal() {
        document.getElementById('moduleModal').classList.add('hidden');
    }

    function filterAndSearchCourses() {
        const searchVal = document.getElementById("searchInput")?.value.toLowerCase().trim() || "";
        const categoryVal = document.getElementById("categoryFilter")?.value.toLowerCase() || "";
        const statusVal = document.getElementById("statusFilter")?.value.toLowerCase() || "";

        const rows = document.querySelectorAll(".course-row");
        rows.forEach(row => {
            const title = row.getAttribute("data-title") || "";
            const category = row.getAttribute("data-category") || "";
            const status = row.getAttribute("data-status") || "";

            const matchesSearch = !searchVal || title.includes(searchVal);
            const matchesCategory = !categoryVal || category.includes(categoryVal);
            const matchesStatus = !statusVal || status.includes(statusVal);

            if (matchesSearch && matchesCategory && matchesStatus) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("searchInput");
        const categoryFilter = document.getElementById("categoryFilter");
        const statusFilter = document.getElementById("statusFilter");
        const filterButton = document.getElementById("filterButton");

        if (searchInput) searchInput.addEventListener("keyup", filterAndSearchCourses);
        if (categoryFilter) categoryFilter.addEventListener("change", filterAndSearchCourses);
        if (statusFilter) statusFilter.addEventListener("change", filterAndSearchCourses);
        if (filterButton) filterButton.addEventListener("click", filterAndSearchCourses);
    });
</script>
@endsection
