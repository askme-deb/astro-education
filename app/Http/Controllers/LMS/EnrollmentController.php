<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Services\Api\Enrollment\EnrollmentApiService;
use Illuminate\Http\Request;

/**
 * Handles enrollment-related frontend requests.
 * Thin controller: delegates to EnrollmentApiService.
 */
class EnrollmentController extends Controller
{
    protected EnrollmentApiService $enrollmentApiService;

    public function __construct(EnrollmentApiService $enrollmentApiService)
    {
        $this->enrollmentApiService = $enrollmentApiService;
    }

    public function index()
    {
        $enrollments = $this->enrollmentApiService->listEnrollments();
        return view('enrollments.index', compact('enrollments'));
    }

    public function store(Request $request, $courseId)
    {
        $result = $this->enrollmentApiService->enrollInCourse($courseId);
        return redirect()->route('courses.show', $courseId)->with('status', $result['success'] ? 'Enrolled!' : 'Failed to enroll.');
    }

    public function show($id)
    {
        $enrollment = $this->enrollmentApiService->getEnrollmentDetails($id);
        return view('enrollments.show', compact('enrollment'));
    }
}
