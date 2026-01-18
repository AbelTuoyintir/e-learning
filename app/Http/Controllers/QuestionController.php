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

        $successCount = 0;
        if (in_array($extension, ['csv', 'txt'])) {
            \Log::info('Processing CSV file');
            $successCount = $this->importFromCsv($request->file('file')->getRealPath(), $quiz);
        } else {
            \Log::info('Processing Excel file');
            $successCount = $this->importFromExcel($request->file('file'), $quiz);
        }

        \Log::info('Import process completed successfully', [
            'quiz_id' => $quiz,
            'imported_count' => $successCount
        ]);

        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Questions imported successfully!',
                'count' => $successCount
            ]);
        }

        return redirect()->route('questions.index', $quiz)
                        ->with('success', 'Questions imported successfully! ' . $successCount . ' questions added.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation failed during import', [
            'errors' => $e->errors(),
            'quiz_id' => $quiz
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        throw $e;

    } catch (\Exception $e) {
        \Log::error('Import process failed', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'quiz_id' => $quiz
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }

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

    // Read and validate header
    $header = fgetcsv($file);
    \Log::debug('CSV header', ['header' => $header]);

    if (empty($header)) {
        fclose($file);
        throw new \Exception("CSV file is empty or has invalid format");
    }

    $rowCount = 0;
    $successCount = 0;
    $errorCount = 0;
    $errors = [];

    while (($row = fgetcsv($file, 0, ',')) !== FALSE) {
        $rowCount++;

        try {
            // Skip empty rows
            if (empty($row) || count(array_filter($row, 'strlen')) === 0) {
                \Log::debug('Skipping empty row', ['row_number' => $rowCount]);
                continue;
            }

            // Pad row to ensure at least 7 elements
            $row = array_pad($row, 7, '');

            \Log::debug('Processing CSV row', ['row_number' => $rowCount, 'data' => $row]);

            $questionText = trim($row[0]);
            $opt1 = trim($row[1]);
            $opt2 = trim($row[2]);
            $opt3 = trim($row[3]);
            $opt4 = trim($row[4]);
            $correct = trim($row[5]);
            $points = trim($row[6]);

            // Validate required fields
            if (empty($questionText)) {
                $errors[] = "Row $rowCount: Empty question text";
                $errorCount++;
                continue;
            }

            // Validate options are not empty
            if (empty($opt1) || empty($opt2) || empty($opt3) || empty($opt4)) {
                $errors[] = "Row $rowCount: One or more options are empty";
                $errorCount++;
                continue;
            }

            // Convert numeric correct_option to letter if needed
            $correctOption = $this->normalizeCorrectOption($correct, $rowCount);

            \Log::debug('Creating question', [
                'row' => $rowCount,
                'question' => substr($questionText, 0, 30) . '...',
                'correct' => $correctOption,
                'points' => $points
            ]);

            $question = Question::create([
                'quiz_id' => $quiz,
                'question_text' => $questionText,
                'option_a' => $opt1,
                'option_b' => $opt2,
                'option_c' => $opt3,
                'option_d' => $opt4,
                'correct_option' => $correctOption, // This is now a string
                'points' => (int) $points ?: 1,
            ]);

            $successCount++;

        } catch (\Exception $e) {
            $errorCount++;
            $errors[] = "Row $rowCount: " . $e->getMessage();
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

    // Log all errors
    if (!empty($errors)) {
        \Log::warning('Import errors', ['errors' => $errors]);
    }
}

private function importFromExcel($file, $quiz)
{
    \Log::debug('Starting Excel import', [
        'file_name' => $file->getClientOriginalName(),
        'quiz_id' => $quiz
    ]);

    try {
        $rows = Excel::toArray([], $file)[0];
        \Log::debug('Excel file loaded', ['total_rows' => count($rows)]);

        if (empty($rows) || count($rows) < 2) {
            \Log::warning('Excel file is empty or has no data rows');
            return;
        }

        // Skip header row
        $header = $rows[0];
        \Log::debug('Excel header', ['header' => $header]);
        unset($rows[0]);

        $rowCount = 0;
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $rowCount++;

            try {
                // Skip empty rows
                if (empty($row) || count(array_filter($row, 'strlen')) === 0) {
                    \Log::debug('Skipping empty row', ['row_number' => $rowCount]);
                    continue;
                }

                // Pad row to ensure at least 7 elements
                $row = array_pad($row, 7, '');

                \Log::debug('Processing Excel row', [
                    'row_number' => $rowCount,
                    'excel_index' => $index,
                    'data' => $row
                ]);

                $questionText = trim($row[0] ?? '');
                $opt1 = trim($row[1] ?? '');
                $opt2 = trim($row[2] ?? '');
                $opt3 = trim($row[3] ?? '');
                $opt4 = trim($row[4] ?? '');
                $correct = trim($row[5] ?? '');
                $points = trim($row[6] ?? 1);

                // Validate required fields
                if (empty($questionText)) {
                    $errors[] = "Row $rowCount: Empty question text";
                    $errorCount++;
                    continue;
                }

                // Validate options are not empty
                if (empty($opt1) || empty($opt2) || empty($opt3) || empty($opt4)) {
                    $errors[] = "Row $rowCount: One or more options are empty";
                    $errorCount++;
                    continue;
                }

                // Convert numeric correct_option to letter if needed
                $correctOption = $this->normalizeCorrectOption($correct, $rowCount);

                $question = Question::create([
                    'quiz_id' => $quiz,
                    'question_text' => $questionText,
                    'option_a' => $opt1,
                    'option_b' => $opt2,
                    'option_c' => $opt3,
                    'option_d' => $opt4,
                    'correct_option' => $correctOption, // This is now a string
                    'points' => (int) $points ?: 1,
                ]);

                $successCount++;

            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Row $rowCount: " . $e->getMessage();
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

        // Log all errors
        if (!empty($errors)) {
            \Log::warning('Import errors', ['errors' => $errors]);
        }

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

/**
 * Helper function to normalize correct_option values
 * Converts numbers to letters (1->A, 2->B, etc.)
 */
private function normalizeCorrectOption($value, $rowNumber)
{
    $value = trim((string) $value);

    // If it's already A, B, C, D, return as is
    if (in_array(strtoupper($value), ['A', 'B', 'C', 'D'])) {
        return strtoupper($value);
    }

    // Convert numeric values to letters
    $numberMap = [
        '1' => 'A',
        '2' => 'B',
        '3' => 'C',
        '4' => 'D'
    ];

    if (isset($numberMap[$value])) {
        \Log::debug("Converted numeric correct_option '$value' to '{$numberMap[$value]}'", ['row' => $rowNumber]);
        return $numberMap[$value];
    }

    // If value is invalid, default to 'A' but log warning
    \Log::warning("Invalid correct_option value '$value' in row $rowNumber, defaulting to 'A'");
    return 'A';
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
