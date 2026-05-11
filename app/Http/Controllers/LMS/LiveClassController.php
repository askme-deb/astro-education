<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Services\Api\LiveClasses\LiveClassApiService;
use Illuminate\Http\Request;

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

    public function index()
    {
        $liveClasses = $this->liveClassApiService->listLiveClasses();
        return view('live-classes.index', compact('liveClasses'));
    }

    public function show($id)
    {
        $liveClass = $this->liveClassApiService->getLiveClassRecording($id);
        return view('live-classes.show', compact('liveClass'));
    }
}
