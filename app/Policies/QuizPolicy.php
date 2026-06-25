<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuizPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Quiz $quiz): bool
    {
        if ($user->role === 'admin') return true;
        return $quiz->course && $user->id === $quiz->course->user_id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'tutor']);
    }

    public function update(User $user, Quiz $quiz): bool
    {
        if ($user->role === 'admin') return true;
        return $quiz->course && $user->id === $quiz->course->user_id;
    }

    public function delete(User $user, Quiz $quiz): bool
    {
        if ($user->role === 'admin') return true;
        return $quiz->course && $user->id === $quiz->course->user_id;
    }
}
