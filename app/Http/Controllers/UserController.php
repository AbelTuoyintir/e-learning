<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Mail\StudentPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function regstu(){
        return view('users.studentForm');
    }

   public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string|max:20',
            'program' => 'nullable|string|max:255',
            // Don't include index_number here since we'll generate it
        ]);

        try {
            // Generate index number based on program and year
            $indexNumber = $this->generateIndexNumber($validated['program'] ?? null);

            // Generate plain text password
            $plainPassword = Str::random(8);

            // Hash password for database
            $validated['password'] = Hash::make($plainPassword);

            // Add the generated index number
            $validated['index_number'] = $indexNumber;

            // Create student
            $student = Student::create($validated);

            // Send email with the PLAIN password (not hashed)
            Mail::to($student->email)->send(new StudentPasswordMail($student, $plainPassword, $student->email));

            // Send SMS with the PLAIN password
            try {
                $apiKey = env('ARKASEL_API_KEY');
                $senderId = env('ARKASEL_SENDER_ID');

                $message = "Hello {$student->firstname}, your QuizApp account is ready. Student ID: {$index_number}, Email: {$student->email}, Password: {$plainPassword}. Login at: " . url('/login');

                $response = Http::get('https://sms.arkesel.com/sms/api', [
                    'action' => 'send-sms',
                    'api_key' => $apiKey,
                    'to' => $student->phone,
                    'from' => $senderId,
                    'sms' => $message
                ]);

                if (!$response->successful()) {
                    \Log::error('SMS failed: ' . $response->body());
                }

            } catch (\Exception $smsException) {
                \Log::error('SMS sending error: ' . $smsException->getMessage());
            }

            return redirect()->back()->with('success', 'Student created successfully! Student ID: ' . $indexNumber);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create student: ' . $e->getMessage())->withInput();
        }
    }

    private function generateIndexNumber($program = null)
    {
        $year = date('Y'); // 4-digit year

        // Get program abbreviation
        $programAbbr = 'STU'; // Default for students
        if ($program) {
            // Map common programs to abbreviations
            $programMap = [
                'computer science' => 'CS',
                'information technology' => 'IT',
                'business administration' => 'BA',
                'engineering' => 'ENG',
                'medicine' => 'MED',
                // Add more as needed
            ];

            $programLower = strtolower($program);
            foreach ($programMap as $key => $abbr) {
                if (str_contains($programLower, $key)) {
                    $programAbbr = $abbr;
                    break;
                }
            }
        }

        // Get total student count + 1
        $totalStudents = Student::count();
        $nextNumber = $totalStudents + 1;

        // Format: PROG/YEAR/SEQUENCE (e.g., CS/2024/00123)
        return $programAbbr . '/' . $year . '/' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'required|string|max:20',
            'program' => 'nullable|string|max:255',
        ]);

        try {
            $student->update($validated);
            return redirect()->back()->with('success', 'Student updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update student: ' . $e->getMessage())->withInput();
        }
    }

}
