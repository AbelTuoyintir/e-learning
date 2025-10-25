<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserActivity extends Model
{
    protected $fillable = [
        'user_id',
        'user_type', 
        'user_role',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    /**
     * Get the user (either App\Models\User or App\Models\Student)
     */
    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope for student activities
     */
    public function scopeStudents($query)
    {
        return $query->where('user_role', 'student');
    }

    /**
     * Scope for admin activities
     */
    public function scopeAdmins($query)
    {
        return $query->where('user_role', 'admin');
    }
}