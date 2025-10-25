<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Option;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\pagination\Paginator;

class QuestionController extends Controller
{
    public function import(Request $request, $quiz)
{
    \Log::info('Import process started', ['quiz_id' => $quiz]);

    try {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx,xls',
        ]);

        \Log::debug('File validation passed', [
            'original_name' => $request->file('file')->getClientOriginalName(),
            'size' => $request->file('file')->getSize(),
            'mime_type' => $request->file('file')->getMimeType()
        ]);

        $extension = $request->file('file')->getClientOriginalExtension();
        \Log::debug('File extension detected', ['extension' => $extension]);

        if (in_array($extension, ['csv', 'txt'])) {
            \Log::info('Processing CSV file');
            $this->importFromCsv($request->file('file')->getRealPath(), $quiz);
        } else {
            \Log::info('Processing Excel file');
            $this->importFromExcel($request->file('file'), $quiz);
        }

        \Log::info('Import process completed successfully', ['quiz_id' => $quiz]);

        return redirect()->route('questions.index', $quiz)
                        ->with('success', 'Questions imported successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed during import', [
            'errors' => $e->errors(),
            'quiz_id' => $quiz
        ]);
        throw $e;

    } catch (\Exception $e) {
        \Log::error('Import process failed', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'quiz_id' => $quiz
        ]);

        return redirect()->back()
                        ->with('error', 'Import failed: ' . $e->getMessage());
    }
}

private function importFromCsv($path, $quiz)
{
    \Log::debug('Starting CSV import', ['file_path' => $path, 'quiz_id' => $quiz]);

    $file = fopen($path, 'r');
    if (!$file) {
        \Log::error('Failed to open CSV file', ['file_path' => $path]);
        throw new \Exception("Could not open CSV file");
    }

    $header = fgetcsv($file);
    \Log::debug('CSV header', ['header' => $header]);

    $rowCount = 0;
    $successCount = 0;
    $errorCount = 0;

    while (($row = fgetcsv($file, 1000, ',')) !== FALSE) {
        $rowCount++;

        try {
            \Log::debug('Processing CSV row', ['row_number' => $rowCount, 'data' => $row]);

            if (count($row) < 7) {
                \Log::warning('Skipping incomplete row', [
                    'row_number' => $rowCount,
                    'columns_count' => count($row),
                    'expected_columns' => 7
                ]);
                $errorCount++;
                continue;
            }

            // CORRECTED VARIABLE ASSIGNMENT
            $questionText = $row[0];
            $opt1 = $row[1];
            $opt2 = $row[2];
            $opt3 = $row[3];
            $opt4 = $row[4];
            $correct = $row[5];
            $points = $row[6];

            $question = Question::create([
                'quiz_id' => $quiz,
                'question_text' => $questionText,
                'option_a' => $opt1,
                'option_b' => $opt2,
                'option_c' => $opt3,
                'option_d' => $opt4,
                'correct_option' => $correct,
                'points' => (int) $points,
            ]);

            $successCount++;
            \Log::debug('Question created successfully', [
                'row_number' => $rowCount,
                'question_id' => $question->id
            ]);

        } catch (\Exception $e) {
            $errorCount++;
            \Log::error('Error processing CSV row', [
                'row_number' => $rowCount,
                'error' => $e->getMessage(),
                'data' => $row
            ]);
        }
    }

    fclose($file);

    \Log::info('CSV import completed', [
        'total_rows' => $rowCount,
        'successful' => $successCount,
        'failed' => $errorCount,
        'quiz_id' => $quiz
    ]);
}

private function importFromExcel($file, $quiz)
{
    \Log::debug('Starting Excel import', [
        'file_name' => $file->getClientOriginalName(),
        'quiz_id' => $quiz
    ]);

    try {
        $rows = Excel::toArray([], $file)[0]; // first sheet
        \Log::debug('Excel file loaded', ['total_rows' => count($rows)]);

        if (empty($rows)) {
            \Log::warning('Excel file is empty');
            return;
        }

        $header = $rows[0];
        \Log::debug('Excel header', ['header' => $header]);

        unset($rows[0]); // remove header row

        $rowCount = 0;
        $successCount = 0;
        $errorCount = 0;

        foreach ($rows as $index => $row) {
            $rowCount++;

            try {
                \Log::debug('Processing Excel row', [
                    'row_number' => $rowCount,
                    'excel_index' => $index,
                    'data' => $row
                ]);

                if (count($row) < 7) {
                    \Log::warning('Skipping incomplete row', [
                        'row_number' => $rowCount,
                        'columns_count' => count($row),
                        'expected_columns' => 7
                    ]);
                    $errorCount++;
                    continue;
                }

                // CORRECTED VARIABLE ASSIGNMENT
                $questionText = $row[0];
                $opt1 = $row[1];
                $opt2 = $row[2];
                $opt3 = $row[3];
                $opt4 = $row[4];
                $correct = $row[5];
                $points = $row[6];

                $question = Question::create([
                    'quiz_id' => $quiz,
                    'question_text' => $questionText,
                    'option a' => $opt1,
                    'option b' => $opt2,
                    'option c' => $opt3,
                    'option d' => $opt4,
                    'correct_option' => $correct,
                    'points' => (int) $points,
                ]);

                $successCount++;
                \Log::debug('Question created successfully', [
                    'row_number' => $rowCount,
                    'question_id' => $question->id
                ]);

            } catch (\Exception $e) {
                $errorCount++;
                \Log::error('Error processing Excel row', [
                    'row_number' => $rowCount,
                    'excel_index' => $index,
                    'error' => $e->getMessage(),
                    'data' => $row
                ]);
            }
        }

        \Log::info('Excel import completed', [
            'total_rows' => $rowCount,
            'successful' => $successCount,
            'failed' => $errorCount,
            'quiz_id' => $quiz
        ]);

    } catch (\Exception $e) {
        \Log::error('Failed to process Excel file', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'quiz_id' => $quiz
        ]);
        throw new \Exception("Excel processing failed: " . $e->getMessage());
    }
}
    // List questions for a specific quiz
    public function index($quiz)
    {
        $quiz = Quiz::findOrFail($quiz);

        $questions = Question::where('quiz_id', $quiz->id)
                             ->paginate(5);
        return view('questions.manage', compact('questions', 'quiz'));
    }

    // Show create question form
    // In your controller
    public function create(Quiz $quiz) // Type-hint the Quiz model
    {
        return view('questions.questionform', [
            // 'quiz' => $quizId->id,
            'quiz' => $quiz,
        ]);
    }

    // Store a new question
   public function store(Request $request, $quiz)
    {
        $data = $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|integer|min:0',
            'points' => 'nullable|integer|min:1',
        ]);

        $question = Question::create([
            'quiz_id' => $quiz,
            'question_text' => $data['question_text'],
            'points' => $data['points'] ?? 1,
            'correct_option' => $data['correct_option'],
        ]);

        // Save options - assuming you have an options relationship
        foreach ($data['options'] as $index => $optionText) {
            $question->options()->create([
                'option_text' => $optionText,
                'is_correct' => $index == $data['correct_option']
            ]);
        }

        return redirect()->route('questions.index', $quiz)
                        ->with('success', 'Question added successfully!');
    }

    public function update($quiz, $id)
    {
        $quiz = Quiz::findOrFail($quiz);
        $question = Question::with('options')->findOrFail($id);
        return view('questions.edit', compact('question', 'quiz'));
    }

    public function edit(Request $request, $quiz, $id)
    {
        $data = $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'reqired|string',
            'correct_option' => 'required|integer|min:0',
            'points' => 'nullable|integer|min:1',
        ]);

        $question = Question::findOrFail($id);
        $question->update([
            'question_text' => $data['question_text'],
            'points' => $data['points'] ?? 1,
        ]);

        // Update options
        foreach ($data['options'] as $index => $optionText) {
            $option = Option::where('question_id', $question->id)
                            ->where('id', $request->input("option_ids.$index"))
                            ->first();

            if ($option) {
                $option->update([
                    'option_text' => $optionText,
                    'is_correct' => $index == $data['correct_option'],
                ]);
            } else {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $optionText,
                    'is_correct' => $index == $data['correct_option'],
                ]);
            }
        }

        return redirect()->route('questions.index', $quiz)
                         ->with('success', 'Question updated successfully!');
}
}
