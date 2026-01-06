<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // so students can log in
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;

class Student extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable;

    // Table name (optional, Laravel will auto-detect "students")
    protected $table = 'students';

    // Fillable fields
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'email',
        'password',
        'phone',
        'Program',
        'status',
        'index_number'
        'theme_preference',
    ];

    // Hide password when returning JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * A student can enroll in many courses.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_student');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * A student can attempt many quizzes.
     */
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * A student can have many notifications.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }


    /**
     * Get the email address for password reset.
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\StudentPasswordResetNotification($token));
    }
}
