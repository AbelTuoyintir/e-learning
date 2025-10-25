<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCourses extends Model
{
    //
    protected $table = 'student_courses';
    protected $fillable = [
        'student_id',
        'course_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
