<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'order',
        'duration_minutes',
        'is_active',
    ];

    /**
     * A module belongs to a course.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * A module can have many topics.
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
