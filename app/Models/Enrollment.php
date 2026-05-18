<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    //

    protected $fillable = [
        'student_id',
        'course_id',
        'enrolled_at',
        'price_paid',
        'payment_status',
        'payment_reference',
        'purchased_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'purchased_at' => 'datetime',
        'price_paid' => 'decimal:2',
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
