<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    //
    protected $fillable = ['quiz_id', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option', 'points'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

     public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function modules()
    {
        return $this->hasManyThrough(Module::class, Topic::class, 'id', 'id', 'topic_id', 'module_id');
    }

    public function course()
    {
        return $this->hasManyThrough(Course::class, Module::class, 'id', 'id', 'module_id', 'course_id');
    }

}
