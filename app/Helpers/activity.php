<?php

namespace App\Services;

use App\Models\UserActivity;

class ActivityLogger
{
    public static function log($user, $action, $description, $request, $metadata = [])
    {
        // Determine user type and role
        $userType = get_class($user);
        $userRole = self::getUserRole($user);
        $userId = $user->id;

        // Ensure description is a string
        if (is_array($description)) {
            $description = json_encode($description);
        }

        // Add request details to metadata
        $metadata = array_merge($metadata, [
            'method' => $request->method(),
            'path' => $request->path(),
            'full_url' => $request->fullUrl(),
        ]);

        return UserActivity::create([
            'user_id' => $userId,
            'user_type' => $userType,
            'user_role' => $userRole,
            'action' => $action,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => $metadata,
        ]);
    }

    private static function getUserRole($user)
    {
        // Check if user has a role property/method
        if (method_exists($user, 'getRole')) {
            return $user->getRole();
        }
        
        if (property_exists($user, 'role')) {
            return $user->role;
        }

        // Determine role based on class
        $userType = get_class($user);
        return match($userType) {
            'App\Models\Student' => 'student',
            'App\Models\User' => $user->is_admin ? 'admin' : 'user',
            default => 'user'
        };
    }

    /**
     * Quick logging methods for common actions
     */
    public static function logLogin($user, $request, $isSuccess = true)
    {
        $status = $isSuccess ? 'successfully' : 'failed';
        return self::log($user, 'login', "User {$status} logged in", $request);
    }

    public static function logLogout($user, $request)
    {
        return self::log($user, 'logout', "User logged out", $request);
    }

    public static function logCourseEnrollment($student, $course, $request)
    {
        return self::log($student, 'enroll', "Student enrolled in course: {$course->title}", $request, [
            'course_id' => $course->id,
            'course_title' => $course->title
        ]);
    }
}