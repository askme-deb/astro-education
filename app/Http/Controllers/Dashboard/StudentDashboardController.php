<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Api\Dashboard\DashboardApiService;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function __construct(
        private readonly DashboardApiService $dashboardApiService,
        private readonly \App\Services\Api\Enrollment\EnrollmentApiService $enrollmentApiService,
    ) {
    }

    public function index(): View
    {
        if (! session()->has('auth.api_token') || ! session()->has('auth.user')) {
            return redirect()->route('home');
        }

        $user = session('auth.user');
        $enrollmentResponse = $this->enrollmentApiService->listEnrollments();
        $enrollmentItems = $this->extractEnrollmentItems($enrollmentResponse);

        $stats = [
            'total' => count($enrollmentItems),
            'active' => count(array_filter($enrollmentItems, fn (array $enrollment): bool => ($enrollment['status'] ?? 'active') !== 'completed')),
            'completed' => count(array_filter($enrollmentItems, fn (array $enrollment): bool => ($enrollment['status'] ?? null) === 'completed')),
        ];

        return view('dashboard.index', [
            'user' => $user,
            'enrollments' => $enrollmentItems,
            'stats' => $stats,
            'apiError' => !empty($enrollmentResponse['success']) ? null : ($enrollmentResponse['error'] ?? null),
        ]);
    }

    private function extractEnrollmentItems(array $response): array
    {
        $payload = $response['data'] ?? [];

        while (is_array($payload) && isset($payload['data'])) {
            if (is_array($payload['data']) && array_is_list($payload['data'])) {
                $payload = $payload['data'];
                break;
            }
            $payload = $payload['data'];
        }

        if (! is_array($payload)) {
            return [];
        }

        $items = array_values(array_filter($payload, 'is_array'));

        return array_map(function (array $enrollment): array {
            $course = is_array($enrollment['course'] ?? null) ? $enrollment['course'] : [];
            $courseId = $enrollment['course_id'] ?? $course['id'] ?? null;
            $progress = (int) ($enrollment['progress_percentage'] ?? $course['progress'] ?? 0);
            $image = $course['image'] ?? $course['thumbnail'] ?? $course['image_url'] ?? null;
            $enrolledAt = $enrollment['created_at'] ?? null;

            return [
                'id' => $enrollment['id'] ?? null,
                'title' => $course['title'] ?? $course['name'] ?? ('Course #' . ($enrollment['course_id'] ?? 'N/A')),
                'status' => strtolower((string) ($enrollment['status'] ?? 'active')),
                'course_id' => $courseId,
                'progress' => $progress,
                'enrolled_at' => $enrolledAt,
                'enrolled_at_label' => $this->formatEnrollmentDate($enrolledAt),
                'image' => is_string($image) && $image !== '' ? $image : asset('assets/images/course-details.jpg'),
                'category' => $this->normalizeCategory($course),
                'course_url' => $courseId ? route('courses.show', ['id' => $courseId]) : route('courses.index'),
                'resume_url' => isset($enrollment['id']) ? route('enrollments.show', ['id' => $enrollment['id']]) : ($courseId ? route('courses.show', ['id' => $courseId]) : route('courses.index')),
                'resume_label' => $progress >= 100 ? 'Review Progress' : 'Resume Learning',
            ];
        }, $items);
    }

    private function normalizeCategory(array $course): string
    {
        $category = $course['category'] ?? $course['category_name'] ?? null;
        if (is_array($category)) {
            return (string) ($category['name'] ?? 'General');
        }
        return is_scalar($category) && $category !== '' ? \Illuminate\Support\Str::title((string) $category) : 'General';
    }

    private function formatEnrollmentDate(?string $enrolledAt): string
    {
        if (! $enrolledAt) {
            return 'Recently enrolled';
        }
        try {
            return \Illuminate\Support\Carbon::parse($enrolledAt)->format('d M Y');
        } catch (\Throwable) {
            return 'Recently enrolled';
        }
    }
}
