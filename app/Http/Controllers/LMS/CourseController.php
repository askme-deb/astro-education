<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Services\Api\Courses\CourseApiService;
use Illuminate\Http\Request;

/**
 * Handles course-related frontend requests.
 * Thin controller: delegates to CourseApiService.
 */
class CourseController extends Controller
{
    protected CourseApiService $courseApiService;

    public function __construct(CourseApiService $courseApiService)
    {
        $this->courseApiService = $courseApiService;
    }

    public function index(Request $request)
    {
        $catalog = $this->courseApiService->catalog($request->all());

        return view('courses.index', [
            'courses' => $catalog['response'],
            'courseItems' => $catalog['items'],
            'featuredCourse' => $catalog['featured'],
            'pagination' => $catalog['pagination'],
            'filters' => $request->only(['search', 'difficulty_level', 'duration', 'price_sort']),
        ]);
    }

    public function show($id)
    {
        $detail = $this->courseApiService->detail((int) $id);

        return view('courses.show', [
            'course' => $detail['item'],
            'courseResponse' => $detail['response'],
            'courseContent' => $detail['content'],
        ]);
    }
}
