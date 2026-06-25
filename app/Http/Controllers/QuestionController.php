<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
{
    public function index($quiz)
    {
        $quiz = Quiz::findOrFail($quiz);
        $questionCount = Question::where('quiz_id', $quiz->id)->count();
        $questionLimit = $this->resolveQuestionLimit($quiz);

        $questions = Question::where('quiz_id', $quiz->id)
            ->latest()
            ->paginate(20);

        return view('questions.manage', compact('questions', 'quiz', 'questionCount', 'questionLimit'));
    }

    public function create(Quiz $quiz)
    {
        return view('questions.questionform', ['quiz' => $quiz]);
    }

    public function store(Request $request, $quiz)
    {
        $quiz = Quiz::findOrFail($quiz);

        if (! $this->canAddMoreQuestions($quiz)) {
            return redirect()->back()->withInput()->with(
                'error',
                "Question bank limit reached for this quiz ({$this->resolveQuestionLimit($quiz)} questions)."
            );
        }

        $data = $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|string',
            'points' => 'nullable|integer|min:1',
        ]);

        Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => $data['question_text'],
            'option_a' => $data['option_a'],
            'option_b' => $data['option_b'],
            'option_c' => $data['option_c'],
            'option_d' => $data['option_d'],
            'correct_option' => $this->normalizeCorrectOption($data['correct_option'], 0),
            'points' => $data['points'] ?? 1,
        ]);

        return redirect()->route('questions.index', $quiz->id)
            ->with('success', 'Question added successfully!');
    }

    public function edit($quiz, $question)
    {
        $quiz = Quiz::findOrFail($quiz);
        $question = Question::where('quiz_id', $quiz->id)->findOrFail($question);

        return view('questions.edit', compact('question', 'quiz'));
    }

    public function update(Request $request, $quiz, $question)
    {
        $quiz = Quiz::findOrFail($quiz);
        $question = Question::where('quiz_id', $quiz->id)->findOrFail($question);

        $data = $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|string',
            'points' => 'nullable|integer|min:1',
        ]);

        $question->update([
            'question_text' => $data['question_text'],
            'option_a' => $data['option_a'],
            'option_b' => $data['option_b'],
            'option_c' => $data['option_c'],
            'option_d' => $data['option_d'],
            'correct_option' => $this->normalizeCorrectOption($data['correct_option'], 0),
            'points' => $data['points'] ?? 1,
        ]);

        return redirect()->route('questions.index', $quiz->id)
            ->with('success', 'Question updated successfully!');
    }

    public function destroy($quiz, $question)
    {
        $quiz = Quiz::findOrFail($quiz);
        $question = Question::where('quiz_id', $quiz->id)->findOrFail($question);
        $question->delete();

        return redirect()->route('questions.index', $quiz->id)
            ->with('success', 'Question deleted successfully!');
    }

    public function import(Request $request, $quiz)
    {
        $quiz = Quiz::findOrFail($quiz);
        Log::info('Import process started', ['quiz_id' => $quiz->id]);

        try {
            $request->validate([
                'file' => 'required|mimes:csv,txt,xlsx,xls',
            ]);

            $extension = strtolower($request->file('file')->getClientOriginalExtension());
            $summary = in_array($extension, ['csv', 'txt'], true)
                ? $this->importFromCsv($request->file('file')->getRealPath(), $quiz)
                : $this->importFromExcel($request->file('file'), $quiz);

            $message = "Questions imported successfully. Added {$summary['imported']} question(s).";

            if ($summary['failed'] > 0) {
                $message .= " {$summary['failed']} row(s) failed.";
            }

            // if ($summary['limit_reached']) {
            //     $message .= " Question bank limit reached for this quiz.";
            // }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'count' => $summary['imported'],
                    'summary' => $summary,
                ]);
            }

            return redirect()->route('questions.index', $quiz->id)->with('success', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors(),
                ], 422);
            }

            throw $e;
        } catch (\Throwable $e) {
            Log::error('Import process failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'quiz_id' => $quiz->id,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Import failed: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Import failed: '.$e->getMessage());
        }
    }

    private function importFromCsv(string $path, Quiz $quiz): array
    {
        $file = fopen($path, 'r');
        if (! $file) {
            throw new \Exception('Could not open CSV file.');
        }

        $header = fgetcsv($file);
        if (empty($header) || count($header) < 7) {
            fclose($file);
            throw new \Exception('CSV must have at least 7 columns: question_text, option_a, option_b, option_c, option_d, correct_option, points.');
        }

        $rows = [];
        while (($row = fgetcsv($file)) !== false) {
            $rows[] = $row;
        }
        fclose($file);

        return $this->importRows($rows, $quiz);
    }

    private function importFromExcel($file, Quiz $quiz): array
    {
        $sheets = Excel::toArray([], $file);
        $rows = $sheets[0] ?? [];

        if (count($rows) < 2) {
            return [
                'total' => 0,
                'imported' => 0,
                'failed' => 0,
                'errors' => [],
                'limit_reached' => false,
            ];
        }

        array_shift($rows);

        return $this->importRows($rows, $quiz);
    }

    private function importRows(array $rows, Quiz $quiz): array
    {
        $summary = [
            'total' => 0,
            'imported' => 0,
            'failed' => 0,
            'errors' => [],
            'limit_reached' => false,
        ];

        $limit = $this->resolveQuestionLimit($quiz);
        $currentCount = Question::where('quiz_id', $quiz->id)->count();
        $remainingSlots = max($limit - $currentCount, 0);

        if ($remainingSlots === 0) {
            $summary['limit_reached'] = true;
            $summary['errors'][] = "This quiz already reached its question limit ({$limit}).";
            return $summary;
        }

        foreach ($rows as $index => $row) {
            if ($summary['imported'] >= $remainingSlots) {
                $summary['limit_reached'] = true;
                break;
            }

            $rowNumber = $index + 2; // Account for header row in files.
            $row = array_pad($row, 7, '');
            $row = array_map(static fn ($value) => trim((string) $value), $row);

            if ($this->isRowEmpty($row)) {
                continue;
            }

            $summary['total']++;

            try {
                $questionText = $row[0];
                $optionA = $row[1];
                $optionB = $row[2];
                $optionC = $row[3];
                $optionD = $row[4];
                $correct = $row[5];
                $points = (int) ($row[6] ?? 1);

                if ($questionText === '') {
                    throw new \Exception("Row {$rowNumber}: Empty question text.");
                }

                if ($optionA === '' || $optionB === '' || $optionC === '' || $optionD === '') {
                    throw new \Exception("Row {$rowNumber}: Options A, B, C and D are required.");
                }

                if ($points <= 0) {
                    $points = 1;
                }

                Question::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $questionText,
                    'option_a' => $optionA,
                    'option_b' => $optionB,
                    'option_c' => $optionC,
                    'option_d' => $optionD,
                    'correct_option' => $this->normalizeCorrectOption($correct, $rowNumber),
                    'points' => $points,
                ]);

                $summary['imported']++;
            } catch (\Throwable $e) {
                $summary['failed']++;
                $summary['errors'][] = $e->getMessage();
                Log::warning('Question import row failed', [
                    'quiz_id' => $quiz->id,
                    'row' => $rowNumber,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $summary;
    }

    private function normalizeCorrectOption($value, int $rowNumber): string
    {
        $value = strtoupper(trim((string) $value));
        $normalized = str_replace([' ', '-'], '_', $value);

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
            'FIRST' => 'A',
            'SECOND' => 'B',
            'THIRD' => 'C',
            'FOURTH' => 'D',
            'ONE' => 'A',
            'TWO' => 'B',
            'THREE' => 'C',
            'FOUR' => 'D',
        ];

        if (isset($map[$normalized])) {
            return $map[$normalized];
        }

        Log::warning("Invalid correct_option value '{$value}' in row {$rowNumber}; defaulting to A.");
        return 'A';
    }

    private function canAddMoreQuestions(Quiz $quiz): bool
    {
        $currentCount = Question::where('quiz_id', $quiz->id)->count();
        return $currentCount < $this->resolveQuestionLimit($quiz);
    }

    private function resolveQuestionLimit(Quiz $quiz): int
    {
        return max((int) ($quiz->question_limit ?? 60), 1);
    }

    private function isRowEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if ($value !== '' && $value !== null) {
                return false;
            }
        }

        return true;
    }
}
