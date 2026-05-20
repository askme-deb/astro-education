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
        $user = auth()->user();

        $joinResponse = $this->liveClassApiService->joinLiveClass((int) $id);
        $roomResponse = $this->liveClassApiService->getRoomAccess((int) $id);

        $joinPayload = array_replace_recursive(
            $this->extractJoinPayload($roomResponse),
            $this->extractJoinPayload($joinResponse)
        );

        $meetingId = $joinPayload['meeting_id'] ?? $joinPayload['room_id'] ?? $joinPayload['meetingId'] ?? '';
        $token = $joinPayload['token'] ?? $joinPayload['auth_token'] ?? $joinPayload['jwt'] ?? '';
        $apiKey = $joinPayload['api_key'] ?? $joinPayload['apiKey'] ?? config('services.videosdk.api_key') ?? $this->extractApiKeyFromJwt($token);
        
        $role = $joinPayload['role'] ?? 'participant';
        if (session()->has('auth.roles') && in_array('Instructor', session('auth.roles'))) {
            $role = 'host';
        }

        $jsPayload = [
            'token' => $token,
            'api_key' => $apiKey,
            'meeting_id' => $meetingId,
            'participant_id' => (string) ($joinPayload['participant_id'] ?? $joinPayload['user_id'] ?? $user->id ?? auth()->id()),
            'user_name' => $joinPayload['user_name'] ?? $joinPayload['name'] ?? $user->name ?? $user->email ?? 'User',
            'role' => $role,
            'live_class' => $liveClass,
            'debug' => [
                'join_success' => !empty($joinResponse['success']),
                'room_success' => !empty($roomResponse['success']),
                'has_token' => !empty($token),
                'has_api_key' => !empty($apiKey),
                'has_meeting_id' => !empty($meetingId),
            ],
        ];

        return view('live-classes.room', [
            'liveClass' => $liveClass,
            'joinPayload' => $joinPayload,
            'jsPayload' => $jsPayload,
        ]);
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
}
