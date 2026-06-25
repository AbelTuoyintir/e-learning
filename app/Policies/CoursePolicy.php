<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Course $course): bool
    {
        if ($user->role === 'admin') return true;
        return $user->id === $course->user_id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'tutor']);
    }

    public function update(User $user, Course $course): bool
    {
        if ($user->role === 'admin') return true;
        return $user->id === $course->user_id;
    }

    public function delete(User $user, Course $course): bool
    {
        if ($user->role === 'admin') return true;
        return $user->id === $course->user_id;
    }
}
