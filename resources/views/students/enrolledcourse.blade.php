@extends('layouts.studentNavBar')
@section('content')

<!-- My Enrolled Courses -->
<section id="enrolledSection" class="mb-12 container mx-auto px-6 py-20">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
        <span class="w-10 h-10 grid place-items-center rounded-xl bg-green-100 text-green-600">
            <i class="fas fa-user-check"></i>
        </span>
        My Enrolled Courses
        </h2>
        <span class="text-sm text-slate-500">{{ $enrolledCourses->count() }} active courses</span>
    </div>

    @if($enrolledCourses->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($enrolledCourses as $enrollment)
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="h-3 bg-gradient-to-r from-cyan-400 to-blue-600"></div>
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">{{ $enrollment->course->title }}</h3>
                        <p class="text-sm text-slate-500 mt-1">Enrolled {{ $enrollment->created_at->format('d M Y') }}</p>
                    </div>
                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">Active</span>
                </div>
                <div class="mt-5 flex gap-3">
                    <button onclick="viewMaterials('{{ $enrollment->course->title }}')" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-book mr-2"></i>Materials
                    </button>
                    <button onclick="viewQuizzes('{{ $enrollment->course->id }}', '{{ $enrollment->course->title }}')" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-tasks mr-2"></i>Quizzes
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-12 text-center">
        <div class="w-20 h-20 mx-auto mb-4 grid place-items-center rounded-full bg-slate-100 text-slate-400">
            <i class="fas fa-book-open text-2xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-slate-700 mb-2">No Enrolled Courses</h3>
        <p class="text-slate-500 mb-6">You haven't enrolled in any courses yet.</p>
        <a href="{{ route('courses.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold transition">
            Browse Courses
        </a>
    </div>
    @endif
</section>

<!-- Modals -->
<!-- Materials Modal -->
<div id="materialsModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 max-h-[80vh] overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <h3 class="text-xl font-bold text-slate-800">Course Materials</h3>
            <button onclick="closeModal('materialsModal')" class="text-slate-400 hover:text-slate-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="materialsContent" class="p-6 overflow-y-auto max-h-[60vh] space-y-4">
            <!-- Dynamic content -->
        </div>
    </div>
</div>

<!-- Quizzes Modal -->
<div id="quizzesModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 max-h-[80vh] overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <h3 class="text-xl font-bold text-slate-800">Course Quizzes</h3>
            <button onclick="closeModal('quizzesModal')" class="text-slate-400 hover:text-slate-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="quizzesContent" class="p-6 overflow-y-auto max-h-[60vh] space-y-4">
            <!-- Dynamic content -->
        </div>
    </div>
</div>

<script>
function viewMaterials(course) {
    document.getElementById('materialsContent').innerHTML = `<div class="text-center py-10 text-slate-500">Loading materials for <strong>${course}</strong>...</div>`;
    document.getElementById('materialsModal').classList.remove('hidden');

    // Simulate load
    setTimeout(() => {
        document.getElementById('materialsContent').innerHTML = `
            <div class="border border-slate-200 rounded-xl p-4 hover:bg-slate-50 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <i class="fas fa-file-pdf text-2xl text-red-500"></i>
                        <div>
                            <h4 class="font-semibold text-slate-800">Course Materials PDF</h4>
                            <p class="text-sm text-slate-500">PDF • 2.5 MB</p>
                        </div>
                    </div>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">Download</button>
                </div>
            </div>
            <div class="border border-slate-200 rounded-xl p-4 hover:bg-slate-50 transition">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <i class="fas fa-file-video text-2xl text-blue-500"></i>
                        <div>
                            <h4 class="font-semibold text-slate-800">Introduction Video</h4>
                            <p class="text-sm text-slate-500">Video • 45 min</p>
                        </div>
                    </div>
                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">Watch</button>
                </div>
            </div>
        `;
    }, 600);
}

function viewQuizzes(courseId, courseTitle) {
    document.getElementById('quizzesContent').innerHTML = `<div class="text-center py-10 text-slate-500">Loading quizzes for <strong>${courseTitle}</strong>...</div>`;
    document.getElementById('quizzesModal').classList.remove('hidden');

    setTimeout(() => {
        document.getElementById('quizzesContent').innerHTML = `
            <div class="border border-slate-200 rounded-xl p-4 hover:bg-slate-50 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-semibold text-slate-800">${courseTitle} Quiz</h4>
                        <div class="flex items-center gap-4 mt-2 text-sm text-slate-500">
                            <span><i class="fas fa-question-circle mr-1"></i>20 questions</span>
                            <span><i class="fas fa-clock mr-1"></i>30 min</span>
                            <span><i class="fas fa-calendar mr-1"></i>Due: Oct 15</span>
                        </div>
                    </div>
                    <a href="/students/enrolled-courses/${courseId}/quizzes" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">Start</a>
                </div>
            </div>
        `;
    }, 600);
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'materialsModal' || e.target.id === 'quizzesModal') {
        e.target.classList.add('hidden');
    }
});
</script>
@endsection
