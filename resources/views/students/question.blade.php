@extends('layouts.studentNavBar')

@section('title', 'Taking Quiz: ' . $quiz->title)

@section('content')
<div class="container mx-auto p-6 max-w-7xl dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    
    <!-- Two Column Layout -->
    <div class="flex flex-col lg:flex-row gap-6">
        
        <!-- Main Quiz Content Column -->
        <div class="flex-1 lg:w-3/4">
            <!-- Quiz Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $quiz->title }}</h1>
                    <div class="text-right">
                        <div class="text-lg font-semibold text-gray-700" id="timer">
                            @php
                                $minutes = floor($quiz->time_limit);
                                $seconds = 0;
                            @endphp
                            Time: {{ $minutes }}:{{ str_pad($seconds, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <div class="text-sm text-gray-500">
                            Page <span id="current-page">1</span> of <span id="total-pages"></span>
                            ({{ $questions->count() }} questions total)
                        </div>
                    </div>
                </div>
                <p class="text-gray-600">{{ $quiz->description }}</p>
            </div>

            <!-- Progress Bar -->
            <div class="mb-6">
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium text-gray-700">Overall Progress</span>
                    <span class="text-sm font-medium text-gray-700"><span id="progress-percent">0</span>%</span>
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
                @endphp

                @foreach($lockedQuestionIds as $qid)
                    <input type="hidden" name="locked_question_ids[]" value="{{ $qid }}" />
                @endforeach

                @php
                    $questionsPerPage = 4;
                    $pages = $questions->chunk($questionsPerPage);
                    $totalPages = $pages->count();
                @endphp

                @foreach($pages as $pageIndex => $pageQuestions)
                <div class="page-section bg-white rounded-lg shadow-md p-6 mb-6 @if($pageIndex !== 0) hidden @endif"
                     data-page-index="{{ $pageIndex }}" data-page-number="{{ $pageIndex + 1 }}">
                    
                    <div class="border-b pb-3 mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Page {{ $pageIndex + 1 }} of {{ $totalPages }}</h3>
                        <p class="text-sm text-gray-500">Questions {{ $pageIndex * $questionsPerPage + 1 }} - {{ min(($pageIndex + 1) * $questionsPerPage, $questions->count()) }}</p>
                    </div>
                    
                    @foreach($pageQuestions as $index => $question)
                    @php
                        $globalIndex = $pageIndex * $questionsPerPage + $index;
                    @endphp
                    <div class="question-item mb-8 pb-6 border-b border-gray-200 last:border-b-0 last:pb-0"
                         data-question-id="{{ $question->id }}" data-global-index="{{ $globalIndex }}" data-page="{{ $pageIndex }}">
                        
                        <!-- Question -->
                        <div class="mb-4">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-md font-semibold text-gray-800">
                                    Question {{ $globalIndex + 1 }}:
                                </h4>
                                <span class="question-status text-xs px-2 py-1 rounded-full"></span>
                            </div>
                            <p class="text-gray-700">{{ $question->question_text }}</p>
                            <span class="text-sm text-gray-500">Points: {{ $question->points }}</span>
                        </div>

                        <!-- Options -->
                        <div class="space-y-3 ml-4">
                            <label class="option-item flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors hover:border-blue-300">
                                <input type="radio" name="answers[{{ $question->id }}]" value="A"
                                       class="mr-3 h-5 w-5 text-blue-600">
                                <div>
                                    <span class="font-medium text-gray-700">A:</span>
                                    <span class="text-gray-700 ml-2">{{ $question->option_a }}</span>
                                </div>
                            </label>

                            <label class="option-item flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors hover:border-blue-300">
                                <input type="radio" name="answers[{{ $question->id }}]" value="B"
                                       class="mr-3 h-5 w-5 text-blue-600">
                                <div>
                                    <span class="font-medium text-gray-700">B:</span>
                                    <span class="text-gray-700 ml-2">{{ $question->option_b }}</span>
                                </div>
                            </label>

                            <label class="option-item flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors hover:border-blue-300">
                                <input type="radio" name="answers[{{ $question->id }}]" value="C"
                                       class="mr-3 h-5 w-5 text-blue-600">
                                <div>
                                    <span class="font-medium text-gray-700">C:</span>
                                    <span class="text-gray-700 ml-2">{{ $question->option_c }}</span>
                                </div>
                            </label>

                            <label class="option-item flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors hover:border-blue-300">
                                <input type="radio" name="answers[{{ $question->id }}]" value="D"
                                       class="mr-3 h-5 w-5 text-blue-600">
                                <div>
                                    <span class="font-medium text-gray-700">D:</span>
                                    <span class="text-gray-700 ml-2">{{ $question->option_d }}</span>
                                </div>
                            </label>
                        </div>
                        
                        <!-- Skip Question Button -->
                        <div class="mt-3 text-right">
                            <button type="button" class="skip-question text-sm text-red-500 hover:text-red-700 underline font-medium" 
                                    data-question-id="{{ $question->id }}">
                                ⏭️ Skip this question
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach

                <!-- Page Navigation Buttons -->
                <div class="flex justify-between mt-6">
                    <button type="button" id="prev-page-btn"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        ← Previous Page
                    </button>

                    <div class="flex gap-2">
                        <span class="text-sm text-gray-600 self-center">Page</span>
                        <select id="page-selector" class="border border-gray-300 rounded px-3 py-1 text-sm bg-white dark:bg-gray-800 dark:border-gray-600">
                            @for($i = 1; $i <= $totalPages; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <span class="text-sm text-gray-600 self-center">of {{ $totalPages }}</span>
                    </div>

                    <button type="button" id="next-page-btn"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Next Page →
                    </button>

                    <button type="submit" id="submit-btn"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Submit Quiz ✓
                    </button>
                </div>
                
                <!-- Page Indicator Dots -->
                <div class="flex justify-center mt-4 gap-2">
                    @for($i = 0; $i < $totalPages; $i++)
                        <div class="page-dot w-2 h-2 rounded-full bg-gray-300 cursor-pointer transition-all duration-200" data-page="{{ $i }}"></div>
                    @endfor
                </div>
            </form>
        </div>

        <!-- Skipped Questions Sidebar Column -->
        <div class="lg:w-1/4">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                <div class="flex items-center justify-between mb-4 pb-3 border-b">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="text-yellow-500 mr-2">⏭️</span> 
                        Skipped Questions
                    </h3>
                    <span id="skipped-sidebar-count" class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded-full">0</span>
                </div>
                
                <div id="skipped-questions-container" class="space-y-2 max-h-[calc(100vh-200px)] overflow-y-auto">
                    <div id="no-skipped-message" class="text-center text-gray-400 py-8">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>No skipped questions</p>
                        <p class="text-xs mt-1">Questions you skip will appear here</p>
                    </div>
                </div>
                
                <div class="mt-4 pt-3 border-t">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Quick Stats:</span>
                        <span><span id="sidebar-answered-count">0</span>/{{ $questions->count() }} answered</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div id="sidebar-progress-bar" class="bg-green-500 h-1.5 rounded-full" style="width: 0%"></div>
                    </div>
                    <button type="button" id="review-all-skipped-btn" class="mt-3 w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-lg text-sm font-medium transition-colors hidden">
                        Review All Skipped Questions
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Quiz Navigation with Skipped Questions Sidebar -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pages = document.querySelectorAll('.page-section');
    const prevPageBtn = document.getElementById('prev-page-btn');
    const nextPageBtn = document.getElementById('next-page-btn');
    const submitBtn = document.getElementById('submit-btn');
    const pageSelector = document.getElementById('page-selector');
    const pageDots = document.querySelectorAll('.page-dot');
    const currentPageSpan = document.getElementById('current-page');
    const totalPagesSpan = document.getElementById('total-pages');
    const progressBar = document.getElementById('progress-bar');
    const progressPercent = document.getElementById('progress-percent');
    const answeredCountSpan = document.getElementById('answered-count');
    const skippedCountSpan = document.getElementById('skipped-count');
    const skippedSidebarCountSpan = document.getElementById('skipped-sidebar-count');
    const sidebarAnsweredCountSpan = document.getElementById('sidebar-answered-count');
    const sidebarProgressBar = document.getElementById('sidebar-progress-bar');
    const skippedContainer = document.getElementById('skipped-questions-container');
    const noSkippedMessage = document.getElementById('no-skipped-message');
    const reviewAllBtn = document.getElementById('review-all-skipped-btn');
    const quizForm = document.getElementById('quiz-form');
    
    let currentPage = 0;
    const totalPages = pages.length;
    let skippedQuestions = new Set(); // Track skipped question IDs
    
    totalPagesSpan.textContent = totalPages;
    
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
        timerElement.textContent = `Time: ${minutes}:${seconds.toString().padStart(2, '0')}`;
            
        if (timeLeft < 300) {
            timerElement.classList.add('text-red-600');
            timerElement.classList.remove('text-gray-700');
        }
        
        if (timeLeft < 60) {
            timerElement.classList.toggle('text-red-600', Math.floor(Date.now() / 500) % 2 === 0);
        }
    }
    
    function updateNavigation() {
        currentPageSpan.textContent = currentPage + 1;
        pageSelector.value = currentPage + 1;
        
        pageDots.forEach((dot, index) => {
            if (index === currentPage) {
                dot.classList.add('bg-blue-600');
                dot.classList.remove('bg-gray-300');
            } else {
                dot.classList.remove('bg-blue-600');
                dot.classList.add('bg-gray-300');
            }
        });
        
        prevPageBtn.disabled = currentPage === 0;
        
        pages.forEach((page, index) => {
            page.classList.toggle('hidden', index !== currentPage);
        });
        
        updateOverallProgress();
    }
    
    function updateOverallProgress() {
        const totalQuestions = {{ $questions->count() }};
        const answeredCount = Object.keys(answers).filter(qId => answers[qId] !== undefined && answers[qId] !== '').length;
        const progress = (answeredCount / totalQuestions) * 100;
        
        progressBar.style.width = `${progress}%`;
        progressPercent.textContent = Math.round(progress);
        answeredCountSpan.textContent = answeredCount;
        sidebarAnsweredCountSpan.textContent = answeredCount;
        sidebarProgressBar.style.width = `${progress}%`;
        
        // Update question status indicators
        document.querySelectorAll('.question-item').forEach(item => {
            const questionId = item.dataset.questionId;
            const statusSpan = item.querySelector('.question-status');
            
            if (answers[questionId] && answers[questionId] !== '') {
                item.classList.add('answered');
                item.classList.remove('skipped');
                if (statusSpan) {
                    statusSpan.textContent = '✓ Answered';
                    statusSpan.classList.remove('bg-yellow-100', 'text-yellow-800', 'bg-red-100', 'text-red-800');
                    statusSpan.classList.add('bg-green-100', 'text-green-800');
                }
                // Remove from skipped set if it was there
                if (skippedQuestions.has(questionId)) {
                    skippedQuestions.delete(questionId);
                }
            } else if (skippedQuestions.has(questionId)) {
                item.classList.add('skipped');
                item.classList.remove('answered');
                if (statusSpan) {
                    statusSpan.textContent = '⏭️ Skipped';
                    statusSpan.classList.remove('bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');
                    statusSpan.classList.add('bg-yellow-100', 'text-yellow-800');
                }
            } else {
                item.classList.remove('answered', 'skipped');
                if (statusSpan) {
                    statusSpan.textContent = '⚪ Not answered';
                    statusSpan.classList.remove('bg-green-100', 'text-green-800', 'bg-yellow-100', 'text-yellow-800');
                    statusSpan.classList.add('bg-gray-100', 'text-gray-600');
                }
            }
        });
        
        updateSkippedSidebar();
    }
    
    function updateSkippedSidebar() {
        const skippedCount = skippedQuestions.size;
        skippedCountSpan.textContent = skippedCount;
        skippedSidebarCountSpan.textContent = skippedCount;
        
        // Clear container but keep no-skipped message
        const itemsToRemove = skippedContainer.querySelectorAll('.skipped-question-item:not(#no-skipped-message)');
        itemsToRemove.forEach(item => item.remove());
        
        if (skippedCount === 0) {
            noSkippedMessage.classList.remove('hidden');
            reviewAllBtn.classList.add('hidden');
        } else {
            noSkippedMessage.classList.add('hidden');
            reviewAllBtn.classList.remove('hidden');
            
            // Get all question elements
            const allQuestions = document.querySelectorAll('.question-item');
            const skippedQuestionsArray = Array.from(skippedQuestions);
            
            // Sort skipped questions by global index
            skippedQuestionsArray.sort((a, b) => {
                const qA = document.querySelector(`.question-item[data-question-id="${a}"]`);
                const qB = document.querySelector(`.question-item[data-question-id="${b}"]`);
                const indexA = qA ? parseInt(qA.dataset.globalIndex) : 0;
                const indexB = qB ? parseInt(qB.dataset.globalIndex) : 0;
                return indexA - indexB;
            });
            
            skippedQuestionsArray.forEach(questionId => {
                const questionElement = document.querySelector(`.question-item[data-question-id="${questionId}"]`);
                if (questionElement) {
                    const globalIndex = parseInt(questionElement.dataset.globalIndex);
                    const questionText = questionElement.querySelector('p')?.textContent.substring(0, 60) || 'Question';
                    const pageNumber = parseInt(questionElement.dataset.page);
                    
                    const skippedItem = document.createElement('div');
                    skippedItem.className = 'skipped-question-item p-3 bg-yellow-50 border border-yellow-200 rounded-lg cursor-pointer hover:bg-yellow-100 transition-colors';
                    skippedItem.setAttribute('data-question-id', questionId);
                    skippedItem.setAttribute('data-page', pageNumber);
                    
                    skippedItem.innerHTML = `
                        <div class="flex justify-between items-start mb-1">
                            <span class="font-semibold text-sm text-gray-700">Question ${globalIndex + 1}</span>
                            <span class="text-xs text-yellow-600 bg-yellow-200 px-2 py-0.5 rounded-full">Skipped</span>
                        </div>
                        <p class="text-sm text-gray-600 line-clamp-2">${this.escapeHtml(questionText)}...</p>
                        <div class="flex items-center mt-2 text-xs text-yellow-600">
                            <span>📍 Page ${pageNumber + 1}</span>
                            <span class="mx-2">•</span>
                            <span>⏭️ Click to review</span>
                        </div>
                    `;
                    
                    skippedItem.addEventListener('click', (e) => {
                        e.stopPropagation();
                        navigateToQuestion(questionId);
                    });
                    
                    skippedContainer.appendChild(skippedItem);
                }
            });
        }
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function navigateToQuestion(questionId) {
        const questionElement = document.querySelector(`.question-item[data-question-id="${questionId}"]`);
        if (questionElement) {
            const targetPage = parseInt(questionElement.dataset.page);
            if (!isNaN(targetPage) && targetPage !== currentPage) {
                currentPage = targetPage;
                updateNavigation();
            }
            
            // Scroll to the question
            setTimeout(() => {
                questionElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                questionElement.classList.add('highlight-pulse');
                setTimeout(() => {
                    questionElement.classList.remove('highlight-pulse');
                }, 2000);
            }, 100);
        }
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
            
            // Show temporary notification
            showToast('Question skipped! You can review it later from the sidebar.', 'warning');
        }
    }
    
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg text-white z-50 animate-slide-up ${
            type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
    
    function saveToLocalStorage() {
        const attemptId = Date.now();
        localStorage.setItem(`quiz_${ {{ $quiz->id }} }_answers`, JSON.stringify(answers));
        localStorage.setItem(`quiz_${ {{ $quiz->id }} }_timeLeft`, timeLeft);
        localStorage.setItem(`quiz_${ {{ $quiz->id }} }_skipped`, JSON.stringify(Array.from(skippedQuestions)));
    }
    
    function loadFromLocalStorage() {
        const savedAnswers = localStorage.getItem(`quiz_${ {{ $quiz->id }} }_answers`);
        const savedTimeLeft = localStorage.getItem(`quiz_${ {{ $quiz->id }} }_timeLeft`);
        const savedSkipped = localStorage.getItem(`quiz_${ {{ $quiz->id }} }_skipped`);
        
        if (savedAnswers) {
            const loadedAnswers = JSON.parse(savedAnswers);
            Object.assign(answers, loadedAnswers);
            
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
    }
    
    function clearLocalStorage() {
        localStorage.removeItem(`quiz_${ {{ $quiz->id }} }_answers`);
        localStorage.removeItem(`quiz_${ {{ $quiz->id }} }_timeLeft`);
        localStorage.removeItem(`quiz_${ {{ $quiz->id }} }_skipped`);
    }
    
    function submitQuiz() {
        const totalQuestions = {{ $questions->count() }};
        const answeredCount = Object.keys(answers).filter(qId => answers[qId] && answers[qId] !== '').length;
        const skippedCount = skippedQuestions.size;
        const unansweredCount = totalQuestions - answeredCount;
        
        let message = 'Are you sure you want to submit the quiz?';
        
        if (skippedCount > 0) {
            message += `\n\n⚠️ You have ${skippedCount} skipped question(s).`;
            message += `\n📝 Total answered: ${answeredCount} out of ${totalQuestions}`;
            message += `\n\nSkipped questions will receive 0 points.`;
        } else if (unansweredCount > 0) {
            message += `\n\n⚠️ You have ${unansweredCount} unanswered question(s).`;
        } else {
            message += `\n\n✅ You have answered all ${totalQuestions} questions!`;
        }
        
        message += '\n\nOnce submitted, you cannot change your answers.';
        
        if (confirm(message)) {
            document.querySelectorAll('.question-item').forEach(q => {
                const questionId = q.dataset.questionId;
                const inputName = `answers[${questionId}]`;
                const existingInput = quizForm.querySelector(`input[name="${inputName}"]:checked`);
                
                if (!existingInput && (!answers[questionId] || answers[questionId] === '')) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = inputName;
                    hiddenInput.value = '';
                    quizForm.appendChild(hiddenInput);
                }
            });
            
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
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
    
    prevPageBtn.addEventListener('click', () => {
        if (currentPage > 0) {
            currentPage--;
            updateNavigation();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });
    
    pageSelector.addEventListener('change', (e) => {
        currentPage = parseInt(e.target.value) - 1;
        updateNavigation();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    pageDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentPage = index;
            updateNavigation();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
    
    reviewAllBtn.addEventListener('click', () => {
        if (skippedQuestions.size > 0) {
            const firstSkipped = Array.from(skippedQuestions)[0];
            navigateToQuestion(firstSkipped);
        }
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
                });
                optionItem.classList.add('selected-option');
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
                });
            }
            
            // Auto-advance to next question if not last on page
            const currentPageQuestions = pages[currentPage].querySelectorAll('.question-item');
            const currentQuestionIndex = Array.from(currentPageQuestions).findIndex(
                q => q.dataset.questionId === questionId
            );
            
            if (currentQuestionIndex < currentPageQuestions.length - 1) {
                // Scroll to next question on same page
                const nextQuestion = currentPageQuestions[currentQuestionIndex + 1];
                nextQuestion.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else if (currentPage < totalPages - 1) {
                // Go to next page
                currentPage++;
                updateNavigation();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight' && e.shiftKey && currentPage < totalPages - 1) {
            nextPageBtn.click();
        }
        if (e.key === 'ArrowLeft' && e.shiftKey && currentPage > 0) {
            prevPageBtn.click();
        }
        if (e.key === 'Enter' && e.ctrlKey) {
            e.preventDefault();
            submitBtn.click();
        }
    });
    
    // Warn on page refresh
    window.addEventListener('beforeunload', (e) => {
        if (Object.keys(answers).length > 0 && !quizForm.submitted) {
            saveToLocalStorage();
            e.preventDefault();
            e.returnValue = 'You have unsaved answers. Are you sure you want to leave?';
            return e.returnValue;
        }
    });
    
    quizForm.addEventListener('submit', () => {
        quizForm.submitted = true;
        clearLocalStorage();
    });
    
    // Load saved answers
    loadFromLocalStorage();
    
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

.question-item.answered {
    border-left: 4px solid #10b981;
    padding-left: 1rem;
    margin-left: -1rem;
}

.question-item.skipped {
    border-left: 4px solid #f59e0b;
    padding-left: 1rem;
    margin-left: -1rem;
    background-color: #fefce8;
}

.question-item.highlight-pulse {
    animation: pulse 0.5s ease-in-out 3;
    background-color: #fef3c7;
}

@keyframes pulse {
    0%, 100% { background-color: #fef3c7; }
    50% { background-color: #fde68a; }
}

@keyframes slide-up {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.animate-slide-up {
    animation: slide-up 0.3s ease-out;
}

.page-dot {
    transition: all 0.2s ease;
}

.page-dot:hover {
    transform: scale(1.2);
}

#timer.blinking {
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

#skipped-questions-container {
    scrollbar-width: thin;
}

#skipped-questions-container::-webkit-scrollbar {
    width: 6px;
}

#skipped-questions-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#skipped-questions-container::-webkit-scrollbar-thumb {
    background: #f59e0b;
    border-radius: 10px;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection