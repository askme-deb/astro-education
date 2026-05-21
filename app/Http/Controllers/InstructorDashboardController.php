<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Api\LiveClasses\LiveClassApiService;
use App\Services\Api\Courses\CourseApiService;

class InstructorDashboardController extends Controller
{
    protected $liveClassApiService;
    protected $courseApiService;

    public function __construct(LiveClassApiService $liveClassApiService, CourseApiService $courseApiService)
    {
        $this->liveClassApiService = $liveClassApiService;
        $this->courseApiService = $courseApiService;
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

        // Fetch courses list for selection dropdown
        $coursesCatalog = $this->courseApiService->catalog();
        $courses = $coursesCatalog['items'] ?? [];

        return view('instructor.dashboard', [
            'liveClasses' => $liveClasses,
            'courses' => $courses,
        ]);
    }
}
