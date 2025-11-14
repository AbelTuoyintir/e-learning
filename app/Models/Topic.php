<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'content',
        'order',
        'duration_minutes',
        'is_active',
        'has_quiz',
        'passing_score',
    ];

    /**
     * A topic belongs to a module.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * (Optional) If each topic has multiple quizzes,
     * you can define this relationship later.
     */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function videos()
    {
        return $this->hasMany(TopicVideo::class);
    }

    public function articles()
    {
        return $this->hasMany(TopicArticle::class);
    }
}
