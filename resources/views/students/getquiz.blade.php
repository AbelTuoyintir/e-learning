@extends('layouts.student')   {{-- your normal student shell --}}
@section('title', $course->title.' – Quizzes')

@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('students.enrolledcourses') }}">My Courses</a></li>
        <li class="breadcrumb-item active">{{ $course->title }}</li>
      </ol>
    </nav>

    {{-- Page header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Quizzes</h2>
            <p class="text-muted mb-0">Pick a quiz below to start or continue your attempt.</p>
        </div>
        <div class="badge bg-primary fs-6">{{ $quizzes->count() }} quiz{{ $quizzes->count() == 1 ? '' : 'zes' }}</div>
    </div>

    {{-- Quiz cards --}}
    <div class="row g-3">
        @forelse($quizzes as $quiz)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm hover-shadow">
                    <div class="card-body d-flex flex-column">
                        {{-- Title & meta --}}
                        <h5 class="card-title mb-1">{{ $quiz->title }}</h5>
                        <p class="text-muted small mb-2">
                            {{ $quiz->questions_count }} question{{ $quiz->questions_count == 1 ? '' : 's' }}
                            • {{ $quiz->duration }} min
                        </p>

                        {{-- Due date badge --}}
                        @if($quiz->due_at)
                            <p class="mb-2">
                                @if(now()->isAfter($quiz->due_at))
                                    <span class="badge bg-danger">Overdue</span>
                                @else
                                    <span class="badge bg-warning text-dark">Due {{ $quiz->due_at->diffForHumans() }}</span>
                                @endif
                            </p>
                        @endif

                        {{-- Attempt status --}}
                        @if($quiz->attempts_count)
                            <p class="text-success small mb-3">✓ You have already attempted this quiz.</p>
                        @endif

                        {{-- Action button --}}
                        <div class="mt-auto">
                            <a href="{{ route('quiz.start', $quiz) }}"
                               class="btn btn-primary btn-sm w-100">
                                @if($quiz->attempts_count)
                                    Review / Re-attempt
                                @else
                                    Start Quiz
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No quizzes have been published for this course yet.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection

{{-- Tiny bit of extra styling --}}
@push('styles')
<style>
    .hover-shadow{transition:.25s}
    .hover-shadow:hover{box-shadow:0 .5rem 1rem rgba(0,0,0,.15)!important}
</style>
@endpush
