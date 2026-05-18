<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'difficulty',
        'course_id',
        'module_id',
        'topic_id',
        'time_limit',
        'time_per_question',
        'question_limit',
        'description',
        'due_at',
        'max_attempts',
        'passing_score',
        'is_active',
        'related_to_id',     // For polymorphic relationship
        'related_to_type'    // For polymorphic relationship
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'is_active' => 'boolean',
        'time_limit' => 'integer',
        'time_per_question' => 'integer',
        'question_limit' => 'integer',
        'max_attempts' => 'integer',
        'passing_score' => 'integer'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // Add this if you want to filter by specific user
    public function userAttempts($userId = null)
    {
        $userId = $userId ?? auth()->id();
        return $this->hasMany(QuizAttempt::class)->where('user_id', $userId);
    }

    // Add polymorphic relationship for topics
    public function relatedTo()
    {
        return $this->morphTo();
    }
}
