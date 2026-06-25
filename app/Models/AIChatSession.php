<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AIChatSession extends Model
{
    protected $table = 'ai_chat_sessions';

    protected $fillable = [
        'student_id',
        'question',
        'response',
        'course_id',
        'module_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
