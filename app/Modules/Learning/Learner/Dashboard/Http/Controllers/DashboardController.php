<?php

namespace App\Modules\Learning\Learner\Dashboard\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Learning\Learner\Dashboard\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController
{
    public function __construct(
        private DashboardService $dashboardService,
    ) {}

    public function stats(): JsonResponse
    {
        $data = $this->dashboardService->getStats();

        return ApiResponse::success($data);
    }
}
