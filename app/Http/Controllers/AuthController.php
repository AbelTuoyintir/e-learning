<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\StudentLoginAlert; 

class AuthController extends Controller
{
    //
    public function login(){
        return view('students.studLogin');
    }

    public function studentLogin(Request $request)
    {


        // Log incoming request attempt
        Log::info('Student login attempt started', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
            'time' => now()->toDateTimeString(),
        ]);


        // Validate credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

       if (Auth::guard('student')->attempt($credentials)) {
            $request->session()->regenerate();

            // Get the authenticated student
            $student = Auth::guard('student')->user();

            // Send login notification
            $student->notify(new StudentLoginAlert(now()->toDateTimeString(), $request->ip()));

            // Log successful login
            Log::info('Student login successful', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
                'time' => now()->toDateTimeString(),
            ]);

            return redirect()->intended('/students/dashboard');
        }

        // Log failed login
        Log::warning('Student login failed', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
            'time' => now()->toDateTimeString(),
        ]);

        // Return with error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function studentLogout(Request $request) {
        auth()->guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/student/login');
    }

    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
