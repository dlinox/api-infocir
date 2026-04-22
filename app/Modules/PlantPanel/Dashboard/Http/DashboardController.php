<?php

namespace App\Modules\PlantPanel\Dashboard\Http;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\PlantPanel\Dashboard\Services\DashboardService;

class DashboardController
{
    public function __construct(private DashboardService $service) {}

    public function summary(): \Illuminate\Http\JsonResponse
    {
        return ApiResponse::success($this->service->getSummary(), 'Resumen del dashboard');
    }
}
