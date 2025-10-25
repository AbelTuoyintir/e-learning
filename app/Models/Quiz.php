<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    //

    protected $fillable = ['title', 'image', 'difficulty','course_id', 'image', 'time_limit', 'time_per_question', 'description'];
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    public function results()
    {
        return $this->hasMany(Result::class);
    }

}
