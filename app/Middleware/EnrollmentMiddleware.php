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
    public function __construct(private readonly EnrollmentApiService $enrollmentService)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        if (! session()->has('auth.api_token') || ! session()->has('auth.user')) {
            return redirect()->route('home')->with('error', 'Please log in to continue.');
        }

        $courseId = $request->route('courseId') ?? $request->route('id');
        $enrolled = $this->enrollmentService->extractEnrollmentItems($this->enrollmentService->listEnrollments());

        if ($enrolled === []) {
            return redirect()->route('courses.index')->with('error', 'Unable to verify your enrollment right now.');
        }

        $isEnrolled = collect($enrolled)->contains(function ($enrollment) use ($courseId) {
            return (int) data_get($enrollment, 'course_id') === (int) $courseId
                || (int) data_get($enrollment, 'course.id') === (int) $courseId;
        });
        if (! $isEnrolled) {
            return redirect()->route('courses.index')->with('error', 'You must be enrolled to access this content.');
        }

        return $next($request);
    }
}
