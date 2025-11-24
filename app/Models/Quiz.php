<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    //

    protected $fillable = ['title', 'image', 'difficulty','course_id', 'image', 'time_limit', 'time_per_question', 'description',
'module_id', 'topic_id'];
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

}
