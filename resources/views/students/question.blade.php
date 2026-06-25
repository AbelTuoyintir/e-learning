@extends('layouts.studentNavBar')

@section('title', 'Assessment: ' . $quiz->title)

@section('content')
<div class="min-h-screen bg-slate-50 dark:bg-gray-950 pb-20 transition-colors duration-300">
    <!-- Sticky Quiz Header -->
    <div class="bg-white dark:bg-gray-900 border-b dark:border-gray-800 shadow-sm sticky top-[72px] z-30">
        <div class="container mx-auto px-6 py-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $quiz->title }}</h1>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest">Question <span id="current-question">1</span> of {{ $quiz->questions_count }}</span>
                        <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ $quiz->module->title ?? 'Module Assessment' }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <!-- Timer -->
                    <div class="flex items-center bg-gray-50 dark:bg-gray-800 px-4 py-2 rounded-xl border dark:border-gray-700" id="timer-container">
                        <i class="fas fa-clock text-gray-400 mr-3"></i>
                        <span class="text-xl font-mono font-bold text-gray-700 dark:text-gray-200" id="timer">
                            @php
                                $minutes = floor($quiz->time_limit);
                                $seconds = 0;
                            @endphp
                            {{ $minutes }}:{{ str_pad($seconds, 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>

                    <!-- Progress Circle -->
                    <div class="relative w-12 h-12">
                        <svg class="w-full h-full transform -rotate-90">
                            <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="4" fill="transparent" class="text-gray-200 dark:text-gray-700" />
                            <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="4" fill="transparent" stroke-dasharray="125.6" stroke-dashoffset="125.6" class="text-blue-600 transition-all duration-500" id="progress-circle" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center text-[10px] font-bold dark:text-white"><span id="progress-percent">0</span>%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 max-w-4xl mt-8">

    <!-- Progress Bar -->
    <div class="mb-6">
        <div class="flex justify-between mb-1">
            <span class="text-sm font-medium text-gray-700">Progress</span>
            <span class="text-sm font-medium text-gray-700"><span id="progress-percent">0</span>%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5">
            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 0%" id="progress-bar"></div>
        </div>
    </div>

    <!-- Question Form -->
    <form id="quiz-form" action="{{ route('quiz.submit', $quiz->id) }}" method="POST">
        @csrf
        
        @php
            $lockedQuestionIds = $questions->pluck('id')->values()->all();
        @endphp

        {{-- Lock the chosen question set for this attempt so refresh/submit is consistent --}}
        @foreach($lockedQuestionIds as $qid)
            <input type="hidden" name="locked_question_ids[]" value="{{ $qid }}" />
        @endforeach

        @foreach($questions as $index => $question)
        <div class="question-section bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 p-8 mb-6 @if($index !== 0) hidden @endif"
             data-question-id="{{ $question->id }}" data-index="{{ $index + 1 }}">

            <!-- Question -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-3 py-1 bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-gray-400 text-[10px] font-bold rounded-lg uppercase tracking-widest">Question {{ $index + 1 }}</span>
                    <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-[10px] font-bold rounded-lg uppercase tracking-widest">{{ $question->points }} Points</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white leading-snug">
                    {{ $question->question_text }}
                </h3>
                @if($question->type && $question->type !== 'MCQ')
                    <span class="inline-block mt-3 px-2 py-1 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 text-[10px] font-bold rounded uppercase tracking-widest border border-amber-100 dark:border-amber-800">
                        {{ $question->type }}
                    </span>
                @endif
            </div>

            <!-- Options -->
            <div class="space-y-4">
                @if($question->type === 'True/False')
                    <label class="option-item flex items-center p-6 border-2 border-gray-100 dark:border-gray-800 rounded-2xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200 group">
                        <div class="w-6 h-6 border-2 border-gray-300 dark:border-gray-600 rounded-full flex items-center justify-center mr-4 group-hover:border-blue-500 transition-colors">
                            <div class="w-3 h-3 bg-blue-600 rounded-full opacity-0 transition-opacity check-dot"></div>
                        </div>
                        <input type="radio" name="answers[{{ $question->id }}]" value="A" class="hidden">
                        <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">True</span>
                    </label>

                    <label class="option-item flex items-center p-6 border-2 border-gray-100 dark:border-gray-800 rounded-2xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200 group">
                        <div class="w-6 h-6 border-2 border-gray-300 dark:border-gray-600 rounded-full flex items-center justify-center mr-4 group-hover:border-blue-500 transition-colors">
                            <div class="w-3 h-3 bg-blue-600 rounded-full opacity-0 transition-opacity check-dot"></div>
                        </div>
                        <input type="radio" name="answers[{{ $question->id }}]" value="B" class="hidden">
                        <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">False</span>
                    </label>
                @elseif($question->type === 'Short Answer' || $question->type === 'Essay')
                    <div class="mt-2">
                        <textarea name="answers[{{ $question->id }}]"
                                  rows="4"
                                  class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm p-4 text-gray-700"
                                  placeholder="Type your answer here..."
                                  oninput="saveAnswer('{{ $question->id }}', this.value)"></textarea>
                        @if($question->type === 'Short Answer')
                            <p class="text-xs text-gray-500 mt-2 italic">Note: Short answers are graded based on exact matches for automated marking.</p>
                        @endif
                    </div>
                @else
                    @foreach(['A', 'B', 'C', 'D'] as $letter)
                        @php $optionKey = 'option_' . strtolower($letter); @endphp
                        @if($question->$optionKey)
                            <label class="option-item flex items-center p-6 border-2 border-gray-100 dark:border-gray-800 rounded-2xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200 group">
                                <div class="w-10 h-10 bg-slate-100 dark:bg-gray-800 rounded-xl flex items-center justify-center text-slate-500 dark:text-gray-400 font-bold mr-4 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                    {{ $letter }}
                                </div>
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $letter }}" class="hidden">
                                <span class="text-lg text-gray-700 dark:text-gray-200 leading-tight">{{ $question->$optionKey }}</span>
                            </label>
                        @endif
                    @endforeach
                @endif
            </div>
            
            <!-- Skip Question Button -->
            <div class="mt-4 text-right">
                <button type="button" class="skip-question text-sm text-gray-500 hover:text-gray-700 underline" 
                        data-question-id="{{ $question->id }}">
                    Skip this question
                </button>
            </div>
        </div>
        @endforeach

        <!-- Navigation Buttons -->
        <div class="flex justify-between mt-6">
            <button type="button" id="prev-btn"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    disabled>
                ← Previous
            </button>

            <button type="button" id="next-btn"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                Next Question →
            </button>

            <button type="submit" id="submit-btn"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg hidden transition-colors">
                Submit Quiz ✓
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
    const progressPercent = document.getElementById('progress-percent');
    const quizForm = document.getElementById('quiz-form');
    let currentQuestion = 0;
    
    // Store answers to prevent data loss
    const answers = {};
    
    // Timer functionality
    const timeLimit = {{ $quiz->time_limit }} * 60; // Convert to seconds
    let timeLeft = timeLimit;
    let timerInterval;
    
    function startTimer() {
        updateTimerDisplay();
        
        timerInterval = setInterval(() => {
            timeLeft--;
            updateTimerDisplay();
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                submitQuiz();
            }
        }, 1000);
    }
    
    function updateTimerDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        const timerElement = document.getElementById('timer');
        timerElement.textContent = `Time: ${minutes}:${seconds.toString().padStart(2, '0')}`;
            
        // Change color when time is running low
        if (timeLeft < 300) { // Less than 5 minutes
            timerElement.classList.add('text-red-600');
            timerElement.classList.remove('text-gray-700');
        }
        
        // Blink when less than 1 minute
        if (timeLeft < 60) {
            timerElement.classList.toggle('text-red-600', Math.floor(Date.now() / 500) % 2 === 0);
        }
    }
    
    function updateNavigation() {
        // Update progress bar
        const progress = ((currentQuestion + 1) / questions.length) * 100;
        const dashOffset = 125.6 - (125.6 * progress / 100);
        document.getElementById('progress-circle').style.strokeDashoffset = dashOffset;
        progressPercent.textContent = Math.round(progress);
        
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
        
        // Restore saved answer for current question if exists
        const currentQuestionId = questions[currentQuestion].dataset.questionId;
        if (answers[currentQuestionId]) {
            const radioInput = questions[currentQuestion].querySelector(
                `input[type="radio"][value="${answers[currentQuestionId]}"]`
            );
            if (radioInput) {
                radioInput.checked = true;
                // Highlight the selected option
                const parentLabel = radioInput.closest('.option-item');
                if (parentLabel) {
                    parentLabel.classList.add('selected-option');
                }
            }
        }
        
        // Highlight answered questions in progress
        updateProgressIndicator();
    }
    
    function updateProgressIndicator() {
        questions.forEach((q, index) => {
            const questionId = q.dataset.questionId;
            const questionNumber = index + 1;
            // You could add visual indicators for answered questions here
        });
    }
    
    function saveAnswer(questionId, value) {
        answers[questionId] = value;
        
        // Update UI to show answered status
        const questionElement = document.querySelector(`[data-question-id="${questionId}"]`);
        if (questionElement) {
            questionElement.classList.add('answered');
            
            // Update the progress indicator
            updateProgressIndicator();
        }
    }
    
    function submitQuiz() {
        // Fill in all skipped questions with empty value
        questions.forEach(q => {
            const questionId = q.dataset.questionId;
            const inputName = `answers[${questionId}]`;
            const existingInput = quizForm.querySelector(`input[name="${inputName}"]:checked`);
            
            if (!existingInput && !answers[questionId]) {
                // Create a hidden input for skipped questions
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = inputName;
                hiddenInput.value = '';
                quizForm.appendChild(hiddenInput);
            }
        });
        
        // Show confirmation dialog
        const answeredCount = Object.keys(answers).length;
        const totalQuestions = questions.length;
        const unansweredCount = totalQuestions - answeredCount;
        
        let message = 'Are you sure you want to submit the quiz?';
        
        if (unansweredCount > 0) {
            message += `\n\nYou have answered ${answeredCount} out of ${totalQuestions} questions.`;
            message += `\n${unansweredCount} question(s) remain unanswered.`;
        } else {
            message += `\n\nYou have answered all ${totalQuestions} questions.`;
        }
        
        message += '\n\nOnce submitted, you cannot change your answers.';
        
        if (confirm(message)) {
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Submitting...';
            
            // Submit the form
            quizForm.submit();
        }
    }
    
    // Event Listeners
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
    
    submitBtn.addEventListener('click', (e) => {
        e.preventDefault();
        submitQuiz();
    });
    
    // Radio button change events
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const questionId = this.name.match(/\[(\d+)\]/)[1];
            saveAnswer(questionId, this.value);
            
            // Highlight selected option
            document.querySelectorAll('.option-item').forEach(item => {
                item.classList.remove('selected-option');
            });
            this.closest('.option-item').classList.add('selected-option');
        });
    });
    
    // Skip question buttons
    document.querySelectorAll('.skip-question').forEach(button => {
        button.addEventListener('click', function() {
            const questionId = this.dataset.questionId;
            saveAnswer(questionId, '');
            
            // Clear any selection for this question
            const questionElement = document.querySelector(`[data-question-id="${questionId}"]`);
            if (questionElement) {
                questionElement.querySelectorAll('input[type="radio"]').forEach(radio => {
                    radio.checked = false;
                });
                questionElement.querySelectorAll('.option-item').forEach(item => {
                    item.classList.remove('selected-option');
                });
            }
            
            // Auto-advance to next question if not last
            if (currentQuestion < questions.length - 1) {
                currentQuestion++;
                updateNavigation();
            }
        });
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight' && !nextBtn.classList.contains('hidden')) {
            nextBtn.click();
        }
        if (e.key === 'ArrowLeft' && !prevBtn.disabled) {
            prevBtn.click();
        }
        if (e.key === 'Enter' && e.ctrlKey) {
            submitBtn.click();
        }
        // Number keys 1-4 for selecting answers
        if (e.key >= '1' && e.key <= '4' && !e.ctrlKey && !e.altKey && !e.metaKey) {
            const optionLetters = ['A', 'B', 'C', 'D'];
            const letter = optionLetters[parseInt(e.key) - 1];
            const currentQuestionElement = questions[currentQuestion];
            const radioInput = currentQuestionElement.querySelector(
                `input[type="radio"][value="${letter}"]`
            );
            if (radioInput) {
                radioInput.checked = true;
                radioInput.dispatchEvent(new Event('change'));
            }
        }
    });
    
    // Form submit prevention on accidental refresh/close
    let hasAnswers = false;
    window.addEventListener('beforeunload', (e) => {
        if (Object.keys(answers).length > 0 && !quizForm.submitted) {
            e.preventDefault();
            e.returnValue = 'You have unsaved answers. Are you sure you want to leave?';
            return e.returnValue;
        }
    });
    
    // Mark form as submitted when submitting
    quizForm.addEventListener('submit', () => {
        quizForm.submitted = true;
    });
    
    // Initialize
    startTimer();
    updateNavigation();
});
</script>

<style>
.option-item {
    transition: all 0.2s ease;
}

.option-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.option-item input[type="radio"]:checked + div {
    font-weight: bold;
    color: #2563eb;
}

.option-item input[type="radio"]:checked {
    border-color: #2563eb;
}

.selected-option {
    border-color: #2563eb !important;
    background-color: #eff6ff !important;
}

.question-section.answered {
    border-left: 4px solid #10b981;
}

#timer.blinking {
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>
@endsection