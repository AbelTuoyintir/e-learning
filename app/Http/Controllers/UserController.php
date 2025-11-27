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
        // ... add other fields as needed
    ]);

    try {
        // Generate plain text password
        $plainPassword = Str::random(8);

        // Hash password for database
        $validated['password'] = Hash::make($plainPassword);

        // Create student
        $student = Student::create($validated);

        // Send email with the PLAIN password (not hashed)
        Mail::to($student->email)->send(new StudentPasswordMail($student, $plainPassword, $student->email));

        // Send SMS with the PLAIN password
        try {
            $apiKey = env('ARKASEL_API_KEY');
            $senderId = env('ARKASEL_SENDER_ID');

            $message = "Hello {$student->firstname}, your QuizApp account is ready. Email: {$student->email}, Password: {$plainPassword}. Login at: " . url('/login');

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

        return redirect()->back()->with('success', 'Student created successfully!');

    } catch (\Exception $e) {
        return back()->with('error', 'Failed to create student: ' . $e->getMessage())->withInput();
    }
}
}
