<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Result;
use App\Models\Student;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\StudentResultMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class StudentController extends Controller
{
    //
    // StudentController.php
public function dashboard()
{
    $student = auth()->user();

    // Enrolled courses count
    $enrolledCoursesCount = $student->enrollments()->count();

    // Completed quizzes count
    $completedQuizzesCount = Result::where('student_id', $student->id)->count();

    // Average score
    $results = Result::where('student_id', $student->id)->get();
    $averageScore = $results->count() > 0 ? $results->avg('percentage') : 0;

    // Recent results
    $recentResults = Result::where('student_id', $student->id)
        ->with('quiz')
        ->latest('completed_at')
        ->take(5)
        ->get();

    // Available quizzes
    $enrolledCourseIds = $student->enrollments()->pluck('course_id')->all();

    $availableQuizzes = Quiz::withCount('questions')
        ->with('questions:id,quiz_id')
        ->whereHas('questions')
        ->where(function ($query) use ($enrolledCourseIds) {
            $query->whereIn('course_id', $enrolledCourseIds)
                ->orWhereHas('module', function ($moduleQuery) use ($enrolledCourseIds) {
                    $moduleQuery->whereIn('course_id', $enrolledCourseIds);
                })
                ->orWhereHas('topic.module', function ($moduleQuery) use ($enrolledCourseIds) {
                    $moduleQuery->whereIn('course_id', $enrolledCourseIds);
                });
        })
        ->get();

    // Progress Tracking
    $totalTopics = \App\Models\Topic::whereHas('module', function($q) use ($enrolledCourseIds) {
        $q->whereIn('course_id', $enrolledCourseIds);
    })->count();

    $completedTopics = \App\Models\TopicProgress::where('student_id', $student->id)
        ->where('status', 'Completed')
        ->count();

    $remainingTopics = $totalTopics - $completedTopics;
    $completionPercentage = $totalTopics > 0 ? round(($completedTopics / $totalTopics) * 100) : 0;

    // Academic Records
    $passedModules = \App\Models\Result::where('student_id', $student->id)
        ->where('passed', 1)
        ->whereHas('quiz', function($q) { $q->where('quiz_type', 'module_assessment'); })
        ->count();

    $failedModules = \App\Models\Result::where('student_id', $student->id)
        ->where('passed', 0)
        ->whereHas('quiz', function($q) { $q->where('quiz_type', 'module_assessment'); })
        ->count();

    $retakeModules = \App\Models\ModuleProgress::where('student_id', $student->id)
        ->where('status', 'Retake Required')
        ->count();

    $aiLearningSessions = \App\Models\AIChatSession::where('student_id', $student->id)->count();

    return view('students.dashboard', compact(
        'enrolledCoursesCount',
        'completedQuizzesCount',
        'averageScore',
        'recentResults',
        'availableQuizzes',
        'totalTopics',
        'completedTopics',
        'remainingTopics',
        'completionPercentage',
        'passedModules',
        'failedModules',
        'retakeModules',
        'aiLearningSessions'
    ));
}

public function showQuiz(Quiz $quiz)
{
    $quiz->loadMissing('module', 'topic.module');

    if (!$this->canAccessQuiz($quiz)) {
        return $this->denyQuizAccess($quiz);
    }

    // Check module progress/retake status
    if ($quiz->module_id) {
        $moduleProgress = \App\Models\ModuleProgress::firstOrCreate(
            ['student_id' => Auth::id(), 'module_id' => $quiz->module_id]
        );

        if ($moduleProgress->status === 'Retake Required') {
            return redirect()->back()->with('error', 'Module retake required. Please review all module topics before attempting the assessment again.');
        }

        if ($moduleProgress->attempts_since_retake >= ($quiz->max_attempts ?? 4)) {
            $moduleProgress->update(['status' => 'Retake Required']);
            return redirect()->back()->with('error', 'Maximum attempts reached. Module retake required.');
        }
    }

    // If the quiz has a distribution, dynamically select questions and lock them per attempt.
    $distribution = $quiz->question_distribution;
    $questionIdsLocked = null;

    if (is_array($distribution) && !empty($distribution)) {
        // Create (or reuse) an in-progress attempt so refresh won't change questions.
        $attempt = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', Auth::id())
            ->where('status', 'in_progress')
            ->latest('started_at')
            ->first();

        if ($attempt && is_array($attempt->question_ids) && !empty($attempt->question_ids)) {
            $questionIdsLocked = array_values(array_map('intval', $attempt->question_ids));
        } else {
            $questionIdsLocked = $this->selectQuestionIdsByDistribution($quiz, $distribution);

            $attempt = \App\Models\QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'user_id' => Auth::id(),
                'started_at' => now(),
                'status' => 'in_progress',
                'question_ids' => array_values($questionIdsLocked),
            ]);
        }

        $questions = $quiz->questions()
            ->whereIn('id', $questionIdsLocked)
            ->get();

        // Ensure question order follows locked ids.
        $questions = $questions->sortBy(fn($q) => array_search($q->id, $questionIdsLocked, true))->values();
    } else {
        // Rule 1 & 2: Randomly select 60 questions if > 60, else all.
        $totalQuestionsInBank = $quiz->questions()->count();
        if ($totalQuestionsInBank > 60) {
            $questions = $quiz->questions()->inRandomOrder()->limit(60)->get();
        } else {
            $questions = $quiz->questions()->get()->unique('question_text')->shuffle();
        }
    }

    foreach ($questions as $question) {
        $correctLetter = $this->normalizeCorrectOptionLetter($question->correct_option);
        $correctOptionColumn = $this->getOptionColumn($correctLetter);
        $question->correct_answer_text = $question->{$correctOptionColumn};
    }

    return view('students.question', compact('quiz', 'questions'));
}



public function submit(Request $request, Quiz $quiz)
{
    $quiz->loadMissing('module', 'topic.module');

    if (!$this->canAccessQuiz($quiz)) {
        return $this->denyQuizAccess($quiz);
    }

    
    \Log::info('=== QUIZ SUBMIT START ===', [
        'quiz_id' => $quiz->id,
        'quiz_title' => $quiz->title,
        'student_id' => Auth::id(),
        'answers_count' => count($request->input('answers', [])),
    ]);

    // Validate the request
    $validated = $request->validate([
        'answers' => 'required|array',
        'answers.*' => 'nullable|string|in:A,B,C,D,a,b,c,d', // Added lowercase
    ]);

    \Log::debug('Validation passed', [
        'answers_keys' => array_keys($validated['answers']),
        'answers_values' => array_values($validated['answers'])
    ]);

    try {
        // Use database transaction for data integrity
        return DB::transaction(function () use ($request, $quiz, $validated) {
            $score = 0;
            $details = [];

            // Eager load questions to avoid N+1 query
            $quiz->load(['questions' => function($query) {
                $query->select('id', 'quiz_id', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option', 'points'); // Added 'points'
            }]);

            \Log::debug('Questions loaded', [
                'count' => $quiz->questions->count(),
                'question_ids' => $quiz->questions->pluck('id')->toArray()
            ]);

            $lockedQuestionIds = null;
            if (is_array($request->input('locked_question_ids'))) {
                $lockedQuestionIds = array_values(array_map('intval', $request->input('locked_question_ids')));
            }

            $questionsToScore = $quiz->questions;
            if ($lockedQuestionIds && !empty($lockedQuestionIds)) {
                $questionsToScore = $quiz->questions()->whereIn('id', $lockedQuestionIds)->get();
            }

            foreach ($questionsToScore as $question) {

                // Get user answer from validated data
                $userAnswerLetter = isset($validated['answers'][$question->id])
                    ? strtoupper(trim($validated['answers'][$question->id]))
                    : '';

                $correctLetter = $this->normalizeCorrectOptionLetter($question->correct_option);

                $isCorrect = false;
                $userAnswerText = null;
                $userAnswerColumn = null;
                $pointsEarned = 0;

                // Validate user answer
                if ($this->isValidAnswer($userAnswerLetter)) {
                    $userAnswerColumn = $this->getOptionColumn($userAnswerLetter);
                    $userAnswerText = $question->{$userAnswerColumn} ?? null;

                    $isCorrect = ($userAnswerLetter === $correctLetter);

                    if ($isCorrect) {
                        $score += $question->points; // Use question points instead of just 1
                        $pointsEarned = $question->points;
                    }
                }

                // Prepare answer details
                $details[] = $this->prepareAnswerDetails($question, $userAnswerText, $isCorrect, $userAnswerLetter, $pointsEarned);
            }

            \Log::debug('Scoring complete', [
                'score' => $score,
                'total_questions' => $quiz->questions->count(),
                'total_possible_points' => $quiz->questions->sum('points')
            ]);

            $totalPossiblePoints = $quiz->questions->sum('points');
            $percentage = $this->calculatePercentage($score, $totalPossiblePoints);
            $passed = $this->isPassed($percentage, $quiz);
            $attemptNumber = $this->getNextAttemptNumber($quiz->id, Auth::id());

            // Calculate grade
            $grade = 'F';
            if ($percentage >= 90) $grade = 'A';
            elseif ($percentage >= 80) $grade = 'B';
            elseif ($percentage >= 70) $grade = 'C';
            elseif ($percentage >= 60) $grade = 'D';

            // Estimate time taken (from attempt if exists)
            $timeTaken = 0;
            $attempt = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)
                ->where('user_id', Auth::id())
                ->where('status', 'in_progress')
                ->latest('started_at')
                ->first();
            if ($attempt) {
                $timeTaken = now()->diffInSeconds($attempt->started_at);
                $attempt->update(['status' => 'completed', 'completed_at' => now()]);
            }

            \Log::debug('Creating result', [
                'score' => $score,
                'total_possible_points' => $totalPossiblePoints,
                'percentage' => $percentage,
                'passed' => $passed,
                'attempt' => $attemptNumber,
            ]);

            // Create result with validated data
            $result = Result::create([
                'student_id' => Auth::id(),
                'quiz_id' => $quiz->id,
                'score' => $score,
                'total_possible_points' => $totalPossiblePoints,
                'percentage' => $percentage,
                'grade' => $grade,
                'time_taken' => $timeTaken,
                'passed' => $passed,
                'attempt_number' => $attemptNumber,
                'completed_at' => now(),
                'details' => json_encode($details),
            ]);

            \App\Models\LearningHistory::create([
                'student_id' => Auth::id(),
                'activity_type' => 'assessment_taken',
                'activity_id' => $quiz->id,
                'description' => "Took assessment for '{$quiz->title}'",
                'metadata' => [
                    'score' => $score,
                    'percentage' => $percentage,
                    'passed' => $passed,
                ],
            ]);

            \Log::info('Result created', [
                'result_id' => $result->id,
                'quiz_id' => $result->quiz_id,
                'student_id' => $result->student_id,
                'score' => $result->score,
                'percentage' => $result->percentage,
            ]);

            // Update module progress attempts
            if ($quiz->module_id) {
                $moduleProgress = \App\Models\ModuleProgress::where('student_id', Auth::id())
                    ->where('module_id', $quiz->module_id)
                    ->first();
                if ($moduleProgress) {
                    $moduleProgress->increment('attempts_since_retake');
                    if ($passed) {
                        $moduleProgress->update(['status' => 'Completed']);

                        \App\Models\LearningHistory::create([
                            'student_id' => Auth::id(),
                            'activity_type' => 'module_completed',
                            'activity_id' => $quiz->module_id,
                            'description' => "Completed module '{$quiz->module->title}'",
                            'metadata' => ['percentage' => $percentage],
                        ]);
                    } elseif ($moduleProgress->attempts_since_retake >= ($quiz->max_attempts ?? 4)) {
                        $moduleProgress->update(['status' => 'Retake Required']);
                    }
                }
            }

            // Dispatch notifications asynchronously
            $this->sendNotifications($result, $quiz, $score, $totalPossiblePoints, $percentage, $passed);

            // Log for debugging
            $this->logQuizSubmission($quiz, $score, $totalPossiblePoints, $validated['answers']);

            // Notify of retake requirement if max attempts reached and failed
            $attemptCount = Result::where('student_id', Auth::id())
                ->where('quiz_id', $quiz->id)
                ->count();
            if (!$passed && $attemptCount >= ($quiz->max_attempts ?? 4)) {
                \App\Models\Notification::create([
                    'student_id' => Auth::id(),
                    'title' => 'Retake Required',
                    'message' => "You have failed all 4 attempts for '{$quiz->title}'. Module retake is required.",
                    'type' => 'error',
                ]);
            }

            \Log::info('Redirecting to results', [
                'route_name' => 'quiz.results',
                'quiz_id' => $quiz->id,
                'result_id' => $result->id,
                'redirect_url' => route('quiz.results', $quiz->id),
            ]);

            // Redirect with success message
            return redirect()->route('quiz.results', $quiz->id)
                ->with('success', 'Quiz submitted successfully!')
                ->with('result_id', $result->id); // Add result ID to session
        });

    } catch (\Exception $e) {
        \Log::error('Quiz submission failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'quiz_id' => $quiz->id,
            'student_id' => Auth::id(),
            'request_data' => $request->all(),
        ]);

        return redirect()->back()
            ->with('error', 'Failed to submit quiz: ' . $e->getMessage())
            ->withInput();
    }
}

/**
 * Get the option column name based on letter
 */
private function getOptionColumn($letter): string
{
    $letter = strtoupper($letter);
    $columnMap = [
        'A' => 'option_a',
        'B' => 'option_b',
        'C' => 'option_c',
        'D' => 'option_d'
    ];

    return $columnMap[$letter] ?? 'option_a'; // Default to option_a if invalid
}

private function normalizeCorrectOptionLetter($value): string
{
    $normalized = strtoupper(trim((string) $value));
    $normalized = str_replace([' ', '-'], '_', $normalized);

    $map = [
        'A' => 'A',
        'B' => 'B',
        'C' => 'C',
        'D' => 'D',
        'OPTION_A' => 'A',
        'OPTION_B' => 'B',
        'OPTION_C' => 'C',
        'OPTION_D' => 'D',
        '1' => 'A',
        '2' => 'B',
        '3' => 'C',
        '4' => 'D',
    ];

    return $map[$normalized] ?? 'A';
}

/**
 * Check if answer is valid
 */
private function isValidAnswer($answer): bool
{
    return !empty($answer) && in_array($answer, ['A', 'B', 'C', 'D']);
}

/**
 * Prepare answer details array
 */
private function prepareAnswerDetails($question, $userAnswerText, $isCorrect, $userAnswerLetter, $pointsEarned = 0): array
{
    $correctLetter = $this->normalizeCorrectOptionLetter($question->correct_option);
    $correctOptionColumn = $this->getOptionColumn($correctLetter);

    return [
        'question_id'    => $question->id,
        'question'       => $question->question_text,
        'options'        => [
            'A' => $question->option_a,
            'B' => $question->option_b,
            'C' => $question->option_c,
            'D' => $question->option_d,
        ],
        'your_answer'    => $userAnswerText,
        'your_letter'    => $userAnswerLetter,
        'correct_answer' => $question->{$correctOptionColumn},
        'correct_letter' => $correctLetter,
        'is_correct'     => $isCorrect,
        'points'         => $question->points,
        'points_earned'  => $pointsEarned,
        'skipped'        => empty($userAnswerLetter) || !$this->isValidAnswer($userAnswerLetter),
    ];
}

/**
 * Calculate percentage
 */
private function calculatePercentage($score, $total): float
{
    if ($total <= 0) {
        return 0.0;
    }

    return round(($score / $total) * 100, 2);
}

/**
 * Check if quiz is passed
 */
private function isPassed($percentage, $quiz): bool
{
    // Strict final quiz rule: use quizzes.passing_score (default 65).
    // Practice quizzes are non-gating (still computed, but module progression ignores it).
    $passPercentage = $quiz->passing_score ?? 65;
    return $percentage >= $passPercentage;
}



/**
 * Get next attempt number
 */
private function getNextAttemptNumber($quizId, $studentId): int
{
    $lastAttempt = Result::where('student_id', $studentId)
        ->where('quiz_id', $quizId)
        ->max('attempt_number');

    return ($lastAttempt ?? 0) + 1;
}

/**
 * Send notifications
 */
private function sendNotifications($result, $quiz, $score, $total, $percentage, $passed): void
{
    $user = Auth::user();

    if (!$user) {
        \Log::warning('Cannot send notifications: User not authenticated');
        return;
    }

    // Email notification
    try {
        Mail::to($user->email)->send(new StudentResultMail($result, $quiz));
        \Log::info('Result email sent', ['email' => $user->email]);
    } catch (\Exception $e) {
        \Log::error('Email failed: ' . $e->getMessage());
    }

    // In-app notification
    try {
        // Make sure you have a Notification model with these fields
        \App\Models\Notification::create([
            'student_id' => $user->id,
            'title' => 'Quiz Completed: ' . $quiz->title,
            'message' => "You scored {$score}/{$total} points ({$percentage}%)",
            'type' => $passed ? 'success' : 'warning',
            'is_read' => false,
            'data' => json_encode([
                'quiz_id' => $quiz->id,
                'result_id' => $result->id,
                'score' => $score,
                'percentage' => $percentage,
                'passed' => $passed
            ]),
        ]);
        \Log::info('In-app notification created');
    } catch (\Exception $e) {
        \Log::error('Notification creation failed: ' . $e->getMessage());
    }
}

/**
 * Log quiz submission details
 */
private function logQuizSubmission($quiz, $score, $totalPoints, $answers): void
{
    \Log::info('Quiz Submission Details', [
        'quiz_id' => $quiz->id,
        'quiz_title' => $quiz->title,
        'student_id' => Auth::id(),
        'student_name' => Auth::user()->name ?? 'Unknown',
        'score' => $score,
        'total_points' => $totalPoints,
        'percentage' => round(($score / max($totalPoints, 1)) * 100, 2),
        'answers_submitted' => count($answers),
        'answers_summary' => array_map(function($answer) {
            return strtoupper(trim($answer));
        }, $answers)
    ]);
}


public function results(Quiz $quiz)
{
    $quiz->loadMissing('module', 'topic.module');

    if (!$this->canAccessQuiz($quiz)) {
        return $this->denyQuizAccess($quiz);
    }

    $result = Result::where('student_id', Auth::id())
        ->where('quiz_id', $quiz->id)
        ->with(['quiz' => function($query) {
            $query->select('id', 'title', 'description', 'time_limit');
        }])
        ->latest('completed_at')
        ->firstOrFail();

    // Process result details
    $this->processResultDetails($result);

    $totalQuestions = $result->quiz->questions()->count();
    $percentage = $this->calculatePercentage($result->score, $totalQuestions);

    $sessionResult = [
        'details' => $result->details,
        'score' => $result->score,
        'total' => $totalQuestions,
        'percentage' => $percentage,
        'passed' => $result->passed,
    ];

    return view('students.result', compact('quiz', 'result', 'sessionResult'));
}

public function resultShow(Result $result)
{
    // Authorize using Laravel policies
    $this->authorize('view', $result);

    // Eager load with specific columns for performance
    $result->load(['quiz' => function($query) {
        $query->select('id', 'title', 'description', 'time_limit', 'course_id', 'module_id', 'topic_id')
              ->withCount('questions');
    }, 'quiz.module', 'quiz.topic.module']);

    if (!$this->canAccessQuiz($result->quiz)) {
        return $this->denyQuizAccess($result->quiz);
    }

    // Process result details
    $this->processResultDetails($result);

    $quiz = $result->quiz;
    $totalQuestions = $quiz->questions_count;
    $percentage = $this->calculatePercentage($result->score, $totalQuestions);

    $sessionResult = [
        'details' => $result->details,
        'score' => $result->score,
        'total' => $totalQuestions,
        'percentage' => $percentage,
        'passed' => $result->passed,
    ];

    return view('students.result', compact('result', 'quiz', 'sessionResult'));
}

/**
 * Process and validate result details
 */
/**
 * Process result details to ensure consistent structure
 */
private function processResultDetails(Result &$result): void
{
    // Handle different data formats
    $details = $result->details;
    
    if (is_string($details)) {
        $details = json_decode($details, true);
    }

    // Ensure details is an array
    $details = is_array($details) ? $details : [];

    // Validate and sanitize details structure
    $sanitizedDetails = [];
    foreach ($details as $detail) {
        $sanitizedDetails[] = $this->sanitizeAnswerDetail($detail);
    }

    // Assign back to result
    $result->details = $sanitizedDetails;
}

/**
 * Sanitize answer detail structure
 */
private function sanitizeAnswerDetail($detail): array
{
    if (!is_array($detail)) {
        return [
            'question' => '',
            'options' => [
                'A' => '',
                'B' => '',
                'C' => '',
                'D' => '',
            ],
            'your_answer' => '',
            'correct_answer' => '',
            'is_correct' => false,
            'skipped' => true,
            'user_letter' => '',
            'correct_letter' => '',
            'points' => 0,
            'points_earned' => 0,
        ];
    }

    // Ensure all required fields exist with default values
    return [
        'question_id' => $detail['question_id'] ?? 0,
        'question' => $detail['question'] ?? '',
        'options' => array_merge(
            [
                'A' => '',
                'B' => '',
                'C' => '',
                'D' => '',
            ],
            $detail['options'] ?? []
        ),
        'your_answer' => $detail['your_answer'] ?? '',
        'correct_answer' => $detail['correct_answer'] ?? '',
        'is_correct' => (bool) ($detail['is_correct'] ?? false),
        'skipped' => (bool) ($detail['skipped'] ?? true),
        'user_letter' => $detail['user_letter'] ?? '',
        'correct_letter' => $detail['correct_letter'] ?? '',
        'points' => (int) ($detail['points'] ?? 0),
        'points_earned' => (int) ($detail['points_earned'] ?? 0),
    ];
}

public function resultsIndex()
{
    $student = Auth::user();

    // Cache results for 5 minutes to improve performance
    $cacheKey = "student_results_{$student->id}_page_" . request()->get('page', 1);

    $results = Cache::remember($cacheKey, 300, function () use ($student) {
        return Result::where('student_id', $student->id)
            ->with(['quiz:id,title,description,time_limit'])
            ->select('id', 'quiz_id', 'score', 'passed', 'attempt_number', 'completed_at')
            ->latest('completed_at')
            ->paginate(10);
    });

    // Calculate statistics
    $statsCacheKey = "student_stats_{$student->id}";

    $stats = Cache::remember($statsCacheKey, 300, function () use ($student) {
        return Result::where('student_id', $student->id)
            ->selectRaw('
                COUNT(*) as total_attempts,
                SUM(CASE WHEN passed = 1 THEN 1 ELSE 0 END) as passed_quizzes,
                AVG(score) as average_score,
                MAX(score) as highest_score,
                MIN(score) as lowest_score,
                COUNT(DISTINCT quiz_id) as unique_quizzes
            ')
            ->first();
    });

    return view('students.results', compact('results', 'stats'));
}
public function profile()
{
    $student = Auth::user();
    return view('students.profile', compact('student'));
}



public function settings()
{
    $student = Auth::user();
    return view('students.settings', compact('student'));
}

public function updateProfile(Request $request)
{
    $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:students,email,' . Auth::id(),
        'phone' => 'nullable|string|max:20',
    ]);

    $student = Auth::user();
    $student->update($request->only(['firstname', 'lastname', 'email', 'phone']));

    return redirect()->route('students.profile')->with('success', 'Profile updated successfully.');
}

public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $student = Auth::user();

    if (!Hash::check($request->current_password, $student->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect.']);
    }

    $student->update([
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('students.settings')->with('success', 'Password changed successfully.');
}

public function updatePreferences(Request $request)
{
    $request->validate([
        'status' => 'required|in:active,inactive',
        'theme_preference' => 'required|in:light,dark',
    ]);

    $student = Auth::user();
    $student->update($request->only(['status', 'theme_preference']));

    return redirect()->route('students.settings')->with('success', 'Preferences updated successfully.');
}

public function getNotifications()
{
    $student = Auth::user();
    $notifications = $student->notifications()->latest()->get();

    return response()->json($notifications);
}

public function markNotificationAsRead($notificationId)
{
    $student = Auth::user();
    $notification = $student->notifications()->findOrFail($notificationId);
    $notification->update(['is_read' => true]);

    return response()->json(['success' => true]);
}

public function markAllNotificationsAsRead()
{
    $student = Auth::user();
    $student->notifications()->where('is_read', false)->update(['is_read' => true]);

    return response()->json(['success' => true]);
}

/**
 * Calculate statistics for a student
 */
public function statistics()
{
    $student = Auth::user();

    $stats = Result::where('student_id', $student->id)
        ->selectRaw('
            COUNT(*) as total_attempts,
            SUM(CASE WHEN passed = 1 THEN 1 ELSE 0 END) as passed_quizzes,
            AVG(score) as average_score,
            MAX(score) as highest_score,
            MIN(score) as lowest_score
        ')
        ->first();

    $recentResults = Result::where('student_id', $student->id)
        ->with('quiz')
        ->latest('completed_at')
        ->take(5)
        ->get();

    return view('students.statistics', compact('stats', 'recentResults'));
}

private function canAccessQuiz(Quiz $quiz): bool
{
    $student = Auth::user();
    $courseId = $this->resolveQuizCourseId($quiz);

    if (!$student || !$courseId) {
        return false;
    }

    return $student->enrollments()->where('course_id', $courseId)->exists();
}

private function resolveQuizCourseId(Quiz $quiz): ?int
{
    if ($quiz->course_id) {
        return (int) $quiz->course_id;
    }

    if ($quiz->module?->course_id) {
        return (int) $quiz->module->course_id;
    }

    if ($quiz->topic?->module?->course_id) {
        return (int) $quiz->topic->module->course_id;
    }

    return null;
}

private function denyQuizAccess(Quiz $quiz)
{
    \Log::warning('Blocked quiz access for unenrolled student.', [
        'quiz_id' => $quiz->id,
        'student_id' => Auth::id(),
        'resolved_course_id' => $this->resolveQuizCourseId($quiz),
    ]);

    return redirect()->route('students.courses')
        ->with('error', 'You must enroll in this course before viewing its quizzes or materials.');
}

/**
 * Select question ids based on quiz->question_distribution.
 * Expected format: {"topic_id": 5, "topic_id2": 10} (counts per topic)
 */
private function selectQuestionIdsByDistribution(Quiz $quiz, array $distribution): array
{
    $distribution = array_filter($distribution, fn($count) => is_numeric($count) && (int)$count > 0);

    if (empty($distribution)) {
        return $quiz->questions()->pluck('id')->toArray();
    }

    $selected = [];

    foreach ($distribution as $topicIdRaw => $countRaw) {
        $topicId = (int) $topicIdRaw;
        $count = (int) $countRaw;

        if ($count <= 0 || $topicId <= 0) {
            continue;
        }

        $ids = Question::query()
            ->where('topic_id', $topicId)
            ->inRandomOrder()
            ->limit($count)
            ->pluck('id')
            ->toArray();

        $selected = array_merge($selected, $ids);
    }

            // De-duplicate.
    $selected = array_values(array_unique($selected));

    // Enforce quiz->question_limit, but only by limiting what we RETURN.
    // This ensures the "limit" affects which questions are used for answering,
    // not which questions exist in the quiz bank.
    $limit = $quiz->question_limit ?? null;
    if ($limit !== null && (int) $limit > 0) {
        $selected = array_slice($selected, 0, (int) $limit);
    }

    return $selected;
}


//Add this method to your StudentController.php

public function getStudentDetails($studentId)

{
    try {
        $student = Student::with(['enrollments.course', 'results.quiz'])->findOrFail($studentId);

        // Get enrolled courses with progress
        $enrolledCourses = $student->enrollments->map(function ($enrollment) {
            $course = $enrollment->course;

            // Get completed quizzes for this course
            $completedQuizzes = Result::where('student_id', $enrollment->student_id ?? $enrollment->user_id ?? $student->id)
                ->whereHas('quiz', function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                })
                ->where('passed', 1)
                ->count();

            $totalQuizzes = Quiz::where('course_id', $course->id)->count();

            return [
                'id' => $course->id,
                'title' => $course->title,
                'code' => $course->code ?? 'N/A',
                'enrolled_at' => $enrollment->created_at?->format('M d, Y'),
                'progress' => $totalQuizzes > 0 ? round(($completedQuizzes / $totalQuizzes) * 100) : 0,
                'completed_quizzes' => $completedQuizzes,
                'total_quizzes' => $totalQuizzes,
            ];
        });

        // Get completed courses (courses where all quizzes are passed)
        $completedCourses = $enrolledCourses->filter(fn ($course) => ($course['progress'] ?? 0) >= 100);

        // If payments() relation exists, include it. Otherwise return empty array.
        $paymentHistory = [];
        if (method_exists($student, 'payments')) {
            try {
                $paymentHistory = $student->payments()
                    ->orderBy('created_at', 'desc')
                    ->get(['amount', 'status', 'reference', 'created_at'])
                    ->map(function ($payment) {
                        return [
                            'amount' => number_format($payment->amount, 2),
                            'status' => $payment->status,
                            'reference' => $payment->reference,
                            'date' => $payment->created_at?->format('M d, Y h:i A'),
                        ];
                    })->values()->all();
            } catch (\Throwable $e) {
                $paymentHistory = [];
            }
        }

        // Get recent results
        $recentResults = Result::where('student_id', $student->id)
            ->with('quiz')
            ->latest('completed_at')
            ->take(5)
            ->get()
            ->map(function ($result) {
                return [
                    'quiz_title' => $result->quiz->title,
                    'score' => round($result->percentage),
                    'passed' => $result->passed,
                    'completed_at' => $result->completed_at?->format('M d, Y'),
                ];
            });

        // Calculate statistics
        $stats = [
            'total_courses' => $enrolledCourses->count(),
            'completed_courses' => $completedCourses->count(),
            'total_quizzes_taken' => Result::where('student_id', $student->id)->count(),
            'average_score' => round(Result::where('student_id', $student->id)->avg('percentage') ?? 0, 1),
            'total_paid' => 0,
        ];

        return response()->json([
            'success' => true,
            'student' => [
                'id' => $student->id,
                'fullname' => ($student->firstname ?? '') . ' ' . ($student->lastname ?? ''),
                'firstname' => $student->firstname,
                'lastname' => $student->lastname,
                'email' => $student->email,
                'phone' => $student->phone ?? 'Not provided',
                'program' => $student->program ?? 'Not specified',
                'registration_date' => $student->created_at?->format('F d, Y'),
                'status' => $student->status ?? 'active',
                'avatar' => $student->avatar ?? ('https://ui-avatars.com/api/?background=6366f1&color=fff&name=' . urlencode(($student->firstname ?? '') . ' ' . ($student->lastname ?? ''))),
            ],
            'enrolled_courses' => $enrolledCourses,
            'completed_courses_count' => $completedCourses->count(),
            'payment_history' => $paymentHistory,
            'recent_results' => $recentResults,
            'statistics' => $stats,
        ]);
    } catch (\Throwable $e) {
        \Log::error('Failed to get student details', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'student_id' => $studentId,
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to retrieve student details: ' . $e->getMessage(),
        ], 500);
    }
}


public function updateStudent(Request $request, $studentId)
{
    try {
        \Log::info('Updating student', ['student_id' => $studentId]);
        
        $student = Student::findOrFail($studentId);
        
        // Validate the request
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $studentId,
            'phone' => 'nullable|string|max:20',
            'program' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,suspended,graduated',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Update basic info
        $student->firstname = $validated['firstname'];
        $student->lastname = $validated['lastname'];
        $student->email = $validated['email'];
        $student->phone = $validated['phone'] ?? null;
        $student->program = $validated['program'] ?? null;
        $student->status = $validated['status'];
        
        // Update password if provided
        if (!empty($validated['password'])) {
            $student->password = Hash::make($validated['password']);
        }
        
        $student->save();
        
        \Log::info('Student updated successfully', ['student_id' => $studentId]);
        
        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully',
            'student' => [
                'id' => $student->id,
                'firstname' => $student->firstname,
                'lastname' => $student->lastname,
                'email' => $student->email,
                'phone' => $student->phone,
                'program' => $student->program,
                'status' => $student->status,
                'fullname' => $student->firstname . ' ' . $student->lastname,
            ]
        ]);
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        \Log::error('Failed to update student', [
            'student_id' => $studentId,
            'error' => $e->getMessage()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to update student: ' . $e->getMessage()
        ], 500);
    }
}

}





