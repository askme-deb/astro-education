<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Api\Dashboard\DashboardApiService;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function __construct(
        private readonly DashboardApiService $dashboardApiService,
    ) {
    }

    public function index(): View
    {
        $dashboard = $this->dashboardApiService->overview();

        return view('dashboard.index', [
            'dashboard' => $dashboard,
        ]);
    }
}
