<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\LiveClassCreated;
use App\Events\LiveClassStarted;
use App\Services\Api\LiveClasses\LiveClassApiService;
use App\Http\Requests\StoreLiveClassRequest;
use App\Http\Requests\UpdateLiveClassRequest;

class LiveClassController extends Controller
{
    protected $liveClassApiService;

    public function __construct(LiveClassApiService $liveClassApiService)
    {
        $this->liveClassApiService = $liveClassApiService;
    }

    /**
     * Create a new live class
     */
    public function create(StoreLiveClassRequest $request)
    {
        $response = $this->liveClassApiService
            ->createLiveClass($request->all());

        // Service already returns array
        if (!empty($response['success'])) {

            // Actual live class data
            $liveClassData = $response['data']['data'] ?? [];

            // Broadcast event
            broadcast(
                new LiveClassCreated($liveClassData)
            )->toOthers();

            // Return API response
            return response()->json([
                'success' => true,
                'message' => $response['data']['message'] ?? 'Live class created successfully.',
                'data' => $liveClassData,
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to create live class',
        ], 500);
    }

    /**
     * List instructor's live classes
     */
    public function index(Request $request)
    {
        $response = $this->liveClassApiService->listLiveClasses();

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to fetch live classes'], 500);
    }

    /**
     * Get live class details
     */
    public function show(Request $request, $id)
    {
        $response = $this->liveClassApiService->detail($id);

        if (is_array($response)) {
            return response()->json([
                'success' => true,
                'data' => $response
            ]);
        }

        return response()->json(['error' => 'Live class not found'], 404);
    }

    /**
     * Update live class
     */
    public function update(UpdateLiveClassRequest $request, $id)
    {
        $response = $this->liveClassApiService->updateLiveClass($id, $request->all());

        if ($response->successful()) {
            $data = $response->json();

            return response()->json($data);
        }

        return response()->json(['error' => 'Failed to update live class'], 500);
    }

    /**
     * Delete live class
     */
    public function destroy(Request $request, $id)
    {
        $response = $this->liveClassApiService->deleteLiveClass($id);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'message' => 'Live class deleted successfully.'
            ]);
        }

        return response()->json(['error' => 'Failed to delete live class'], 500);
    }

    /**
     * Get my live classes (Student/Instructor)
     */
    public function myClasses(Request $request)
    {
        $response = $this->liveClassApiService->listMyLiveClasses();

        if (!empty($response['success'])) {
            return response()->json([
                'success' => true,
                'data' => $response['data'] ?? []
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $response['error'] ?? 'Failed to fetch my live classes'
        ], 500);
    }

    /**
     * Enroll in a standalone live class
     */
    public function enroll(Request $request, $id)
    {
        $response = $this->liveClassApiService->enrollInStandaloneLiveClass($id);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'message' => 'Enrolled in standalone live class successfully.'
            ]);
        }

        return response()->json(['error' => 'Failed to enroll in live class'], 500);
    }

    /**
     * Join live class
     */
    public function join(Request $request, $id)
    {
        $response = $this->liveClassApiService
            ->joinLiveClass($id);

        if (!empty($response['success'])) {
            $data = $this->extractJoinPayload($response);

            $liveClassData = $this->liveClassApiService->detail((int) $id);
            $meetingId = $data['meeting_id'] ?? $data['room_id'] ?? $data['meetingId'] ?? null;
            $token = $data['token'] ?? $data['auth_token'] ?? $data['jwt'] ?? null;
            $apiKey = $data['api_key'] ?? $data['apiKey'] ?? config('services.videosdk.api_key') ?? $this->extractApiKeyFromJwt($token);

            return response()->json([
                'success' => true,
                'message' => 'Join credentials generated successfully.',
                'data' => [
                    'meeting_id' => $meetingId,
                    'meeting_link' => $data['meeting_link'] ?? $data['join_url'] ?? null,
                    'token' => $token,
                    'api_key' => $apiKey,
                    'user_name' => auth()->user()->name ?? 'User',
                    'participant_id' => (string) ($data['participant_id'] ?? $data['user_id'] ?? auth()->id()),
                    'user_id' => (string) ($data['user_id'] ?? auth()->id()),
                    'role' => $data['role'] ?? 'participant',
                    'live_class' => $liveClassData,
                    'debug' => [
                        'has_token' => !empty($token),
                        'has_api_key' => !empty($apiKey),
                        'has_meeting_id' => !empty($meetingId),
                    ],
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $response['error'] ?? $response['message'] ?? 'Failed to join live class.'
        ], 500);
    }

    private function extractJoinPayload(array $response): array
    {
        $data = $response['data'] ?? [];

        return $data['data']['join_payload']
            ?? $data['join_payload']
            ?? $data['data']
            ?? $data
            ?? [];
    }

    private function extractApiKeyFromJwt(?string $token): ?string
    {
        if (empty($token) || substr_count($token, '.') < 2) {
            return null;
        }

        $parts = explode('.', $token);
        $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);

        return is_array($payload) ? ($payload['apikey'] ?? $payload['apiKey'] ?? null) : null;
    }

    /**
     * Get recording access
     */
    public function getRecording(Request $request, $id)
    {
        $response = $this->liveClassApiService->getRoomRecording($id);

        if (!empty($response['success'])) {
            return response()->json([
                'success' => true,
                'message' => $response['message'] ?? 'Recording access granted.',
                'data' => $response['data'] ?? []
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $response['error'] ?? $response['message'] ?? 'Failed to get recording access.'
        ], 500);
    }

    /**
     * Get room access
     */
    public function room(Request $request, $id)
    {
        $liveClass = $this->liveClassApiService->detail((int) $id);
        $response = $this->liveClassApiService->getRoomAccess($id);
        
        if (!empty($response['success'])) {
            $joinPayload = $response['data']['data']['join_payload'] ?? $response['data']['join_payload'] ?? [];
            
            return response()->json([
                'success' => true,
                'message' => 'Room access granted.',
                'data' => [
                    'live_class' => $liveClass,
                    'join_payload' => $joinPayload
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $response['error'] ?? $response['message'] ?? 'Failed to get room access.'
        ], 500);
    }

    /**
     * End live class
     */
    public function end(Request $request, $id)
    {
        $response = $this->liveClassApiService->endLiveClass($id);

        if (!empty($response['success'])) {
            return response()->json([
                'success' => true,
                'message' => $response['message'] ?? 'Live class ended successfully.',
                'data' => $response['data'] ?? []
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $response['error'] ?? $response['message'] ?? 'Failed to end live class.'
        ], 500);
    }

    /**
     * Get room recording access
     */
    public function roomRecording(Request $request, $id)
    {
        $response = $this->liveClassApiService->getRoomRecording($id);

        if (!empty($response['success'])) {
            return response()->json([
                'success' => true,
                'message' => 'Recording access granted.',
                'data' => $response['data'] ?? []
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $response['error'] ?? $response['message'] ?? 'Failed to get recording access.'
        ], 500);
    }

    /**
     * Start live class
     */
    public function start(Request $request, $id)
    {
        $response = $this->liveClassApiService
            ->startLiveClass($id);

        if (!empty($response['success'])) {
            $liveClassData = $response['data'] ?? [];

            // Broadcast event to notify students
            broadcast(
                new LiveClassStarted($liveClassData)
            )->toOthers();

            // Get room access after starting
            $roomResponse = $this->liveClassApiService->getRoomAccess($id);

            return response()->json([
                'success' => true,
                'message' => 'Live class started successfully.',
                'data' => [
                    'live_class' => $liveClassData,
                    'room_access' => $roomResponse['data'] ?? null
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to start live class.'
        ], 500);
    }
}