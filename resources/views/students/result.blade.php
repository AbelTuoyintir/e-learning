<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .correct-answer {
            background-color: #D1FAE5;
            border-color: #34D399;
            color: #065F46;
        }
        .incorrect-answer {
            background-color: #FEE2E2;
            border-color: #F87171;
            color: #B91C1C;
        }
        .user-answer {
            background-color: #FEF3C7;
            border-color: #F59E0B;
            color: #92400E;
        }
        .skipped-answer {
            background-color: #F3F4F6;
            border-color: #D1D5DB;
            color: #6B7280;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="max-w-3xl mx-auto p-6">
        <!-- Quiz Title -->
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">{{ $quiz->title }}</h2>

        @if($sessionResult)
            @php
                $correct = collect($sessionResult['details'])->where('is_correct', true)->count();
                $skipped = collect($sessionResult['details'])->where('skipped', true)->count();
                $wrong = $sessionResult['total'] - $correct - $skipped;
                $percentage = round(($sessionResult['score'] / $sessionResult['total']) * 100, 2);
            @endphp

            <!-- Performance Summary Card -->
            <div class="bg-white shadow-md rounded-xl p-6 mb-8 border">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">📊 Performance Summary</h3>

                <div class="space-y-2">
                    <p><span class="font-semibold">Score:</span> {{ $sessionResult['score'] }} / {{ $sessionResult['total'] }}</p>
                    <p><span class="font-semibold">Percentage:</span> {{ $percentage }}%</p>
                </div>

                <!-- Progress bar -->
                <div class="w-full bg-gray-200 rounded-full h-3 mt-4">
                    <div class="h-3 rounded-full
                        @if($percentage >= 70) bg-green-500
                        @elseif($percentage >= 40) bg-yellow-500
                        @else bg-red-500
                        @endif"
                        style="width: {{ $percentage }}%">
                    </div>
                </div>

                <!-- Stats -->
                <div class="flex justify-between mt-4 text-sm font-medium">
                    <span class="text-green-600">✔ Correct: {{ $correct }}</span>
                    <span class="text-red-600">✘ Wrong: {{ $wrong }}</span>
                    <span class="text-yellow-600">⏭ Skipped: {{ $skipped }}</span>
                </div>
            </div>

            <h3 class="text-xl font-semibold text-gray-800 mb-4">📝 Answer Review</h3>
            <div class="space-y-6">
                @foreach($result->details as $index => $detail)
                    <div class="mb-6 p-4 bg-white rounded shadow">
                        <p class="font-semibold">
                            Q{{ $index + 1 }}: {{ $detail['question'] ?? 'Question not available' }}
                        </p>
                        <p class="text-sm text-gray-500">Points: {{ $detail['points'] ?? 1 }}</p>

                        <div class="mt-2 space-y-2">
                          @php
    $yourAnswer = $detail['your_answer'];
    $correctAnswer = $detail['correct_answer']; // text from DB
@endphp

@foreach($detail['options'] as $key => $option)
    @php
        $isUserAnswer = $yourAnswer === $option;
        $isCorrectAnswer = $correctAnswer === $option;
    @endphp

    <div class="p-2 rounded border
        @if($isCorrectAnswer) correct-answer
        @elseif($isUserAnswer && !$isCorrectAnswer) incorrect-answer
        @elseif($detail['skipped']) skipped-answer
        @else bg-gray-100 border-gray-300 text-gray-700
        @endif">
        <div class="flex justify-between items-center">
            <span>{{ $key }}: {{ $option }}</span>
            <span>
                @if($isCorrectAnswer)
                    <span class="text-green-600 font-bold">✓ Correct</span>
                @endif
                @if($isUserAnswer && !$isCorrectAnswer)
                    <span class="text-red-600 font-bold">(Your Answer)</span>
                @endif
                @if($detail['skipped'])
                    <span class="font-bold text-gray-500">(Skipped)</span>
                @endif
            </span>
        </div>
    </div>
@endforeach


                                <!-- Fallback display when options aren't available -->
                                <div class="p-2 rounded border bg-gray-100 border-gray-300 text-gray-700">
                                    <p class="font-semibold">Your answer:</p>
                                    <p>{{ $detail['your_key'] ?? 'Not answered' }}</p>
                                </div>

                           
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Action buttons -->
            <div class="mt-8 flex justify-center space-x-4">
                <a href="{{ route('quiz.start', $quiz) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Retry Quiz</a>
                <a href="{{ route('dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Back to Quizzes</a>
            </div>
        @else
            <div class="bg-white p-6 rounded shadow text-center">
                <p class="text-red-500">No quiz results available.</p>
                <a href="{{ url()->previous() }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Go Back</a>
            </div>
        @endif
    </div>
</body>
</html>
