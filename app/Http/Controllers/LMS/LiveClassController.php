<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Services\Api\LiveClasses\LiveClassApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Handles live class-related frontend requests.
 * Thin controller: delegates to LiveClassApiService.
 */
class LiveClassController extends Controller
{
    protected LiveClassApiService $liveClassApiService;

    public function __construct(LiveClassApiService $liveClassApiService)
    {
        $this->liveClassApiService = $liveClassApiService;
    }

    public function index(): View
    {
        return view('live-classes.index', $this->liveClassApiService->catalog());
    }

    public function show($id): View
    {
        return view('live-classes.show', [
            'liveClass' => $this->liveClassApiService->detail((int) $id),
        ]);
    }

    public function enroll($id): RedirectResponse
    {
        $liveClassId = (int) $id;
        $response = $this->liveClassApiService->enrollInStandaloneLiveClass($liveClassId);

        return redirect()
            ->route('live-classes.show', ['id' => $liveClassId])
            ->with($response['success'] ? 'status' : 'error', $response['success'] ? 'Live class enrollment completed successfully.' : ($response['error'] ?? 'Unable to enroll in this live class right now.'));
    }

    public function room($id): View
    {
        $liveClass = $this->liveClassApiService->detail((int) $id);

        // Get join credentials from the API
        $joinResponse = $this->liveClassApiService->joinLiveClass((int) $id);

        // Extract join payload from response
        if (!empty($joinResponse['success']) && !empty($joinResponse['data'])) {
            // The service returns ['success' => true, 'data' => [...API response...]]
            // The API response contains ['success' => true, 'message' => '...', 'data' => [...]]
            // So we need to extract the inner 'data' from the API response
            $apiResponse = $joinResponse['data'];
            $joinPayload = $apiResponse['data'] ?? $apiResponse;
        } else {
            // Fallback to room access endpoint
            $roomResponse = $this->liveClassApiService->getRoomAccess((int) $id);
            $joinPayload = $roomResponse['data']['join_payload'] ?? $roomResponse['data'] ?? [];
        }

        return view('live-classes.room', [
            'liveClass' => $liveClass,
            'joinPayload' => $joinPayload,
        ]);
    }
}
