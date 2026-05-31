<?php

namespace App\Modules\Admin\Dashboard\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dashboard\Services\DashboardService;

class DashboardController
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    public function summary(): \Illuminate\Http\JsonResponse
    {
        return ApiResponse::success($this->dashboardService->getSummary(), 'Resumen del dashboard');
    }

    public function mapData(): \Illuminate\Http\JsonResponse
    {
        return ApiResponse::success($this->dashboardService->getMapData(), 'Datos del mapa');
    }

    public function overview(): \Illuminate\Http\JsonResponse
    {
        return ApiResponse::success($this->dashboardService->getOverview(), 'Resumen general del dashboard');
    }
}
