<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results: {{ $quiz->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .correct-answer {
            background-color: #D1FAE5;
            border-left: 4px solid #34D399;
            color: #065F46;
        }
        .incorrect-answer {
            background-color: #FEE2E2;
            border-left: 4px solid #F87171;
            color: #B91C1C;
        }
        .user-correct-answer {
            background-color: #D1FAE5;
            border-left: 4px solid #34D399;
            color: #065F46;
            position: relative;
        }
        .user-incorrect-answer {
            background-color: #FEE2E2;
            border-left: 4px solid #F87171;
            color: #B91C1C;
            position: relative;
        }
        .skipped-answer {
            background-color: #F3F4F6;
            border-left: 4px solid #D1D5DB;
            color: #6B7280;
        }
        .default-option {
            background-color: #F9FAFB;
            border-left: 4px solid #E5E7EB;
            color: #4B5563;
        }
        .score-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 text-gray-900 dark:text-gray-100 transition-colors duration-300 min-h-screen">
    <div class="max-w-4xl mx-auto p-4 md:p-6 lg:p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 dark:text-gray-100 mb-2">
                <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                Quiz Results
            </h1>
            <h2 class="text-xl md:text-2xl font-semibold text-gray-600 dark:text-gray-300">
                {{ $quiz->title }}
            </h2>
            <p class="text-gray-500 dark:text-gray-400 mt-2">
                Attempt #{{ $result->attempt_number }} •
                {{ $result->completed_at->format('F j, Y g:i A') }}
            </p>
        </div>

        @if($sessionResult && !empty($sessionResult['details']))
            @php
                // Calculate statistics
                $details = $sessionResult['details'];
                $total = $sessionResult['total'];
                $score = $sessionResult['score'];
                $percentage = $sessionResult['percentage'] ?? round(($score / $total) * 100, 2);
                $passed = $sessionResult['passed'] ?? $percentage >= 70;

                // Count stats
                $correct = collect($details)->where('is_correct', true)->count();
                $skipped = collect($details)->where('skipped', true)->count();
                $wrong = $total - $correct - $skipped;
            @endphp

            <!-- Performance Summary Card -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6 mb-8 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                        <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                        Performance Summary
                    </h3>
                    <span class="px-4 py-2 rounded-full text-sm font-bold
                        {{ $passed ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                        {{ $passed ? 'PASSED' : 'FAILED' }}
                    </span>
                </div>

                <!-- Score Display -->
                <div class="text-center mb-8">
                    <div class="inline-block relative">
                        <div class="score-badge">
                            <div class="text-6xl md:text-7xl font-bold
                                {{ $percentage >= 70 ? 'text-green-600' :
                                   ($percentage >= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $percentage }}%
                            </div>
                        </div>
                        <div class="text-lg text-gray-600 dark:text-gray-400 mt-2">
                            {{ $score }} / {{ $total }} correct
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="mb-6">
                    <div class="flex justify-between text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">
                        <span>0%</span>
                        <span>Passing: 70%</span>
                        <span>100%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                        <div class="h-4 rounded-full
                            @if($percentage >= 70) bg-green-500
                            @elseif($percentage >= 40) bg-yellow-500
                            @else bg-red-500
                            @endif"
                            style="width: {{ min($percentage, 100) }}%">
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-xl">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $correct }}</div>
                        <div class="text-sm text-green-700 dark:text-green-300">Correct</div>
                        <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                            {{ $total > 0 ? round(($correct/$total)*100, 1) : 0 }}%
                        </div>
                    </div>

                    <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-xl">
                        <div class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $wrong }}</div>
                        <div class="text-sm text-red-700 dark:text-red-300">Incorrect</div>
                        <div class="text-xs text-red-600 dark:text-red-400 mt-1">
                            {{ $total > 0 ? round(($wrong/$total)*100, 1) : 0 }}%
                        </div>
                    </div>

                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                        <div class="text-3xl font-bold text-gray-600 dark:text-gray-400">{{ $skipped }}</div>
                        <div class="text-sm text-gray-700 dark:text-gray-300">Skipped</div>
                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                            {{ $total > 0 ? round(($skipped/$total)*100, 1) : 0 }}%
                        </div>
                    </div>
                </div>

                <!-- Time and Attempt Info -->
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2 text-blue-500"></i>
                            <span>Completion Time: {{ $result->completed_at->diffForHumans($result->created_at, true) }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-redo mr-2 text-blue-500"></i>
                            <span>Attempt Number: {{ $result->attempt_number }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Answer Review Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                        <i class="fas fa-list-check text-blue-500 mr-2"></i>
                        Answer Review
                    </h3>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $total }} questions
                    </span>
                </div>

                <div class="space-y-6">
                    @foreach($sessionResult['details'] as $index => $detail)
                        @php
                            // Get the correct option letter from debug info or calculate it
                            $correctOptionLetter = $detail['debug']['correct_letter'] ?? '';
                            $userSelectedOption = $detail['debug']['user_selected'] ?? '';

                            // Find correct option text
                            $correctOptionText = $detail['correct_answer'] ?? '';

                            // Find user's answer text
                            $userAnswerText = $detail['your_answer'] ?? '';

                            // Check if user was correct
                            $isCorrect = $detail['is_correct'] ?? false;
                            $isSkipped = $detail['skipped'] ?? false;
                        @endphp

                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden
                            {{ $isCorrect ? 'border-l-4 border-green-500' : 'border-l-4 border-red-500' }}">
                            <!-- Question Header -->
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full
                                            {{ $isCorrect ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                               ($isSkipped ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' :
                                               'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="ml-2 font-semibold text-gray-800 dark:text-gray-200">
                                            Question {{ $index + 1 }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if($isCorrect)
                                            <span class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full text-sm font-medium">
                                                <i class="fas fa-check mr-1"></i> Correct
                                            </span>
                                        @elseif($isSkipped)
                                            <span class="px-3 py-1 bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 rounded-full text-sm font-medium">
                                                <i class="fas fa-forward mr-1"></i> Skipped
                                            </span>
                                        @else
                                            <span class="px-3 py-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full text-sm font-medium">
                                                <i class="fas fa-times mr-1"></i> Incorrect
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <p class="mt-3 text-gray-700 dark:text-gray-300">{{ $detail['question'] }}</p>
                            </div>

                            <!-- Options -->
                            <div class="p-4 space-y-3">
                                @foreach(['A' => $detail['options']['A'] ?? '',
                                         'B' => $detail['options']['B'] ?? '',
                                         'C' => $detail['options']['C'] ?? '',
                                         'D' => $detail['options']['D'] ?? ''] as $letter => $optionText)
                                    @if(!empty($optionText))
                                        @php
                                            $isCorrectOption = ($correctOptionText === $optionText);
                                            $isUserSelected = ($userAnswerText === $optionText);
                                            $optionClass = 'default-option';

                                            if ($isCorrectOption && $isUserSelected) {
                                                $optionClass = 'user-correct-answer';
                                            } elseif ($isCorrectOption && !$isUserSelected) {
                                                $optionClass = 'correct-answer';
                                            } elseif ($isUserSelected && !$isCorrectOption) {
                                                $optionClass = 'user-incorrect-answer';
                                            } elseif ($isSkipped) {
                                                $optionClass = 'skipped-answer';
                                            }
                                        @endphp

                                        <div class="p-3 rounded-lg {{ $optionClass }} transition-all duration-200">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <span class="font-bold mr-3
                                                        {{ $isCorrectOption ? 'text-green-700 dark:text-green-300' :
                                                           ($isUserSelected ? 'text-red-700 dark:text-red-300' :
                                                           'text-gray-700 dark:text-gray-300') }}">
                                                        {{ $letter }}.
                                                    </span>
                                                    <span class="{{ $isCorrectOption ? 'font-semibold' : '' }}">
                                                        {{ $optionText }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    @if($isCorrectOption)
                                                        <span class="text-green-600 dark:text-green-400">
                                                            <i class="fas fa-check-circle"></i>
                                                            <span class="ml-1 hidden md:inline">Correct Answer</span>
                                                        </span>
                                                    @endif
                                                    @if($isUserSelected && !$isSkipped)
                                                        <span class="text-blue-600 dark:text-blue-400">
                                                            <i class="fas fa-user-circle"></i>
                                                            <span class="ml-1 hidden md:inline">Your Answer</span>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <!-- Feedback Section -->
                                @if(!$isCorrect && !$isSkipped)
                                    <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 rounded-r">
                                        <div class="flex">
                                            <i class="fas fa-lightbulb text-yellow-500 mt-1 mr-3"></i>
                                            <div>
                                                <p class="font-medium text-yellow-800 dark:text-yellow-300">Learning Point</p>
                                                <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-1">
                                                    You selected: <span class="font-semibold">{{ $userAnswerText ?: 'Nothing' }}</span>
                                                </p>
                                                <p class="text-sm text-green-700 dark:text-green-400 mt-1">
                                                    Correct answer: <span class="font-semibold">{{ $correctOptionText }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($isSkipped)
                                    <div class="mt-4 p-3 bg-gray-100 dark:bg-gray-700 border-l-4 border-gray-500 rounded-r">
                                        <div class="flex items-center">
                                            <i class="fas fa-forward text-gray-500 mr-3"></i>
                                            <p class="text-gray-700 dark:text-gray-300">
                                                You skipped this question. Time ran out or you chose not to answer.
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
                <a href="{{ route('quiz.start', $quiz) }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-lg shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fas fa-redo mr-2"></i>
                    Retake Quiz
                </a>

                <a href="{{ route('students.dashboard') }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold rounded-lg shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fas fa-home mr-2"></i>
                    Back to Dashboard
                </a>

                <a href="{{ route('results.index') }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-semibold rounded-lg shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fas fa-history mr-2"></i>
                    View All Results
                </a>
            </div>

            <!-- Tips Section -->
            <div class="mt-12 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-800">
                <h4 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
                    <i class="fas fa-graduation-cap text-blue-500 mr-2"></i>
                    Tips for Improvement
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-start">
                        <i class="fas fa-clock text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">Time Management</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Review questions you skipped due to time constraints.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-book text-green-500 mt-1 mr-3"></i>
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">Weak Areas</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Focus on topics where you got answers wrong.</p>
                        </div>
                    </div>
                </div>
                @if(!$passed)
                    <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                        <p class="text-yellow-800 dark:text-yellow-300">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            You need {{ max(0, ceil(($quiz->pass_percentage ?? 70) * $total / 100) - $score) }} more correct answers to pass.
                            Focus on your weak areas and try again!
                        </p>
                    </div>
                @endif
            </div>

        @else
            <!-- No Results Found -->
            <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl text-center">
                <div class="text-6xl text-gray-300 dark:text-gray-700 mb-4">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-3">No Results Available</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    It looks like you haven't completed this quiz yet, or there was an issue loading your results.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('quiz.start', $quiz) }}"
                       class="px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium">
                        <i class="fas fa-play mr-2"></i> Start Quiz
                    </a>
                    <a href="{{ route('students.dashboard') }}"
                       class="px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="mt-12 pt-6 border-t border-gray-200 dark:border-gray-700 text-center text-gray-500 dark:text-gray-400 text-sm">
            <p>Quiz completed on {{ $result->completed_at->format('F j, Y') }}</p>
            <p class="mt-1">Results are saved to your academic record.</p>
        </div>
    </div>

    <!-- JavaScript for interactivity -->
    <script>
        // Smooth scroll to incorrect answers
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to score badge
            const scoreBadge = document.querySelector('.score-badge');
            if (scoreBadge) {
                setTimeout(() => {
                    scoreBadge.style.animation = 'none';
                    setTimeout(() => {
                        scoreBadge.style.animation = 'pulse 2s infinite';
                    }, 50);
                }, 2000);
            }

            // Print-friendly view
            const printBtn = document.createElement('button');
            printBtn.innerHTML = '<i class="fas fa-print mr-2"></i> Print Results';
            printBtn.className = 'fixed bottom-4 right-4 px-4 py-2 bg-gray-800 text-white rounded-lg shadow-lg hover:bg-gray-900 transition-all z-10 hidden md:block';
            printBtn.onclick = () => window.print();
            document.body.appendChild(printBtn);

            // Highlight incorrect answers on click
            document.querySelectorAll('.user-incorrect-answer').forEach(el => {
                el.addEventListener('click', function() {
                    this.classList.toggle('ring-2');
                    this.classList.toggle('ring-red-300');
                });
            });
        });
    </script>
</body>
</html>
