@extends('layouts.studentNavBar')

@section('content')
<div class="container mx-auto px-4 py-8 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300 " >
    <h1 class="text-3xl font-bold text-gray-800 mb-6">My Enrolled Courses</h1>

    @if($enrolledCourses->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($enrolledCourses as $enrollment)
                @php
                    $course = $enrollment->course; // Get course from enrollment
                @endphp

                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <!-- Course Image (if any) -->
                    @if($course->image)
                        <div class="h-48 overflow-hidden">
                            <img src="{{ asset('storage/' . $course->image) }}"
                                 alt="{{ $course->title }}"
                                 class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $course->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            {{ Str::limit($course->description, 100) }}
                        </p>

                        <!-- Course Info -->
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            @if($course->category)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs mr-2">
                                    {{ $course->category }}
                                </span>
                            @endif
                            @if($course->instructor)
                                <span class="flex items-center">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $course->instructor }}
                                </span>
                            @endif
                        </div>

                        <!-- Enrollment Details -->
                        <div class="text-sm text-gray-500 mb-4">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Enrolled: {{ $enrollment->enrolled_at?->format('M d, Y') ?? $enrollment->updated_at?->format('M d, Y') ?? 'Not specified' }}
                        </div>

                        <div class="text-sm text-gray-600 mb-4">
                            @if(($enrollment->payment_status ?? 'free') === 'paid')
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-semibold">
                                    Paid • GHS {{ number_format((float) ($enrollment->price_paid ?? $course->price ?? 0), 2) }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">
                                    Free Enrollment
                                </span>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <!-- View Materials Button -->
                            <a href="{{ route('students.course.materials', $course) }}"
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex-1 text-center">
                                <i class="fas fa-book-open mr-2"></i>
                                View Materials
                            </a>

                            <!-- View Quizzes Button -->
                            <a href="{{ route('students.course.quizzes', $course) }}"
                               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition flex-1 text-center">
                                <i class="fas fa-question-circle mr-2"></i>
                                View Quizzes
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination if needed -->
        @if($enrolledCourses instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="mt-8">
                {{ $enrolledCourses->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow-md p-8 text-center">
            <i class="fas fa-book-open text-5xl text-gray-400 mb-4"></i>
            <h3 class="text-xl font-medium text-gray-700 mb-2">No Enrolled Courses</h3>
            <p class="text-gray-500 mb-6">You haven't enrolled in any courses yet.</p>
            <a href="{{ route('students.courses') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center transition">
                <i class="fas fa-search mr-2"></i>
                Browse Courses
            </a>
        </div>
    @endif
</div>

<!-- Materials Modal (if you still want it) -->
<div id="materialsModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 max-h-[80vh] overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <h3 class="text-xl font-bold text-slate-800">Course Materials</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div id="materialsContent" class="p-6 overflow-y-auto max-h-[60vh] space-y-4">
            <!-- Dynamic content will be loaded via JavaScript -->
        </div>
        <div class="p-6 border-t border-slate-100">
            <a href="#" id="viewAllMaterialsBtn"
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition inline-flex items-center">
                <i class="fas fa-book-open mr-2"></i>
                View All Materials
            </a>
        </div>
    </div>
</div>

<script>
function openMaterialsModal(courseId, courseTitle) {
    // Set the link for "View All Materials" button
    document.getElementById('viewAllMaterialsBtn').href = `/student/course/${courseId}/materials`;

    // Show loading state
    document.getElementById('materialsContent').innerHTML = `
        <div class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-3xl text-blue-500 mb-4"></i>
            <p class="text-gray-600">Loading materials preview...</p>
        </div>
    `;

    // Show the modal
    document.getElementById('materialsModal').classList.remove('hidden');

    // Load materials preview via AJAX (optional)
    fetch(`/api/course/${courseId}/materials-preview`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('materialsContent').innerHTML = data.html;
            } else {
                document.getElementById('materialsContent').innerHTML = `
                    <div class="text-center py-8">
                        <p class="text-gray-600">Click "View All Materials" to see complete materials.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('materialsContent').innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-circle text-3xl text-red-500 mb-4"></i>
                    <p class="text-gray-600">Unable to load preview.</p>
                </div>
            `;
        });
}

function closeModal() {
    document.getElementById('materialsModal').classList.add('hidden');
}

// Optional: Add button to each course card to open modal
document.addEventListener('DOMContentLoaded', function() {
    // You can add "Preview Materials" buttons to each card if you want
    document.querySelectorAll('.course-card').forEach(card => {
        const courseId = card.dataset.courseId;
        const courseTitle = card.dataset.courseTitle;

        // Add preview button if needed
        // const previewBtn = document.createElement('button');
        // previewBtn.textContent = 'Preview';
        // previewBtn.onclick = () => openMaterialsModal(courseId, courseTitle);
        // card.querySelector('.actions').appendChild(previewBtn);
    });
});
</script>
@endsection
