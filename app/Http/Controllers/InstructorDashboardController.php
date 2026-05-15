<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Api\LiveClasses\LiveClassApiService;

class InstructorDashboardController extends Controller
{
    protected $liveClassApiService;

    public function __construct(LiveClassApiService $liveClassApiService)
    {
        $this->liveClassApiService = $liveClassApiService;
    }

    public function index()
    {
        $response = $this->liveClassApiService->listLiveClasses();

        $liveClasses = [];
        if (!empty($response['success'])) {

            // because your API structure is:
            // success -> data -> data

            $liveClasses = $response['data']['data']['data'] ?? [];
        }
        return view('instructor.dashboard', [
            'liveClasses' => $liveClasses,
        ]);
    }
}
