<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserActivity; 
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

          // Define action based on request method
        $action = $this->getActionFromMethod($request->method());
        
        // Define description with request details
        $description = [
            'method' => $request->method(),
            'path' => $request->path(),
            'full_url' => $request->fullUrl(),
        ];

        // Excluded routes to avoid clutter
        $excluded = [
            'student/login',
            'student/logout',
            'login',
            'logout',
            'register',
        ];

        $path = trim($request->path(), '/');

       if (!in_array($path, $excluded)) {
            // Get the user ID if available, otherwise null
            $userId = auth()->id();
            $studentId = auth()->id();

            UserActivity::create([
                'user_id' => $userId ?? $studentId,
                'action' => $action,
                'description' => is_array($description) ? json_encode($description) : $description,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

       

        return $response;
    }

      private function getActionFromMethod(string $method): string
        {
            return match($method) {
                'GET' => 'view',
                'POST' => 'create',
                'PUT', 'PATCH' => 'update',
                'DELETE' => 'delete',
                default => 'access',
            };
        }

}
