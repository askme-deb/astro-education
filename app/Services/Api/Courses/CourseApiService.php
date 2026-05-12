<?php

namespace App\Services\Api\Courses;

use App\Services\Api\Clients\BaseApiClient;
use Illuminate\Support\Str;

/**
 * CourseApiService
 * Handles all course-related API operations and caching.
 */
class CourseApiService extends BaseApiClient
{
    /**
     * Returns a normalized list of course records for the frontend.
     */
    public function catalog(array $params = []): array
    {
        $response = $this->listCourses($params);
        $items = $this->extractCourseItems($response);
        $paginator = $this->extractPaginatorPayload($response);

        return [
            'response' => $response,
            'items' => $items,
            'featured' => $items[0] ?? null,
            'pagination' => [
                'current_page' => (int) ($paginator['current_page'] ?? 1),
                'last_page' => max((int) ($paginator['last_page'] ?? 1), 1),
                'per_page' => (int) ($paginator['per_page'] ?? count($items)),
                'total' => (int) ($paginator['total'] ?? count($items)),
                'from' => (int) ($paginator['from'] ?? (count($items) > 0 ? 1 : 0)),
                'to' => (int) ($paginator['to'] ?? count($items)),
            ],
        ];
    }

    /**
     * List all courses (with caching).
     */
    public function listCourses(array $params = [])
    {
        $cacheKey = 'courses.list.' . md5(json_encode($params));
        $cacheTtl = config('services.lms_api.cache_ttl', 3600);
        return $this->get('courses', $params, [
            'cache_key' => $cacheKey,
            'cache_ttl' => $cacheTtl,
        ]);
    }

    /**
     * Get course details by ID (with caching).
     */
    public function getCourseDetails(int $courseId)
    {
        $cacheKey = 'courses.details.' . $courseId;
        $cacheTtl = config('services.lms_api.cache_ttl', 3600);

        return $this->get("courses/{$courseId}", [], [
            'cache_key' => $cacheKey,
            'cache_ttl' => $cacheTtl,
        ]);
    }

    public function detail(int $courseId): array
    {
        $response = $this->getCourseDetails($courseId);
        $course = $this->extractDetailPayload($response);
        $normalized = $this->normalizeCourseItem($course);

        return [
            'response' => $response,
            'item' => $normalized,
            'content' => (string) ($course['summary'] ?? $course['description'] ?? ''),
        ];
    }

    /**
     * Filters out invalid API entries before they reach Blade.
     */
    protected function extractCourseItems(array $response): array
    {
        $payload = $this->extractPaginatorPayload($response)['data'] ?? $this->extractListPayload($response);

        if (! is_array($payload)) {
            return [];
        }

        $items = [];

        foreach ($payload as $course) {
            $normalizedCourse = $this->normalizeCourseItem($course);

            if ($normalizedCourse !== null) {
                $items[] = $normalizedCourse;
            }
        }

        return $items;
    }

    protected function extractPaginatorPayload(array $response): array
    {
        $payload = $response['data'] ?? [];

        while (is_array($payload) && isset($payload['data']) && is_array($payload['data'])) {
            if ($this->looksLikePaginator($payload)) {
                return $payload;
            }

            $payload = $payload['data'];
        }

        return $this->looksLikePaginator($payload) ? $payload : [];
    }

    protected function extractDetailPayload(array $response): array
    {
        $payload = $response['data'] ?? [];

        while (is_array($payload) && isset($payload['data']) && is_array($payload['data'])) {
            if ($this->looksLikeCourse($payload)) {
                return $payload;
            }

            $payload = $payload['data'];
        }

        return $this->looksLikeCourse($payload) ? $payload : [];
    }

    /**
     * Unwraps nested API envelopes until a course list payload is reached.
     */
    protected function extractListPayload(array $response): array
    {
        $payload = $response['data'] ?? [];

        while (is_array($payload) && isset($payload['data']) && is_array($payload['data'])) {
            $payload = $payload['data'];
        }

        if (is_array($payload) && isset($payload['data']) && is_array($payload['data'])) {
            return $payload['data'];
        }

        return is_array($payload) ? $payload : [];
    }

    protected function looksLikePaginator(mixed $payload): bool
    {
        return is_array($payload)
            && array_key_exists('data', $payload)
            && is_array($payload['data'])
            && array_key_exists('current_page', $payload);
    }

    protected function looksLikeCourse(mixed $payload): bool
    {
        return is_array($payload)
            && (array_key_exists('id', $payload) || array_key_exists('course_id', $payload));
    }

    /**
     * Normalizes a single course record into the view contract.
     */
    protected function normalizeCourseItem(mixed $course): ?array
    {
        if (! is_array($course)) {
            return null;
        }

        $id = $course['id'] ?? $course['course_id'] ?? null;

        if (! is_scalar($id) || $id === '') {
            return null;
        }

        $normalizedLessons = $this->normalizeLessons($course);

        return [
            'id' => (string) $id,
            'title' => (string) ($course['title'] ?? $course['name'] ?? 'Untitled'),
            'description' => Str::limit(strip_tags((string) ($course['description'] ?? $course['summary'] ?? $course['short_description'] ?? '')), 180),
            'category' => $this->normalizeCategory($course),
            'image' => $course['image'] ?? $course['thumbnail'] ?? null,
            'price' => $this->normalizePrice($course),
            'price_label' => $this->formatPrice($course['price'] ?? null),
            'level' => Str::title((string) ($course['difficulty_level'] ?? 'Beginner')),
            'duration_label' => $this->normalizeDuration($course),
            'progress' => $this->normalizeProgress($course),
            'instructor' => $this->normalizeInstructor($course),
            'instructors' => $this->normalizeInstructors($course),
            'lessons' => $normalizedLessons,
            'lesson_count' => count($normalizedLessons),
            'preview_video_url' => $normalizedLessons[0]['embed_url'] ?? $normalizedLessons[0]['video_url'] ?? null,
            'status' => (string) ($course['status'] ?? 'draft'),
            'is_public' => (bool) ($course['is_public'] ?? false),
            'raw' => $course,
        ];
    }

    protected function normalizeCategory(array $course): string
    {
        $category = $course['category'] ?? $course['category_name'] ?? null;

        if (is_array($category)) {
            return (string) ($category['name'] ?? 'General');
        }

        if (is_scalar($category) && $category !== '') {
            return (string) $category;
        }

        return 'General';
    }

    protected function normalizePrice(array $course): float
    {
        return round((float) ($course['price'] ?? 0), 2);
    }

    protected function formatPrice(mixed $price): string
    {
        $amount = round((float) ($price ?? 0), 2);
        $formatted = number_format($amount, 2, '.', '');
        $formatted = rtrim(rtrim($formatted, '0'), '.');

        return 'Rs.' . ($formatted === '' ? '0' : $formatted);
    }

    protected function normalizeDuration(array $course): string
    {
        $duration = $course['total_duration'] ?? null;

        if (is_scalar($duration) && (string) $duration !== '') {
            return (string) $duration;
        }

        $lessonsCount = count($this->normalizeLessons($course));

        if ($lessonsCount > 0) {
            return $lessonsCount . ' lessons';
        }

        return 'Self paced';
    }

    protected function normalizeProgress(array $course): int
    {
        $progress = $course['progress_percentage'] ?? $course['progress'] ?? 0;

        return max(0, min(100, (int) $progress));
    }

    protected function normalizeInstructor(array $course): string
    {
        $instructors = $this->normalizeInstructors($course);

        if ($instructors === []) {
            return 'Faculty assigned soon';
        }

        return (string) ($instructors[0]['name'] ?? 'Faculty assigned soon');
    }

    protected function normalizeInstructors(array $course): array
    {
        $instructors = $course['instructors'] ?? [];

        if (! is_array($instructors)) {
            return [];
        }

        $items = [];

        foreach ($instructors as $instructor) {
            if (! is_array($instructor)) {
                continue;
            }

            $fullName = trim(implode(' ', array_filter([
                $instructor['first_name'] ?? null,
                $instructor['middle_name'] ?? null,
                $instructor['last_name'] ?? null,
            ])));

            $items[] = [
                'id' => $instructor['id'] ?? null,
                'name' => $fullName !== '' ? $fullName : (string) ($instructor['user_name'] ?? 'Faculty assigned soon'),
                'email' => $instructor['email'] ?? null,
                'designation' => $instructor['designation'] ?? $instructor['skill'] ?? null,
                'bio' => $instructor['skill'] ?? null,
            ];
        }

        return $items;
    }

    protected function normalizeLessons(array $course): array
    {
        $lessons = $course['lessons'] ?? [];

        if (! is_array($lessons)) {
            return [];
        }

        $items = [];

        foreach ($lessons as $lesson) {
            if (! is_array($lesson)) {
                continue;
            }

            $videoUrl = $lesson['vimeo_id'] ?? null;
            $items[] = [
                'id' => $lesson['id'] ?? null,
                'title' => (string) ($lesson['title'] ?? 'Untitled Lesson'),
                'type' => Str::title((string) ($lesson['type'] ?? 'Lesson')),
                'position' => (int) ($lesson['position'] ?? 0),
                'topic_id' => $lesson['topic_id'] ?? null,
                'content' => (string) ($lesson['content'] ?? ''),
                'video_url' => is_scalar($videoUrl) && $videoUrl !== '' ? (string) $videoUrl : null,
                'embed_url' => $this->normalizeLessonVideoUrl($lesson),
            ];
        }

        usort($items, fn (array $left, array $right): int => ($left['position'] <=> $right['position']) ?: (($left['id'] ?? 0) <=> ($right['id'] ?? 0)));

        return $items;
    }

    protected function normalizeLessonVideoUrl(array $lesson): ?string
    {
        $videoUrl = $lesson['vimeo_id'] ?? $lesson['video_url'] ?? null;

        if (! is_scalar($videoUrl) || $videoUrl === '') {
            return null;
        }

        $url = trim((string) $videoUrl);
        $type = strtolower((string) ($lesson['type'] ?? ''));

        if ($type === 'vimeo') {
            if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/i', $url, $matches) === 1) {
                return 'https://player.vimeo.com/video/' . $matches[1];
            }

            if (preg_match('/^\d+$/', $url) === 1) {
                return 'https://player.vimeo.com/video/' . $url;
            }
        }

        return $url;
    }
}
