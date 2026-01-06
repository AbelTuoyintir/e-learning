<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    protected $table = 'courses';
    protected $fillable = [
        'title',
        'description',
        'category',
        'image',
        'duration',
        'instructor',
        'price',
    ];

     public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * A course can have many students (many-to-many).
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_student');
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
       public function enrollments()
    {
        return $this->hasMany(StudentCourses::class);
    }
}
