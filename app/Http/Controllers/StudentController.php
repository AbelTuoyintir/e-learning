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
    $availableQuizzes = Quiz::withCount('questions')
        ->whereHas('questions')
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
    $questions = $quiz->questions()->get()->unique('question_text')->shuffle();

    foreach ($questions as $question) {
        // This gives you the actual correct answer text
        $question->correct_answer_text = $question->{$question->correct_option};
    }

    return view('students.question', compact('quiz', 'questions'));
}


public function submit(Request $request, Quiz $quiz)
{
    \Log::info('=== QUIZ SUBMIT START ===', [
        'quiz_id' => $quiz->id,
        'quiz_title' => $quiz->title,
        'student_id' => Auth::id(),
        'answers_count' => count($request->input('answers', [])),
    ]);

    // Validate the request
    $validated = $request->validate([
        'answers' => 'required|array',
        'answers.*' => 'nullable|string|in:A,B,C,D',
    ]);

    \Log::debug('Validation passed', ['answers_keys' => array_keys($validated['answers'])]);

    try {
        // Use database transaction for data integrity
        return DB::transaction(function () use ($request, $quiz, $validated) {
            $score = 0;
            $details = [];

            // Eager load questions to avoid N+1 query
            $quiz->load(['questions' => function($query) {
                $query->select('id', 'quiz_id', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option');
            }]);

            \Log::debug('Questions loaded', ['count' => $quiz->questions->count()]);

            foreach ($quiz->questions as $question) {
                $userAnswerLetter = strtoupper(trim($request->input("answers.{$question->id}", '')));
                $correctLetter = strtoupper(trim($question->correct_option));

                $isCorrect = false;
                $userAnswerText = null;
                $userAnswerColumn = null;

                // Validate user answer
                if ($this->isValidAnswer($userAnswerLetter)) {
                    $userAnswerColumn = $this->getOptionColumn($userAnswerLetter);
                    $userAnswerText = $question->{$userAnswerColumn};

                    $isCorrect = ($userAnswerLetter === $correctLetter);

                    if ($isCorrect) {
                        $score++;
                    }
                }

                // Prepare answer details
                $details[] = $this->prepareAnswerDetails($question, $userAnswerText, $isCorrect, $userAnswerLetter);
            }

            \Log::debug('Scoring complete', ['score' => $score, 'total' => $quiz->questions->count()]);

            $totalQuestions = $quiz->questions->count();
            $percentage = $this->calculatePercentage($score, $totalQuestions);
            $passed = $this->isPassed($percentage, $quiz);
            $attemptNumber = $this->getNextAttemptNumber($quiz->id);

            \Log::debug('Creating result', [
                'score' => $score,
                'total' => $totalQuestions,
                'percentage' => $percentage,
                'passed' => $passed,
                'attempt' => $attemptNumber,
            ]);

            // Create result with validated data
            $result = Result::create([
                'student_id' => Auth::id(),
                'quiz_id' => $quiz->id,
                'score' => $score,
                'passed' => $passed,
                'attempt_number' => $attemptNumber,
                'completed_at' => now(),
                'details' => json_encode($details),
            ]);

            \Log::info('Result created', [
                'result_id' => $result->id,
                'quiz_id' => $result->quiz_id,
                'student_id' => $result->student_id,
            ]);

            // Dispatch notifications asynchronously
            $this->sendNotifications($result, $quiz, $score, $totalQuestions, $percentage, $passed);

            // Log for debugging
            $this->logQuizSubmission($quiz, $score, $totalQuestions, $request->input('answers'));

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
        ]);

        return redirect()->back()
            ->with('error', 'Failed to submit quiz. Please try again.')
            ->withInput();
    }
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
private function prepareAnswerDetails($question, $userAnswerText, $isCorrect, $userAnswerLetter): array
{
    $correctLetter = strtoupper(trim($question->correct_option));
    $correctOptionColumn = $this->getOptionColumn($correctLetter);

    return [
        'question'       => $question->question_text,
        'options'        => [
            'A' => $question->option_a,
            'B' => $question->option_b,
            'C' => $question->option_c,
            'D' => $question->option_d,
        ],
        'your_answer'    => $userAnswerText,
        'correct_answer' => $question->{$correctOptionColumn},
        'is_correct'     => $isCorrect,
        'skipped'        => empty($userAnswerLetter) || !$this->isValidAnswer($userAnswerLetter),
        'correct_letter' => $correctLetter,
        'user_letter'    => $userAnswerLetter,
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
private function getNextAttemptNumber($quizId): int
{
    $lastAttempt = Result::where('student_id', Auth::id())
        ->where('quiz_id', $quizId)
        ->max('attempt_number');

    return ($lastAttempt ?? 0) + 1;
}

/**
 * Send notifications
 */
private function sendNotifications($result, $quiz, $score, $total, $percentage, $passed): void
{
    // Email notification
    try {
        Mail::to(Auth::user()->email)->send(new StudentResultMail($result, $quiz));
    } catch (\Exception $e) {
        \Log::error('Email failed: ' . $e->getMessage());
    }

    // In-app notification
    try {
        Notification::create([
            'student_id' => Auth::id(),
            'title' => 'Quiz Completed',
            'message' => "You scored {$score}/{$total} ({$percentage}%) on {$quiz->title}",
            'type' => $passed ? 'success' : 'warning',
            'is_read' => false,
        ]);
    } catch (\Exception $e) {
        \Log::error('Notification failed: ' . $e->getMessage());
    }
}

/**
 * Log quiz submission
 */
private function logQuizSubmission($quiz, $score, $total, $answers): void
{
    \Log::info('Quiz submission logged', [
        'quiz' => $quiz->title,
        'score' => $score,
        'total' => $total,
    ]);
}


public function results(Quiz $quiz)
{
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
        $query->select('id', 'title', 'description', 'time_limit')
              ->withCount('questions');
    }]);

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
private function processResultDetails(Result &$result): void
{
    // Handle different data formats
    if (is_string($result->details)) {
        $result->details = json_decode($result->details, true);
    }

    // Ensure details is an array
    $result->details = is_array($result->details) ? $result->details : [];

    // Validate and sanitize details structure
    foreach ($result->details as &$detail) {
        $detail = $this->sanitizeAnswerDetail($detail);
    }
}

/**
 * Sanitize answer detail structure
 */
private function sanitizeAnswerDetail(array $detail): array
{
    return [
        'question' => $detail['question'] ?? 'Question not available',
        'options' => $detail['options'] ?? [],
        'your_answer' => $detail['your_answer'] ?? null,
        'correct_answer' => $detail['correct_answer'] ?? null,
        'is_correct' => $detail['is_correct'] ?? false,
        'skipped' => $detail['skipped'] ?? true,
        'correct_letter' => $detail['correct_letter'] ?? ($detail['debug']['correct_letter'] ?? ''),
        'user_letter' => $detail['user_letter'] ?? ($detail['debug']['user_selected_letter'] ?? ''),
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
}
