<?php

namespace App\Services\Api\Enrollment;

use App\Services\Api\Clients\BaseApiClient;

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

    /**
     * Enroll in a course by ID.
     */
    public function enrollInCourse(int $courseId)
    {
        return $this->post("courses/{$courseId}/enroll");
    }

    /**
     * Get enrollment details by ID.
     */
    public function getEnrollmentDetails(int $enrollmentId)
    {
        return $this->get("enrollments/{$enrollmentId}");
    }
}
