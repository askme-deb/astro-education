<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Api\Enrollment\EnrollmentApiService;

/**
 * EnrollmentMiddleware
 * Ensures only enrolled students can access protected course content.
 */
class EnrollmentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $courseId = $request->route('courseId') ?? $request->route('id');
        $enrollmentService = app(EnrollmentApiService::class);
        $enrolled = $enrollmentService->listEnrollments();
        $isEnrolled = collect($enrolled['data'] ?? [])->contains('course_id', $courseId);
        if (!$isEnrolled) {
            return redirect()->route('courses.index')->with('error', 'You must be enrolled to access this content.');
        }
        return $next($request);
    }
}
