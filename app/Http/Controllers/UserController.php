<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use App\Models\Student;

class UserController extends Controller
{
    public function regstu(){
        return view('users.studentForm');
    }

    public function store(Request $request)
    {
        // 1. Validate request
        $validated = $request->validate([
            'firstname'  => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname'   => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:students',
            'phone'      => 'required|string|max:20',
            'Program'    => 'nullable|string|max:100',
        ]);

        // 2. Generate random password
        $plainPassword = \Illuminate\Support\Str::random(10);

        // 3. Hash password before saving
        $validated['password'] = \Illuminate\Support\Facades\Hash::make($plainPassword);

        // 4. Create student
        try {
            $student = \App\Models\Student::create($validated);

           Mail::to($student->email)->send(new StudentPasswordMail($student, $plainPassword, $student->email));

            // 5. Send SMS via Arkasel
            try {
                $apiKey   = env('ARKASEL_API_KEY');
                $senderId = env('ARKASEL_SENDER_ID');
                $to       = $student->phone;  // in international format
                $message  = "Hello {$student->firstname}, your account has been created. Email: {$student->email}, Password: {$plainPassword}";

                // Build the URL
                $url = 'https://sms.arkesel.com/sms/api';
                $query = [
                    'action'  => 'send-sms',
                    'api_key' => $apiKey,
                    'to'      => $to,
                    'from'    => $senderId,
                    'sms'     => $message,
                ];

                // Send GET request
                $response = \Illuminate\Support\Facades\Http::get($url, $query);

                \Log::debug('Arkasel GET SMS URL', ['url' => $response->effectiveUri(), 'status' => $response->status(), 'body' => $response->body()]);

                if ($response->successful()) {
                    \Log::info("SMS sent to {$to}");

                    return redirect()->back()->with([
                        'success' => 'Student registered successfully! Password has been sent via SMS.',
                        'student_data' => [
                            'name' => $student->firstname . ' ' . $student->lastname,
                            'email' => $student->email,
                            'password' => $plainPassword
                        ]
                    ]);
                } else {
                    \Log::error("Failed to send SMS (GET)", [
                        'status' => $response->status(),
                        'body'   => $response->body(),
                    ]);

                    // Student created but SMS failed - still return success but with warning
                    return redirect()->back()->with([
                        'warning' => 'Student registered but SMS failed to send. Please manually provide the password: ' . $plainPassword,
                        'student_data' => [
                            'name' => $student->firstname . ' ' . $student->lastname,
                            'email' => $student->email,
                            'password' => $plainPassword
                        ]
                    ]);
                }

            } catch (\Exception $e) {
                \Log::error("SMS GET Error: " . $e->getMessage());

                // Student created but SMS failed due to exception
                return redirect()->back()->with([
                    'warning' => 'Student registered but SMS service encountered an error. Please manually provide the password: ' . $plainPassword,
                    'student_data' => [
                        'name' => $student->firstname . ' ' . $student->lastname,
                        'email' => $student->email,
                        'password' => $plainPassword
                    ]
                ]);
            }

        } catch (\Exception $e) {
            \Log::error("Student creation failed: " . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to create student: ' . $e->getMessage())->withInput();
        }
    }
}
