<?php

namespace App\Modules\Admin\Dashboard\Http;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dashboard\Services\DashboardService;

class DashboardController
{
    public function __construct(private DashboardService $service) {}

    public function summary(): \Illuminate\Http\JsonResponse
    {
        return ApiResponse::success($this->service->getSummary(), 'Resumen del dashboard');
    }

    public function mapData(): \Illuminate\Http\JsonResponse
    {
        return ApiResponse::success($this->service->getMapData(), 'Datos del mapa');
    }
}
