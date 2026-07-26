<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningHistory extends Model
{
    protected $table = 'learning_history';
    protected $fillable = [
        'student_id',
        'activity_type',
        'description',
        'related_id',
        'related_type',
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
