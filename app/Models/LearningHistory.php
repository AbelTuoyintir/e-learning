<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningHistory extends Model
{
    protected $table = 'learning_history';
    protected $fillable = [
        'student_id',
        'activity_type',
        'activity_id',
        'description',
        'time_spent',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
