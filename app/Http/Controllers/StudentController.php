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
    $questions = $quiz->questions()->inRandomOrder()->get();

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

        // Determine correctness
        $isCorrect = $userAnswer === $question->correct_option;
        if ($isCorrect) {
            $score++;
        }

       $details[] = [
        'question'       => $question->question_text,
        'options'        => [
            'A' => $question->option_a,
            'B' => $question->option_b,
            'C' => $question->option_c,
            'D' => $question->option_d,
        ],
        'your_answer'    => $userAnswer,  // ✅ not null anymore
        'correct_answer' => $question->correct_option,
        'is_correct'     => $userAnswer === $question->correct_option,
        'skipped'        => is_null($userAnswer),

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
        'message' => "You have completed the quiz '{$quiz->title}' with a score of {$score}/{$totalQuestions} ({$percentage}%). " . ($passed ? 'Congratulations!' : 'Keep practicing!'),
        'type' => $passed ? 'success' : 'warning',
        'is_read' => false,
    ]);

    // Redirect to results page
    return redirect()->route('quiz.results', $quiz->id);
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

    // Decode details from JSON
    $result->details = json_decode($result->details, true);

    return view('students.result', compact('quiz', 'result'));
}

public function resultsIndex()
{
    $student = Auth::user();
    $results = Result::where('student_id', $student->id)
        ->with('quiz')
        ->latest('completed_at')
        ->get();

    return view('students.results', compact('results'));
}

public function resultShow(Result $result)
{
    // Ensure the result belongs to the authenticated student
    if ($result->student_id !== Auth::id()) {
        abort(403);
    }

    $result->details = json_decode($result->details, true);
    $quiz = $result->quiz;

    return view('students.result', compact('result', 'quiz'));
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
}
