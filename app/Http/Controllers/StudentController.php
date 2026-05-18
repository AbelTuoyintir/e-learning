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
    $student = Student::find(auth()->id());

    // Average score
    $results = Result::where('student_id', $student->id)->get();
    $averageScore = $results->count() > 0 ? $results->avg(function ($result) {
        return ($result->score / $result->quiz->questions->count()) * 100;
    }) : 0;

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

    return view('students.dashboard', compact(
        'enrolledCoursesCount',
        'completedQuizzesCount',
        'averageScore',
        'recentResults',
        'availableQuizzes'
    ));
}

public function showQuiz(Quiz $quiz)
{
    $quiz->loadMissing('module', 'topic.module');

    if (!$this->canAccessQuiz($quiz)) {
        return $this->denyQuizAccess($quiz);
    }

    $questions = $quiz->questions()->get()->unique('question_text')->shuffle();

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

            foreach ($quiz->questions as $question) {
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
                'total_possible_points' => $totalPossiblePoints, // NEW: Store total possible points
                'percentage' => $percentage,
                'passed' => $passed,
                'attempt_number' => $attemptNumber,
                'completed_at' => now(),
                'details' => json_encode($details),
            ]);

            \Log::info('Result created', [
                'result_id' => $result->id,
                'quiz_id' => $result->quiz_id,
                'student_id' => $result->student_id,
                'score' => $result->score,
                'percentage' => $result->percentage,
            ]);

            // Dispatch notifications asynchronously
            $this->sendNotifications($result, $quiz, $score, $totalPossiblePoints, $percentage, $passed);

            // Log for debugging
            $this->logQuizSubmission($quiz, $score, $totalPossiblePoints, $validated['answers']);

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
    // Check if quiz has custom pass percentage
    $passPercentage = $quiz->pass_percentage ?? 70;
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
}
