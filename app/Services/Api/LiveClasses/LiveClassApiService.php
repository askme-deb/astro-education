<?php

namespace App\Services\Api\LiveClasses;

use App\Services\Api\Clients\BaseApiClient;

/**
 * LiveClassApiService
 * Handles all live class-related API operations.
 */
class LiveClassApiService extends BaseApiClient
{
    public function listLiveClasses()
    {
        return $this->get('live-classes');
    }

    public function createLiveClass(array $data)
    {
        return $this->post('live-classes', $data);
    }

    public function updateLiveClass(int $id, array $data)
    {
        return $this->put("live-classes/{$id}", $data);
    }

    public function deleteLiveClass(int $id)
    {
        return $this->delete("live-classes/{$id}");
    }

    public function startLiveClass(int $id)
    {
        return $this->post("live-classes/{$id}/start");
    }

    public function listMyLiveClasses()
    {
        return $this->get('my-live-classes');
    }

    public function enrollInStandaloneLiveClass(int $id)
    {
        return $this->post("live-classes/{$id}/enroll");
    }

    public function joinLiveClass(int $id)
    {
        return $this->get("live-classes/{$id}/join");
    }

    public function getLiveClassRecording(int $id)
    {
        return $this->get("live-classes/{$id}/recording");
    }
}
