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
    $score = 0;
    $details = [];

    // Load all questions for this quiz
    $quiz->load('questions');

    foreach ($quiz->questions as $question) {
        // Get user's submitted answer for this question (like "option_a")
        $userAnswer = $request->input("answers.{$question->id}");

        // Determine correctness - compare the actual option value
        $isCorrect = false;
        $userAnswerText = null;

        if ($userAnswer) {
            // Get the text of the user's answer (e.g., $question->option_a)
            $userAnswerText = $question->{$userAnswer};

            // Get the correct option column name (e.g., "option_a")
            $correctOptionColumn = $this->getOptionColumn($question->correct_option);

            // Compare if user selected the correct option column
            $isCorrect = $userAnswer === $correctOptionColumn;

            if ($isCorrect) {
                $score++;
            }
        }

        // Get the correct option column name
        $correctOptionColumn = $this->getOptionColumn($question->correct_option);

        // Get the text of the correct answer
        $correctAnswerText = $question->{$correctOptionColumn};

        $details[] = [
            'question'       => $question->question_text,
            'options'        => [
                'A' => $question->option_a,
                'B' => $question->option_b,
                'C' => $question->option_c,
                'D' => $question->option_d,
            ],
            'your_answer'    => $userAnswerText,
            'correct_answer' => $correctAnswerText,
            'is_correct'     => $isCorrect,
            'skipped'        => is_null($userAnswer),
            // Debug info to help troubleshoot
            'debug' => [
                'user_selected' => $userAnswer,
                'correct_letter' => $question->correct_option,
                'correct_column' => $correctOptionColumn,
                'your_answer_text' => $userAnswerText,
                'correct_answer_text' => $correctAnswerText,
            ]
        ];
    }

    // Determine if passed (e.g., 70% or more)
    $totalQuestions = $quiz->questions->count();
    $percentage = ($totalQuestions > 0) ? ($score / $totalQuestions) * 100 : 0;
    $passed = $percentage >= 70;

    // Get attempt number
    $attemptNumber = Result::where('student_id', Auth::id())
        ->where('quiz_id', $quiz->id)
        ->max('attempt_number') ?? 0;
    $attemptNumber++;

    // Save to database
    $result = Result::create([
        'student_id' => Auth::id(),
        'quiz_id' => $quiz->id,
        'score' => $score,
        'passed' => $passed,
        'attempt_number' => $attemptNumber,
        'completed_at' => now(),
        'details' => json_encode($details),
    ]);

    // Send email notification
    try {
        Mail::to(Auth::user()->email)->send(new StudentResultMail($result, $quiz));
    } catch (\Exception $e) {
        \Log::error('Failed to send quiz result email: ' . $e->getMessage());
    }

    // Create in-app notification
    Notification::create([
        'student_id' => Auth::id(),
        'title' => 'Quiz Completed!',
        'message' => "You have completed the quiz '{$quiz->title}' with a score of {$score}/{$totalQuestions} (" . number_format($percentage, 2) . "%). " . ($passed ? 'Congratulations!' : 'Keep practicing!'),
        'type' => $passed ? 'success' : 'warning',
        'is_read' => false,
    ]);

    // Log the result for debugging
    \Log::info('Quiz submitted', [
        'quiz_id' => $quiz->id,
        'student_id' => Auth::id(),
        'score' => $score,
        'total_questions' => $totalQuestions,
        'percentage' => $percentage,
        'passed' => $passed
    ]);

    // Redirect to results page
    return redirect()->route('quiz.results', $quiz->id);
}

/**
 * Helper function to convert letter (A, B, C, D) to column name (option_a, option_b, etc.)
 */
private function getOptionColumn($letter)
{
    $letter = strtoupper(trim($letter));

    $mapping = [
        'A' => 'option_a',
        'B' => 'option_b',
        'C' => 'option_c',
        'D' => 'option_d'
    ];

    return $mapping[$letter] ?? 'option_a'; // default to option_a if invalid
}




public function results(Quiz $quiz)
{
    $result = Result::where('student_id', Auth::id())
        ->where('quiz_id', $quiz->id)
        ->latest('completed_at')
        ->first();

    if (!$result) {
        return redirect()->route('quizzes.index')
            ->with('error', 'No result found for this quiz.');
    }

    // Load the quiz relationship
    $result->load('quiz');

    // Safely decode details from JSON
    $result->details = json_decode($result->details, true) ?? [];

    // Calculate total questions from quiz instead of details count
    $totalQuestions = $result->quiz->questions->count();

    // Prepare sessionResult for the view
    $sessionResult = [
        'details' => $result->details,
        'score' => $result->score,
        'total' => $totalQuestions,
        'percentage' => $totalQuestions > 0 ? ($result->score / $totalQuestions) * 100 : 0,
        'passed' => $result->passed,
    ];

    return view('students.result', compact('quiz', 'result', 'sessionResult'));
}

public function resultsIndex()
{
    $student = Auth::user();

    $results = Result::where('student_id', $student->id)
        ->with(['quiz' => function($query) {
            $query->select('id', 'title', 'description', 'time_limit', 'pass_percentage');
        }])
        ->select('id', 'quiz_id', 'score', 'passed', 'attempt_number', 'completed_at')
        ->latest('completed_at')
        ->paginate(10); // Add pagination for better performance

    return view('students.results', compact('results'));
}

public function resultShow(Result $result)
{
    // Ensure the result belongs to the authenticated student
    if ($result->student_id !== Auth::id()) {
        abort(403);
    }

    // Load the quiz relationship with questions count
    $result->load(['quiz' => function($query) {
        $query->withCount('questions');
    }]);

    // Safely decode details from JSON
    $result->details = json_decode($result->details, true) ?? [];

    $quiz = $result->quiz;

    // Calculate total from quiz questions count
    $totalQuestions = $quiz->questions_count;

    // Prepare sessionResult for the view
    $sessionResult = [
        'details' => $result->details,
        'score' => $result->score,
        'total' => $totalQuestions,
        'percentage' => $totalQuestions > 0 ? ($result->score / $totalQuestions) * 100 : 0,
        'passed' => $result->passed,
    ];

    return view('students.result', compact('result', 'quiz', 'sessionResult'));
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
