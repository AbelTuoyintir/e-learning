<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentHighlight extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'target_type',
        'target_id',
        'highlights',
    ];

    protected $casts = [
        'highlights' => 'array',
    ];
}
