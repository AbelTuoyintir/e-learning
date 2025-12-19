<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Result;

class StudentController extends Controller
{
    //
    // StudentController.php
public function dashboard()
{
    $quizzes = Quiz::withCount('questions')
                 ->whereHas('questions') // Only quizzes with questions
                 ->get();


    return view('students.quiz', compact('quizzes'));
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
}
