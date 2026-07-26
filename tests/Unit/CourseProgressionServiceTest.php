<?php

namespace Tests\Unit;

use App\Models\Quiz;
use App\Services\CourseProgressionService;
use PHPUnit\Framework\TestCase;

class CourseProgressionServiceTest extends TestCase
{
    public function test_it_detects_course_completion_quizzes_without_module(): void
    {
        $service = new CourseProgressionService();

        $courseExamQuiz = new Quiz([
            'type' => 'final',
            'quiz_type' => 'course_exam',
            'module_id' => null,
        ]);

        $moduleQuiz = new Quiz([
            'type' => 'final',
            'quiz_type' => 'module_assessment',
            'module_id' => 4,
        ]);

        $this->assertTrue($service->isCourseCompletionQuiz($courseExamQuiz));
        $this->assertFalse($service->isCourseCompletionQuiz($moduleQuiz));
    }
}
