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


    return view('quiz', compact('quizzes'));
}

public function showQuiz(Quiz $quiz)
{
    $questions = $quiz->questions()->inRandomOrder()->get();

    foreach ($questions as $question) {
        // This gives you the actual correct answer text
        $question->correct_answer_text = $question->{$question->correct_option};
    }

    return view('question', compact('quiz', 'questions'));
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

    // Save results in session
    session([
        'quiz_result' => [
            'quiz'    => $quiz->id,
            'score'   => $score,
            'total'   => $quiz->questions->count(),
            'details' => $details,
        ]
    ]);

    // Redirect to results page
    return redirect()->route('quiz.results', $quiz->id);
}




public function results(Quiz $quiz)
{
    $sessionResult = session('quiz_result');
    //dd($sessionResult);
    if (!$sessionResult || $sessionResult['quiz'] != $quiz->id) {
        return redirect()->route('quizzes.index')
            ->with('error', 'No result found for this quiz.');
    }

    return view('result', compact('quiz', 'sessionResult'));
}
}
