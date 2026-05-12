<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Services\Api\Courses\CourseApiService;
use App\Services\Api\Enrollment\EnrollmentApiService;
use Illuminate\Http\Request;

/**
 * Handles course-related frontend requests.
 * Thin controller: delegates to CourseApiService.
 */
class CourseController extends Controller
{
    protected CourseApiService $courseApiService;
    protected EnrollmentApiService $enrollmentApiService;

    public function __construct(CourseApiService $courseApiService, EnrollmentApiService $enrollmentApiService)
    {
        $this->courseApiService = $courseApiService;
        $this->enrollmentApiService = $enrollmentApiService;
    }

    public function index(Request $request)
    {
        $catalog = $this->courseApiService->catalog($request->all());

        return view('courses.index', [
            'courses' => $catalog['response'],
            'courseItems' => $catalog['items'],
            'featuredCourse' => $catalog['featured'],
            'pagination' => $catalog['pagination'],
            'filters' => $request->only(['search', 'difficulty_level', 'duration', 'price_sort']),
        ]);
    }

    public function show($id)
    {
        $courseId = (int) $id;
        $detail = $this->courseApiService->detail($courseId);
        $enrollmentResponse = session()->has('auth.api_token') && session()->has('auth.user')
            ? $this->enrollmentApiService->listEnrollments()
            : ['success' => false, 'data' => []];
        $matchedEnrollment = $this->findEnrollment($courseId, $this->enrollmentApiService->extractEnrollmentItems($enrollmentResponse));
        $hasFallbackAccess = $this->hasCourseAccessFromDetail($courseId, $detail);
        $resolvedEnrollment = $matchedEnrollment ?? ($hasFallbackAccess ? [
            'id' => null,
            'status' => 'active',
            'progress' => max(0, min(100, (int) data_get($detail, 'item.progress', 0))),
            'created_at' => null,
            'raw' => [],
        ] : null);

        return view('courses.show', [
            'course' => $detail['item'],
            'courseResponse' => $detail['response'],
            'courseContent' => $detail['content'],
            'isEnrolled' => $resolvedEnrollment !== null,
            'enrollment' => $resolvedEnrollment,
        ]);
    }

    private function hasCourseAccessFromDetail(int $courseId, array $detail): bool
    {
        if (! session()->has('auth.api_token') || ! session()->has('auth.user')) {
            return false;
        }

        $rememberedCourseIds = collect(session('lms.enrolled_course_ids', []))
            ->map(fn ($value) => (int) $value)
            ->filter();

        if ($rememberedCourseIds->contains($courseId)) {
            return true;
        }

        $course = is_array($detail['item'] ?? null) ? $detail['item'] : [];
        $raw = is_array($course['raw'] ?? null) ? $course['raw'] : [];

        if ((bool) data_get($raw, 'is_enrolled') || (bool) data_get($raw, 'enrolled') || (bool) data_get($raw, 'has_access') || (bool) data_get($raw, 'can_access')) {
            return true;
        }

        if (filled(data_get($raw, 'enrollment_id')) || filled(data_get($raw, 'enrolled_at'))) {
            return true;
        }

        return max(0, (int) ($course['progress'] ?? data_get($raw, 'progress_percentage') ?? data_get($raw, 'progress') ?? 0)) > 0;
    }

    private function isEnrolled(int $courseId, array $enrollments): bool
    {
        return collect($enrollments)->contains(function ($enrollment) use ($courseId) {
            return (int) data_get($enrollment, 'course_id') === $courseId
                || (int) data_get($enrollment, 'course.id') === $courseId;
        });
    }

    private function findEnrollment(int $courseId, array $enrollments): ?array
    {
        $match = collect($enrollments)->first(function ($enrollment) use ($courseId) {
            return (int) data_get($enrollment, 'course_id') === $courseId
                || (int) data_get($enrollment, 'course.id') === $courseId;
        });

        if (! is_array($match)) {
            return null;
        }

        return [
            'id' => $match['id'] ?? null,
            'status' => strtolower((string) ($match['status'] ?? 'active')),
            'progress' => max(0, min(100, (int) ($match['progress_percentage'] ?? data_get($match, 'course.progress') ?? 0))),
            'created_at' => $match['created_at'] ?? null,
            'raw' => $match,
        ];
    }
}
