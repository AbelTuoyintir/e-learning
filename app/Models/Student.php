<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // so students can log in
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    // Table name (optional, Laravel will auto-detect "students")
    protected $table = 'students';

    // Fillable fields
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'phone',
        'Program',
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

    /**
     * A student can attempt many quizzes.
     */
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
