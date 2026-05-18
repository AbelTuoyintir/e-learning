@extends('layouts.studentNavBar')
@section('content')


<!-- Tailwind CSS (if not already imported) -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Page Container -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">

  <!-- Header -->
  <header class="max-w-7xl mx-auto px-6 pt-10 pb-6">
    <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight">Course Catalogue</h1>
    <p class="mt-2 text-slate-500">Explore, enrol and excel in courses built for tomorrow.</p>
  </header>

  <!-- Main Content -->
  <main class="max-w-7xl mx-auto px-6 pb-16">

    <!-- Available Courses -->
    <section id="coursesSection" class="mb-12">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
          <span class="w-10 h-10 grid place-items-center rounded-xl bg-indigo-100 text-indigo-600">
            <i class="fas fa-book-open"></i>
          </span>
          Available Courses
        </h2>
        <span class="text-sm text-slate-500">{{ $courses->total() }} course(s) open for enrolment</span>
      </div>

     @if($courses->isEmpty() && !request()->has('search'))
    <!-- Empty State for No Courses -->
    <div class="col-span-full">
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-slate-100 rounded-full mb-4">
                <i class="fas fa-book-open text-3xl text-slate-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-slate-700 mb-2">No courses available at this moment</h3>
            <p class="text-slate-500 mb-6 max-w-md mx-auto">We're working on adding new courses to enhance your learning experience. Please check back later for updates.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                <button onclick="location.reload()" class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition duration-200 font-semibold inline-flex items-center">
                    <i class="fas fa-redo mr-2"></i>
                    Refresh Page
                </button>
                <span class="text-slate-400 text-sm">or</span>
                <button onclick="showContactInfo()" class="px-6 py-3 border border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 transition duration-200 font-semibold inline-flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Get Help
                </button>
            </div>
        </div>
    </div>
@elseif($courses->isEmpty() && request()->has('search'))
    <!-- No results for search -->
    <div class="col-span-full">
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-slate-100 rounded-full mb-4">
                <i class="fas fa-search text-3xl text-slate-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-slate-700 mb-2">No courses found</h3>
            <p class="text-slate-500 mb-6 max-w-md mx-auto">Try adjusting your search criteria or browse all available courses.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                <button onclick="clearSearch()" class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition duration-200 font-semibold inline-flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Clear Search
                </button>
                <a href="{{ url()->current() }}" class="px-6 py-3 border border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 transition duration-200 font-semibold inline-flex items-center">
                    <i class="fas fa-list mr-2"></i>
                    View All Courses
                </a>
            </div>
        </div>
    </div>
@else
    <!-- Course Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($courses as $course)
            <!-- Course Card -->
            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-slate-100">
                <div class="h-40 bg-gradient-to-br from-cyan-400 to-blue-600 relative">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="absolute top-4 right-4 bg-white/90 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">Available</div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $course->title }}</h3>
                    <p class="text-slate-600 text-sm mb-4 line-clamp-2">{{ $course->description ?? 'No description available' }}</p>
                    <div class="mb-4">
                        @if((float) ($course->price ?? 0) > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-sm font-semibold">
                                GHS {{ number_format((float) $course->price, 2) }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-sm font-semibold">
                                Free Course
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-slate-500 text-sm">
                            <i class="fas fa-clock"></i>
                            <span>{{ $course->duration ?? 'N/A' }} hours</span>
                        </div>

                        @if((float) ($course->price ?? 0) > 0)
                            <a href="{{ route('students.courses.checkout', $course->id) }}"
                               class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition transform group-hover:scale-105"
                               data-course-id="{{ $course->id }}">
                                <i class="fas fa-credit-card mr-2"></i>Buy Now
                            </a>
                        @else
                            <form action="/students/course-enrollment" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $course->id }}">
                                <button type="submit"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition transform group-hover:scale-105"
                                        data-course-id="{{ $course->id }}">
                                    <i class="fas fa-plus-circle mr-2"></i>Enroll Now
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination (if needed) -->
    @if($courses->hasPages())
    <div class="flex items-center justify-between mt-6">
        <p class="text-sm text-slate-600">
            Showing {{ $courses->firstItem() }} to {{ $courses->lastItem() }} of {{ $courses->total() }} courses
            @if(request()->has('search'))
                for "{{ request('search') }}"
            @endif
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

            @php
                $current = $courses->currentPage();
                $last = $courses->lastPage();
                $start = max(1, $current - 1);
                $end = min($last, $current + 1);
            @endphp

            {{-- First Page --}}
            @if ($start > 1)
                <a href="{{ $courses->url(1) }}" class="px-3 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 transition">1</a>
                @if ($start > 2)
                    <span class="px-3 py-2 text-slate-400">...</span>
                @endif
            @endif

            {{-- Page Numbers --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $courses->currentPage())
                    <span class="px-3 py-2 bg-indigo-600 text-white rounded-lg">{{ $page }}</span>
                @else
                    <a href="{{ $courses->url($page) }}" class="px-3 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 transition">{{ $page }}</a>
                @endif
            @endfor

            {{-- Last Page --}}
            @if ($end < $last)
                @if ($end < $last - 1)
                    <span class="px-3 py-2 text-slate-400">...</span>
                @endif
                <a href="{{ $courses->url($last) }}" class="px-3 py-2 border border-slate-200 rounded-lg hover:bg-slate-50 transition">{{ $last }}</a>
            @endif

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
    @endif
@endif
    </section>


  </main>
</div>

<script>
  function closeModal(id){document.getElementById(id).classList.add('hidden')}


  function clearSearch() {
        // Remove search parameter from URL and reload
        const url = new URL(window.location.href);
        url.searchParams.delete('search');
        window.location.href = url.toString();
    }

    function showContactInfo() {
        Swal.fire({
            title: 'Need Help?',
            html: `
                <div class="text-left">
                    <p class="mb-4">If you need assistance or have questions about courses, please contact:</p>
                    <div class="space-y-2">
                        <p><strong>Email:</strong> support@yourinstitution.com</p>
                        <p><strong>Phone:</strong> +1 (555) 123-4567</p>
                        <p><strong>Hours:</strong> Mon-Fri, 9AM-5PM</p>
                    </div>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Got it!',
            confirmButtonColor: '#4f46e5'
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
    // Only show alerts if this is a fresh page load (not back/forward navigation)
    if (performance.navigation.type === 0 || performance.navigation.type === 1) {
        @if(session('success'))
        Swal.fire({
            title: '🎉 Enrolled Successfully!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonColor: '#4f46e5',
            timer: 3000,
            timerProgressBar: true,
            didClose: () => {
                // Optional: Update UI after successful enrollment
                updateUIAfterEnrollment({{ session('enrolled_course_id') ?? 'null' }});
            }
        });
        @endif

        @if(session('error'))
        Swal.fire({
            title: '❌ Enrollment Failed',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonColor: '#4f46e5'
        });
        @endif
    }
});

// Keep your helper function to update UI
function updateUIAfterEnrollment(courseId) {
    if (courseId) {
        const enrollButton = document.querySelector(`[data-course-id="${courseId}"]`);
        if (enrollButton) {
            enrollButton.disabled = true;
            enrollButton.textContent = 'Enrolled';
            enrollButton.classList.add('enrolled', 'bg-green-500', 'text-white');
        }
    }
}
</script>

@endsection
