@extends('layouts.app')

@section('title', 'Taking Quiz: ' . $quiz->title)

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <!-- Quiz Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-800">{{ $quiz->title }}</h1>
            <div class="text-right">
                <div class="text-lg font-semibold text-gray-700" id="timer">
                    Time: {{ $quiz->time_limit }}:00
                </div>
                <div class="text-sm text-gray-500">
                    Question <span id="current-question">1</span> of {{ $quiz->questions_count }}
                </div>
            </div>
        </div>
        <p class="text-gray-600">{{ $quiz->description }}</p>
    </div>

    <!-- Progress Bar -->
    <div class="mb-6">
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 0%" id="progress-bar"></div>
        </div>
    </div>

    <!-- Question Form -->
    <form id="quiz-form" action="{{ route('quiz.submit', $quiz->id) }}" method="POST">
        @csrf
        {{-- @php
            $questions = $quiz->questions->correct_answer()->first();
            dd($questions);
        @endphp --}}
        @foreach($questions as $index => $question)
        <div class="question-section bg-white rounded-lg shadow-md p-6 mb-6 @if($index !== 0) hidden @endif"
             data-question-id="{{ $question->id }}" data-index="{{ $index + 1 }}">

            <!-- Question -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    Question {{ $index + 1 }}:
                </h3>
                <p class="text-gray-700 text-lg">{{ $question->question_text }}</p>
                <span class="text-sm text-gray-500">Points: {{ $question->points }}</span>
                <input type="hidden" name="correct_answer" value="{{ $question->{$question->correct_option} }}">
            </div>

            <!-- Options -->
            <div class="space-y-3">
                <label class="option-item flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $question->{'option_a'} }}"
                           class="mr-3 h-5 w-5 text-blue-600">
                    <span class="text-gray-700">A: {{ $question->{'option_a'} }}</span>
                </label>

                <label class="option-item flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $question->{'option_b'} }}"
                           class="mr-3 h-5 w-5 text-blue-600">
                    <span class="text-gray-700">B: {{ $question->{'option_b'} }}</span>
                </label>

                <label class="option-item flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $question->{'option_c'} }}"
                           class="mr-3 h-5 w-5 text-blue-600">
                    <span class="text-gray-700">C: {{ $question->{'option_c'} }}</span>
                </label>

                <label class="option-item flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $question->{'option_d'} }}"
                           class="mr-3 h-5 w-5 text-blue-600">
                    <span class="text-gray-700">D: {{ $question->{'option_d'} }}</span>
                </label>
            </div>
        </div>
        @endforeach

        <!-- Navigation Buttons -->
        <div class="flex justify-between mt-6">
            <button type="button" id="prev-btn"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                Previous
            </button>

            <button type="button" id="next-btn"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                Next Question
            </button>

            <button type="submit" id="submit-btn"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg hidden">
                Submit Quiz
            </button>
        </div>
    </form>
</div>

<!-- JavaScript for Quiz Navigation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const questions = document.querySelectorAll('.question-section');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');
    const currentQuestionSpan = document.getElementById('current-question');
    const progressBar = document.getElementById('progress-bar');
    let currentQuestion = 0;

    // Timer functionality
    const timeLimit = {{ $quiz->time_limit }} * 60; // Convert to seconds
    let timeLeft = timeLimit;
    const timer = setInterval(() => {
        timeLeft--;
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        document.getElementById('timer').textContent = `Time: ${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (timeLeft <= 0) {
            clearInterval(timer);
            document.getElementById('quiz-form').submit();
        }
    }, 1000);

    function updateNavigation() {
        // Update progress bar
        const progress = ((currentQuestion + 1) / questions.length) * 100;
        progressBar.style.width = `${progress}%`;

        // Update question counter
        currentQuestionSpan.textContent = currentQuestion + 1;

        // Show/hide buttons
        prevBtn.disabled = currentQuestion === 0;
        nextBtn.classList.toggle('hidden', currentQuestion === questions.length - 1);
        submitBtn.classList.toggle('hidden', currentQuestion !== questions.length - 1);

        // Show current question, hide others
        questions.forEach((q, index) => {
            q.classList.toggle('hidden', index !== currentQuestion);
        });
    }

    nextBtn.addEventListener('click', () => {
        if (currentQuestion < questions.length - 1) {
            currentQuestion++;
            updateNavigation();
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentQuestion > 0) {
            currentQuestion--;
            updateNavigation();
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') nextBtn.click();
        if (e.key === 'ArrowLeft') prevBtn.click();
    });

    // Initialize
    updateNavigation();
});
</script>

<style>
.option-item input[type="radio"]:checked + span {
    font-weight: bold;
    color: #2563eb;
}
.option-item input[type="radio"]:checked {
    border-color: #2563eb;
}
</style>
@endsection
