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
                <span class="text-sm font-medium text-gray-700">Overall Progress</span>
                <span class="text-sm font-medium text-gray-700"><span id="progress-percent-text">0</span>%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 0%" id="progress-bar"></div>
            </div>
        </div>

        <!-- Questions Per Page Stats -->
        <div class="mb-4 flex justify-between items-center text-sm text-gray-600">
            <div>Questions per page: <span class="font-semibold">4</span></div>
            <div>Answered: <span id="answered-count">0</span> / {{ $questions->count() }}</div>
            <div>Skipped: <span id="skipped-count">0</span></div>
        </div>

        <!-- Question Form -->
        <form id="quiz-form" action="{{ route('quiz.submit', $quiz->id) }}" method="POST">
            @csrf
            
            @php
                $lockedQuestionIds = $questions->pluck('id')->values()->all();
                $questionsPerPage = 4;
                $pages = $questions->chunk($questionsPerPage);
                $totalPages = $pages->count();
            @endphp

            @foreach($lockedQuestionIds as $qid)
                <input type="hidden" name="locked_question_ids[]" value="{{ $qid }}" />
            @endforeach

            <!-- Pages -->
            @foreach($pages as $pageIndex => $pageQuestions)
            <div class="page-section bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 p-8 mb-6 @if($pageIndex !== 0) hidden @endif"
                 data-page-index="{{ $pageIndex }}" data-page-number="{{ $pageIndex + 1 }}">
                
                <div class="border-b pb-3 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Page {{ $pageIndex + 1 }} of {{ $totalPages }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Questions {{ $pageIndex * $questionsPerPage + 1 }} - {{ min(($pageIndex + 1) * $questionsPerPage, $questions->count()) }}</p>
                </div>
                
                @foreach($pageQuestions as $index => $question)
                @php
                    $globalIndex = $pageIndex * $questionsPerPage + $index;
                @endphp
                <div class="question-item mb-8 pb-6 border-b border-gray-200 dark:border-gray-700 last:border-b-0 last:pb-0"
                     data-question-id="{{ $question->id }}" data-global-index="{{ $globalIndex }}" data-page="{{ $pageIndex }}">
                    
                    <!-- Question Header -->
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 bg-slate-100 dark:bg-gray-800 text-slate-500 dark:text-gray-400 text-[10px] font-bold rounded-lg uppercase tracking-widest">Question {{ $globalIndex + 1 }}</span>
                        <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-[10px] font-bold rounded-lg uppercase tracking-widest">{{ $question->points }} Points</span>
                        <span class="question-status text-xs px-2 py-1 rounded-full ml-auto"></span>
                    </div>
                    
                    <!-- Question Text -->
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white leading-snug mb-4">
                        {{ $question->question_text }}
                    </h3>
                    
                    @if($question->type && $question->type !== 'MCQ')
                        <span class="inline-block mb-4 px-2 py-1 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 text-[10px] font-bold rounded uppercase tracking-widest border border-amber-100 dark:border-amber-800">
                            {{ $question->type }}
                        </span>
                    @endif

                    <!-- Options -->
                    <div class="space-y-4 mt-4">
                        @if($question->type === 'True/False')
                            <label class="option-item flex items-center p-6 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200 group">
                                <input type="radio" name="answers[{{ $question->id }}]" value="True" class="hidden">
                                <div class="w-6 h-6 border-2 border-gray-300 dark:border-gray-600 rounded-full flex items-center justify-center mr-4 group-hover:border-blue-500 transition-colors">
                                    <div class="w-3 h-3 bg-blue-600 rounded-full opacity-0 transition-opacity check-dot"></div>
                                </div>
                                <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">True</span>
                            </label>
                            
                            <label class="option-item flex items-center p-6 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200 group">
                                <input type="radio" name="answers[{{ $question->id }}]" value="False" class="hidden">
                                <div class="w-6 h-6 border-2 border-gray-300 dark:border-gray-600 rounded-full flex items-center justify-center mr-4 group-hover:border-blue-500 transition-colors">
                                    <div class="w-3 h-3 bg-blue-600 rounded-full opacity-0 transition-opacity check-dot"></div>
                                </div>
                                <span class="text-lg font-semibold text-gray-700 dark:text-gray-200">False</span>
                            </label>
                            
                        @elseif($question->type === 'Short Answer' || $question->type === 'Essay')
                            <div class="mt-2">
                                <textarea name="answers[{{ $question->id }}]"
                                          rows="4"
                                          class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm p-4 text-gray-700"
                                          placeholder="Type your answer here..."></textarea>
                                @if($question->type === 'Short Answer')
                                    <p class="text-xs text-gray-500 mt-2 italic">Note: Short answers are graded based on exact matches for automated marking.</p>
                                @endif
                            </div>
                        @else
                            <!-- MCQ Options -->
                            @foreach(['A', 'B', 'C', 'D'] as $letter)
                                @php $optionKey = 'option_' . strtolower($letter); @endphp
                                @if($question->$optionKey)
                                    <label class="option-item flex items-center p-6 border-2 border-gray-200 dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200 group">
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
                        <button type="button" class="skip-question text-sm text-yellow-600 hover:text-yellow-800 underline font-medium" 
                                data-question-id="{{ $question->id }}">
                            ⏭️ Skip this question
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach

            <!-- Navigation Buttons -->
            <div class="flex flex-wrap justify-between items-center gap-4 mt-6">
                <button type="button" id="prev-page-btn"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    ← Previous Page
                </button>

                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Page</span>
                    <select id="page-selector" class="border border-gray-300 dark:border-gray-600 rounded px-3 py-1 text-sm bg-white dark:bg-gray-800 dark:text-white">
                        @for($i = 1; $i <= $totalPages; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    <span class="text-sm text-gray-600 dark:text-gray-400">of {{ $totalPages }}</span>
                </div>

                <div class="flex gap-2">
                    <button type="button" id="next-page-btn"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Next Page →
                    </button>

                    <button type="submit" id="submit-btn"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Submit Quiz ✓
                    </button>
                </div>
            </div>
            
            <!-- Page Indicator Dots -->
            <div class="flex justify-center mt-4 gap-2">
                @for($i = 0; $i < $totalPages; $i++)
                    <div class="page-dot w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600 cursor-pointer transition-all duration-200" data-page="{{ $i }}"></div>
                @endfor
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Quiz Navigation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pages = document.querySelectorAll('.page-section');
    const prevPageBtn = document.getElementById('prev-page-btn');
    const nextPageBtn = document.getElementById('next-page-btn');
    const submitBtn = document.getElementById('submit-btn');
    const pageSelector = document.getElementById('page-selector');
    const pageDots = document.querySelectorAll('.page-dot');
    const progressBar = document.getElementById('progress-bar');
    const progressPercent = document.getElementById('progress-percent');
    const progressPercentText = document.getElementById('progress-percent-text');
    const answeredCountSpan = document.getElementById('answered-count');
    const skippedCountSpan = document.getElementById('skipped-count');
    const currentQuestionSpan = document.getElementById('current-question');
    const quizForm = document.getElementById('quiz-form');
    
    let currentPage = 0;
    const totalPages = pages.length;
    let skippedQuestions = new Set();
    
    // Store answers
    const answers = {};
    
    // Timer functionality
    const timeLimit = {{ $quiz->time_limit }} * 60;
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
        timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
        if (timeLeft < 300) {
            timerElement.classList.add('text-red-600', 'dark:text-red-400');
        }
    }
    
    function updateNavigation() {
        // Update page visibility
        pages.forEach((page, index) => {
            page.classList.toggle('hidden', index !== currentPage);
        });
        
        // Update page selector
        pageSelector.value = currentPage + 1;
        
        // Update dots
        pageDots.forEach((dot, index) => {
            if (index === currentPage) {
                dot.classList.add('bg-blue-600');
                dot.classList.remove('bg-gray-300', 'dark:bg-gray-600');
            } else {
                dot.classList.remove('bg-blue-600');
                dot.classList.add('bg-gray-300', 'dark:bg-gray-600');
            }
        });
        
        // Update buttons
        prevPageBtn.disabled = currentPage === 0;
        
        // Update current question number
        const currentPageQuestions = pages[currentPage].querySelectorAll('.question-item');
        if (currentPageQuestions.length > 0) {
            const firstQuestion = currentPageQuestions[0];
            const globalIndex = parseInt(firstQuestion.dataset.globalIndex);
            currentQuestionSpan.textContent = globalIndex + 1;
        }
        
        updateOverallProgress();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    function updateOverallProgress() {
        const totalQuestions = {{ $questions->count() }};
        const answeredCount = Object.keys(answers).filter(qId => answers[qId] !== undefined && answers[qId] !== '').length;
        const progress = totalQuestions > 0 ? (answeredCount / totalQuestions) * 100 : 0;
        
        progressBar.style.width = `${progress}%`;
        const roundedProgress = Math.round(progress);
        progressPercent.textContent = roundedProgress;
        if (progressPercentText) progressPercentText.textContent = roundedProgress;
        answeredCountSpan.textContent = answeredCount;
        skippedCountSpan.textContent = skippedQuestions.size;
        
        // Update question status indicators
        document.querySelectorAll('.question-item').forEach(item => {
            const questionId = item.dataset.questionId;
            const statusSpan = item.querySelector('.question-status');
            
            if (answers[questionId] && answers[questionId] !== '') {
                item.classList.add('answered');
                item.classList.remove('skipped');
                if (statusSpan) {
                    statusSpan.textContent = '✓ Answered';
                    statusSpan.className = 'question-status text-xs px-2 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400';
                }
                // Remove from skipped if answered
                if (skippedQuestions.has(questionId)) {
                    skippedQuestions.delete(questionId);
                }
            } else if (skippedQuestions.has(questionId)) {
                item.classList.add('skipped');
                item.classList.remove('answered');
                if (statusSpan) {
                    statusSpan.textContent = '⏭️ Skipped';
                    statusSpan.className = 'question-status text-xs px-2 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400';
                }
            } else {
                item.classList.remove('answered', 'skipped');
                if (statusSpan) {
                    statusSpan.textContent = '⚪ Not answered';
                    statusSpan.className = 'question-status text-xs px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400';
                }
            }
        });
    }
    
    function saveAnswer(questionId, value) {
        if (value === undefined || value === null || value === '') {
            delete answers[questionId];
        } else {
            answers[questionId] = value;
            // If answered, remove from skipped
            if (skippedQuestions.has(questionId)) {
                skippedQuestions.delete(questionId);
            }
        }
        
        updateOverallProgress();
        saveToLocalStorage();
    }
    
    function markAsSkipped(questionId) {
        // Only mark as skipped if not answered
        if (!answers[questionId] || answers[questionId] === '') {
            skippedQuestions.add(questionId);
            updateOverallProgress();
            saveToLocalStorage();
            showToast('Question skipped!', 'warning');
        }
    }
    
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        const colors = {
            warning: 'bg-yellow-500',
            info: 'bg-blue-500',
            success: 'bg-green-500'
        };
        toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg text-white z-50 transition-all duration-300 ${colors[type] || colors.info}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    function saveToLocalStorage() {
        try {
            localStorage.setItem(`quiz_${ {{ $quiz->id }} }_answers`, JSON.stringify(answers));
            localStorage.setItem(`quiz_${ {{ $quiz->id }} }_timeLeft`, timeLeft);
            localStorage.setItem(`quiz_${ {{ $quiz->id }} }_skipped`, JSON.stringify(Array.from(skippedQuestions)));
        } catch (e) {
            // Ignore storage errors
        }
    }
    
    function loadFromLocalStorage() {
        try {
            const savedAnswers = localStorage.getItem(`quiz_${ {{ $quiz->id }} }_answers`);
            const savedTimeLeft = localStorage.getItem(`quiz_${ {{ $quiz->id }} }_timeLeft`);
            const savedSkipped = localStorage.getItem(`quiz_${ {{ $quiz->id }} }_skipped`);
            
            if (savedAnswers) {
                const loadedAnswers = JSON.parse(savedAnswers);
                Object.assign(answers, loadedAnswers);
                
                // Restore radio selections
                Object.entries(loadedAnswers).forEach(([questionId, value]) => {
                    if (value && value !== '') {
                        const radioInput = document.querySelector(`input[type="radio"][name="answers[${questionId}]"][value="${value}"]`);
                        if (radioInput) {
                            radioInput.checked = true;
                            const parentLabel = radioInput.closest('.option-item');
                            if (parentLabel) {
                                parentLabel.classList.add('selected-option');
                            }
                        }
                    }
                });
            }
            
            if (savedSkipped) {
                const loadedSkipped = JSON.parse(savedSkipped);
                loadedSkipped.forEach(id => skippedQuestions.add(id));
            }
            
            if (savedTimeLeft) {
                const parsedTimeLeft = parseInt(savedTimeLeft);
                if (parsedTimeLeft > 0 && parsedTimeLeft < timeLimit) {
                    timeLeft = parsedTimeLeft;
                    updateTimerDisplay();
                }
            }
            
            updateOverallProgress();
        } catch (e) {
            // Ignore storage errors
        }
    }
    
    function clearLocalStorage() {
        try {
            localStorage.removeItem(`quiz_${ {{ $quiz->id }} }_answers`);
            localStorage.removeItem(`quiz_${ {{ $quiz->id }} }_timeLeft`);
            localStorage.removeItem(`quiz_${ {{ $quiz->id }} }_skipped`);
        } catch (e) {
            // Ignore storage errors
        }
    }
    
    function submitQuiz() {
        const totalQuestions = {{ $questions->count() }};
        const answeredCount = Object.keys(answers).filter(qId => answers[qId] && answers[qId] !== '').length;
        const skippedCount = skippedQuestions.size;
        
        let message = 'Are you sure you want to submit the quiz?';
        
        if (skippedCount > 0) {
            message += `\n\n⚠️ You have ${skippedCount} skipped question(s).`;
            message += `\n📝 Total answered: ${answeredCount} out of ${totalQuestions}`;
        } else if (answeredCount < totalQuestions) {
            message += `\n\n⚠️ You have ${totalQuestions - answeredCount} unanswered question(s).`;
        } else {
            message += `\n\n✅ You have answered all ${totalQuestions} questions!`;
        }
        
        message += '\n\nOnce submitted, you cannot change your answers.';
        
        if (confirm(message)) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Submitting...';
            clearLocalStorage();
            clearInterval(timerInterval);
            quizForm.submit();
        }
    }
    
    // Event Listeners
    nextPageBtn.addEventListener('click', () => {
        if (currentPage < totalPages - 1) {
            currentPage++;
            updateNavigation();
        }
    });
    
    prevPageBtn.addEventListener('click', () => {
        if (currentPage > 0) {
            currentPage--;
            updateNavigation();
        }
    });
    
    pageSelector.addEventListener('change', (e) => {
        currentPage = parseInt(e.target.value) - 1;
        updateNavigation();
    });
    
    pageDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentPage = index;
            updateNavigation();
        });
    });
    
    submitBtn.addEventListener('click', (e) => {
        e.preventDefault();
        submitQuiz();
    });
    
    // Radio button change events
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const match = this.name.match(/\[(\d+)\]/);
            if (match) {
                const questionId = match[1];
                saveAnswer(questionId, this.value);
                
                const optionItem = this.closest('.option-item');
                const container = optionItem.closest('.question-item');
                container.querySelectorAll('.option-item').forEach(item => {
                    item.classList.remove('selected-option');
                    item.style.borderColor = '';
                });
                optionItem.classList.add('selected-option');
                optionItem.style.borderColor = '#3b82f6';
            }
        });
    });
    
    // Textarea change events
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.addEventListener('input', function() {
            const match = this.name.match(/\[(\d+)\]/);
            if (match) {
                const questionId = match[1];
                saveAnswer(questionId, this.value);
            }
        });
    });
    
    // Skip question buttons
    document.querySelectorAll('.skip-question').forEach(button => {
        button.addEventListener('click', function() {
            const questionId = this.dataset.questionId;
            markAsSkipped(questionId);
            
            // Clear selection
            const questionElement = document.querySelector(`.question-item[data-question-id="${questionId}"]`);
            if (questionElement) {
                questionElement.querySelectorAll('input[type="radio"]').forEach(radio => {
                    radio.checked = false;
                });
                questionElement.querySelectorAll('.option-item').forEach(item => {
                    item.classList.remove('selected-option');
                    item.style.borderColor = '';
                });
                questionElement.querySelectorAll('textarea').forEach(textarea => {
                    textarea.value = '';
                });
            }
        });
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight' && e.shiftKey && currentPage < totalPages - 1) {
            e.preventDefault();
            nextPageBtn.click();
        }
        if (e.key === 'ArrowLeft' && e.shiftKey && currentPage > 0) {
            e.preventDefault();
            prevPageBtn.click();
        }
        if (e.key === 'Enter' && e.ctrlKey) {
            e.preventDefault();
            submitBtn.click();
        }
    });
    
    // Warn on page refresh
    window.addEventListener('beforeunload', (e) => {
        if (Object.keys(answers).length > 0 && !quizForm.dataset.submitted) {
            saveToLocalStorage();
            e.preventDefault();
            e.returnValue = 'You have unsaved answers. Are you sure you want to leave?';
            return e.returnValue;
        }
    });
    
    quizForm.addEventListener('submit', () => {
        quizForm.dataset.submitted = 'true';
        clearLocalStorage();
    });
    
    // Load saved answers and start
    loadFromLocalStorage();
    startTimer();
    updateNavigation();
});
</script>

<style>
.option-item {
    transition: all 0.2s ease;
    position: relative;
}

.option-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

.option-item input[type="radio"]:checked + .check-dot {
    opacity: 1;
}

.option-item.selected-option {
    border-color: #3b82f6 !important;
    background-color: #eff6ff !important;
}

.dark .option-item.selected-option {
    background-color: #1e293b !important;
    border-color: #60a5fa !important;
}

.question-item.answered {
    border-left: 4px solid #10b981;
    padding-left: 1.5rem;
}

.question-item.skipped {
    border-left: 4px solid #f59e0b;
    padding-left: 1.5rem;
    background-color: #fefce8;
}

.dark .question-item.skipped {
    background-color: #1e293b;
}

.page-dot {
    transition: all 0.2s ease;
}

.page-dot:hover {
    transform: scale(1.3);
}

#timer.blinking {
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.question-item {
    transition: all 0.3s ease;
}

.question-item.highlight-pulse {
    animation: pulse 0.5s ease-in-out 3;
}

@keyframes pulse {
    0%, 100% { background-color: transparent; }
    50% { background-color: #fef3c7; }
}
</style>
@endsection