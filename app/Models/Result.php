<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    //
    protected $fillable = ['student_id', 'quiz_id', 'score', 'percentage', 'total_possible_points', 'grade', 'time_taken', 'passed', 'attempt_number', 'completed_at', 'details'];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

     protected $casts = [
        'completed_at' => 'datetime', // Add this line
        'passed' => 'boolean',
        'details' => 'array', // 
    ];
}
