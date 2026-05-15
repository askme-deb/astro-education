<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\LiveClassCreated;
use App\Services\Api\LiveClasses\LiveClassApiService;

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
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'course_id' => 'required|integer',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_recorded' => 'nullable|boolean',
            'recording_url' => 'nullable|url',
        ]);

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
    // public function create(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required|string',
    //         'description' => 'nullable|string',
    //         'course_id' => 'required|integer',
    //         'start_time' => 'required|date',
    //         'end_time' => 'required|date|after:start_time',
    //         'is_recorded' => 'nullable|boolean',
    //         'recording_url' => 'nullable|url',
    //     ]);

    //     $response = $this->liveClassApiService->createLiveClass($request->all());

    //    if (!empty($response['success'])) {
    //         $data = $response->json();

    //         // Broadcast the event to notify students
    //         broadcast(new LiveClassCreated($data['data']))->toOthers();

    //         return response()->json($data);
    //     }

    //     return response()->json(['error' => 'Failed to create live class'], 500);
    // }

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
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'course_id' => 'sometimes|required|integer',
            'start_time' => 'sometimes|required|date',
            'end_time' => 'sometimes|required|date|after:start_time',
            'is_recorded' => 'nullable|boolean',
            'recording_url' => 'nullable|url',
        ]);

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

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to fetch my live classes'], 500);
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
/**
 * Join live class
 */
public function join(Request $request, $id)
{
    $response = $this->liveClassApiService
        ->joinLiveClass($id);
    dd($response);
    // Service returns array
    if (!empty($response['success'])) {

        $data = $response['data'] ?? [];

        return response()->json([
            'success' => true,
            'message' => $response['message']
                ?? 'Join credentials generated successfully.',
            'data' => [
                'meeting_id' => $data['meeting_id'] ?? null,
                'meeting_link' => $data['meeting_link'] ?? null,
                'token' => $data['token'] ?? null,
                'user_name' => auth()->user()->name ?? 'User',
                'role' => $data['role'] ?? 'participant',
            ]
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => $response['message']
            ?? 'Failed to join live class.'
    ], 500);
}

    /**
     * Get recording access
     */
    public function getRecording(Request $request, $id)
    {
        // This would typically call a method to get recording URL from the API
        // For now, returning a placeholder response
        return response()->json([
            'success' => true,
            'message' => 'Recording access granted.',
            'data' => [
                'recording_url' => 'https://videos.example.com/replays/week-3',
                'live_class' => [
                    'id' => $id,
                    'title' => 'Week 3 Live Revision',
                    'status' => 'completed',
                    'is_recorded' => true
                ]
            ]
        ]);
    }


    /**
 * Start live class
 */
/**
 * Start live class
 */
/**
 * Start live class
 */
/**
 * Start live class
 */
public function start(Request $request, $id)
{
    $response = $this->liveClassApiService
        ->startLiveClass($id);

    if (!empty($response['success'])) {

        return response()->json([
            'success' => true,
            'message' => 'Live class started successfully.',
            'data' => $response['data'] ?? []
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Failed to start live class.'
    ], 500);
}
}
