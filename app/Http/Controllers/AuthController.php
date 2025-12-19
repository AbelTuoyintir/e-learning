<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Notifications\StudentLoginAlert;
use App\Mail\StudentPasswordResetMail;
use App\Models\Student;

class AuthController extends Controller
{
    // Student Login Methods
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

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Login successful'], 200);
            }

            return redirect()->intended('/students/dashboard');
        }

        // Log failed login
        Log::warning('Student login failed', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
            'time' => now()->toDateTimeString(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'The provided credentials do not match our records.'], 401);
        }

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

    // Forgot Password Methods for Students
    public function showForgotPasswordForm()
    {
        return view('students.forgot_password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $student = Student::where('email', $request->email)->first();

        if ($student) {
            $token = Str::random(60);

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now(),
            ]);

            $student->sendPasswordResetNotification($token);

            return back()->with('status', 'We have emailed your password reset link!');
        }

        return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
    }

    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('students.reset_password')->with(['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $reset = DB::table('password_resets')->where('email', $request->email)->first();

        if ($reset && $reset->token === $request->token && now()->diffInMinutes($reset->created_at) < 60) {
            $student = Student::where('email', $request->email)->first();
            $student->password = Hash::make($request->password);

            

            $student->save();

            DB::table('password_resets')->where('email', $request->email)->delete();

            return redirect('/student/login')->with('status', 'Your password has been reset!');
        }

        return back()->withErrors(['email' => 'Invalid token or email.']);
    }

    // Admin Login Methods (using users table)
    public function adminLogin()
    {
        return view('admin.login');
    }

    public function adminLoginSubmit(Request $request)
    {
        // Log incoming admin login attempt
        Log::info('Admin login attempt started', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
            'time' => now()->toDateTimeString(),
        ]);

        // Validate credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Add role check to credentials
        $credentials['role'] = 'admin'; // Assuming you have a 'role' column

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();

            // Log successful admin login
            Log::info('Admin login successful', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
                'time' => now()->toDateTimeString(),
            ]);

            return redirect()->intended('/admin/dashboard');
        }

        // Log failed admin login
        Log::warning('Admin login failed', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
            'time' => now()->toDateTimeString(),
        ]);

        // Return with error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records or you are not authorized as admin.',
        ])->onlyInput('email');
    }

    // Alternative method if you don't have a role column
    public function adminLoginSubmitAlternative(Request $request)
    {
        // Validate credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            // Check if user has admin role/privileges
            $user = Auth::guard('web')->user();

            // Adjust this condition based on how you identify admins
            if ($user->role === 'admin' || $user->is_admin === true) {
                $request->session()->regenerate();

                Log::info('Admin login successful', [
                    'email' => $request->input('email'),
                    'ip' => $request->ip(),
                    'time' => now()->toDateTimeString(),
                ]);

                return redirect()->intended('/admin/dashboard');
            } else {
                // User is not an admin - logout and show error
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => 'You are not authorized to access the admin panel.',
                ])->onlyInput('email');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function adminLogout(Request $request)
    {
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }

    // General logout (for web guard)
    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
