<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\Result;

class CourseProgressionService
{
    public function isModuleCompletionQuiz(Quiz $quiz): bool
    {
        if (!$quiz) {
            return false;
        }

        $quizType = strtolower((string) ($quiz->quiz_type ?? ''));
        $type = strtolower((string) ($quiz->type ?? ''));

        if ($quizType === 'course_exam') {
            return false;
        }

        if ($quizType === 'module_assessment') {
            return !empty($quiz->module_id);
        }

        return $type === 'final' && !empty($quiz->module_id);
    }

    public function isCourseCompletionQuiz(Quiz $quiz): bool
    {
        if (!$quiz) {
            return false;
        }

        $quizType = strtolower((string) ($quiz->quiz_type ?? ''));
        if ($quizType === 'course_exam') {
            return true;
        }

        $type = strtolower((string) ($quiz->type ?? ''));

        return $type === 'final' && empty($quiz->module_id);
    }

    public function hasPassedCourseCompletionQuiz(int $studentId, int $courseId): bool
    {
        $quizIds = Quiz::query()
            ->where('course_id', $courseId)
            ->where(function ($query): void {
                $query->where('quiz_type', 'course_exam')
                    ->orWhere(function ($subQuery): void {
                        $subQuery->where('type', 'final')
                            ->whereNull('module_id');
                    });
            })
            ->pluck('id');

        if ($quizIds->isEmpty()) {
            return false;
        }

        return Result::query()
            ->where('student_id', $studentId)
            ->whereIn('quiz_id', $quizIds)
            ->where('passed', 1)
            ->exists();
    }

    public function hasPassedModuleCompletionQuiz(int $studentId, int $moduleId): bool
    {
        $quizIds = Quiz::query()
            ->where('module_id', $moduleId)
            ->where(function ($query): void {
                $query->where('quiz_type', 'module_assessment')
                    ->orWhere(function ($subQuery): void {
                        $subQuery->where('type', 'final')
                            ->whereNotNull('module_id');
                    });
            })
            ->pluck('id');

        if ($quizIds->isEmpty()) {
            return false;
        }

        return Result::query()
            ->where('student_id', $studentId)
            ->whereIn('quiz_id', $quizIds)
            ->where('passed', 1)
            ->exists();
    }
}
