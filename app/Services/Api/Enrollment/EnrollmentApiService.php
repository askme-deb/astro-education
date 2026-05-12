<?php

namespace App\Services\Api\Enrollment;

use App\Services\Api\Clients\BaseApiClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

/**
 * EnrollmentApiService
 * Handles all enrollment-related API operations.
 */
class EnrollmentApiService extends BaseApiClient
{
    /**
     * List all enrollments for the current user.
     */
    public function listEnrollments()
    {
        return $this->get('enrollments');
    }

    public function extractEnrollmentItems(array $response): array
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

        return array_values(array_filter($payload, 'is_array'));
    }

    /**
     * Enroll in a course by ID.
     */
    public function enrollInCourse(int $courseId, array $attributes = [])
    {
        $tokenContext = $this->resolveTokenContext();
        $userId = $attributes['user_id'] ?? Session::get('api_user_id') ?? data_get(Session::get('auth.user'), 'id');
        $payload = array_merge($attributes, [
            'user_id' => is_numeric($userId) ? (int) $userId : $userId,
            'course_id' => $courseId,
        ]);

        if (! $tokenContext['token']) {
            return [
                'success' => false,
                'error' => 'You must be logged in before enrolling in a course.',
                'status' => 401,
            ];
        }

        Log::info('Enrollment request payload', [
            'endpoint' => rtrim((string) config('services.lms_api.base_url', ''), '/') . "/courses/{$courseId}/enroll",
            'payload' => $payload,
            'user_id' => is_numeric($userId) ? (int) $userId : null,
            'authorization_header' => isset($tokenContext['token']) && is_string($tokenContext['token']) && $tokenContext['token'] !== ''
                ? 'Bearer ' . $tokenContext['token']
                : null,
            'token_source' => $tokenContext['source'] ?? null,
        ]);

        $response = $this->post("courses/{$courseId}/enroll", $payload);

        if (($response['status'] ?? null) === 401) {
            $response['error'] = 'Enrollment failed because the LMS API rejected the current login token.';
        }

        if (($response['status'] ?? null) === 405) {
            $response['error'] = "Enrollment failed because the LMS server does not expose a POST route for api/v1/courses/{$courseId}/enroll.";
        }

        if (($response['status'] ?? null) === 422) {
            $response['error'] = $response['error'] ?? 'Enrollment failed because the LMS rejected the enrollment payload.';
        }

        return $response;
    }

    /**
     * Get enrollment details by ID.
     */
    public function getEnrollmentDetails(int $enrollmentId)
    {
        return $this->get("enrollments/{$enrollmentId}");
    }
}
