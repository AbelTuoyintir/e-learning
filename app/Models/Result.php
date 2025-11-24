<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    //
    protected $fillable = ['student_id', 'quiz_id', 'score', 'passed', 'attempt_number', 'completed_at', 'details'];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
