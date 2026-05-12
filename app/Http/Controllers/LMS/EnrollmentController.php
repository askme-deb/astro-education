<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Services\Api\Enrollment\EnrollmentApiService;
use Illuminate\Support\Carbon;
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
        $courseId = (int) $courseId;

        if (! session()->has('auth.api_token') || ! session()->has('auth.user')) {
            return redirect()
                ->route('courses.show', $courseId)
                ->with('error', 'Please log in before enrolling in a course.');
        }

        $enrollments = $this->enrollmentApiService->extractEnrollmentItems($this->enrollmentApiService->listEnrollments());

        if ($this->isEnrolled($courseId, $enrollments)) {
            return redirect()
                ->route('courses.show', $courseId)
                ->with('status', 'You are already enrolled in this course.');
        }

        $result = $this->enrollmentApiService->enrollInCourse($courseId);

        if ($result['success'] ?? false) {
            $rememberedCourseIds = collect(session('lms.enrolled_course_ids', []))
                ->map(fn ($value) => (int) $value)
                ->filter()
                ->push($courseId)
                ->unique()
                ->values()
                ->all();

            session(['lms.enrolled_course_ids' => $rememberedCourseIds]);
        }

        return redirect()
            ->route('courses.show', $courseId)
            ->with($result['success'] ? 'status' : 'error', $result['success'] ? 'Enrollment completed successfully.' : ($result['error'] ?? 'Failed to enroll in this course.'));
    }

    public function show($id)
    {
        $response = $this->enrollmentApiService->getEnrollmentDetails((int) $id);
        $enrollment = $this->extractEnrollmentRecord($response);

        abort_if(empty($enrollment), 404);

        return view('enrollments.show', [
            'enrollment' => $enrollment,
            'response' => $response,
        ]);
    }

    private function isEnrolled(int $courseId, array $enrollments): bool
    {
        return collect($enrollments)->contains(function ($enrollment) use ($courseId) {
            return (int) data_get($enrollment, 'course_id') === $courseId
                || (int) data_get($enrollment, 'course.id') === $courseId;
        });
    }

    private function extractEnrollmentRecord(array $response): array
    {
        $payload = $response['data'] ?? [];

        while (is_array($payload) && isset($payload['data']) && is_array($payload['data'])) {
            $payload = $payload['data'];
        }

        if (! is_array($payload)) {
            return [];
        }

        $course = is_array($payload['course'] ?? null) ? $payload['course'] : [];
        $courseId = $payload['course_id'] ?? $course['id'] ?? null;
        $progress = (int) ($payload['progress_percentage'] ?? $course['progress'] ?? 0);
        $image = $course['image'] ?? $course['thumbnail'] ?? $course['image_url'] ?? null;
        $enrolledAt = $payload['created_at'] ?? null;

        return [
            'id' => $payload['id'] ?? null,
            'course_id' => $courseId,
            'title' => $course['title'] ?? $course['name'] ?? ('Course #' . ($courseId ?? 'N/A')),
            'description' => (string) ($course['description'] ?? $course['summary'] ?? $course['short_description'] ?? ''),
            'status' => strtolower((string) ($payload['status'] ?? 'active')),
            'progress' => max(0, min(100, $progress)),
            'image' => is_string($image) && $image !== '' ? $image : asset('assets/images/course-details.jpg'),
            'category' => $this->normalizeCategory($course),
            'instructor' => $this->normalizeInstructor($course),
            'duration_label' => $this->normalizeDuration($course),
            'enrolled_at' => $enrolledAt,
            'enrolled_at_label' => $this->formatDateLabel($enrolledAt),
            'course_url' => $courseId ? route('courses.show', ['id' => $courseId]) : route('courses.index'),
            'raw' => $payload,
        ];
    }

    private function normalizeCategory(array $course): string
    {
        $category = $course['category'] ?? $course['category_name'] ?? null;

        if (is_array($category)) {
            return (string) ($category['name'] ?? 'General');
        }

        return is_scalar($category) && $category !== ''
            ? (string) $category
            : 'General';
    }

    private function normalizeInstructor(array $course): string
    {
        $instructor = $course['instructor'] ?? $course['teacher'] ?? $course['mentor'] ?? null;

        if (is_array($instructor)) {
            return (string) ($instructor['name'] ?? 'Astro Education Faculty');
        }

        return is_scalar($instructor) && $instructor !== ''
            ? (string) $instructor
            : 'Astro Education Faculty';
    }

    private function normalizeDuration(array $course): string
    {
        $duration = $course['duration'] ?? $course['duration_label'] ?? null;

        if (is_scalar($duration) && $duration !== '') {
            return (string) $duration;
        }

        $totalLessons = $course['total_lessons'] ?? $course['lessons_count'] ?? null;

        if (is_scalar($totalLessons) && $totalLessons !== '') {
            return $totalLessons . ' lessons';
        }

        return 'Self-paced';
    }

    private function formatDateLabel(?string $value): string
    {
        if (! $value) {
            return 'Recently updated';
        }

        try {
            return Carbon::parse($value)->format('d M Y');
        } catch (\Throwable) {
            return 'Recently updated';
        }
    }
}
