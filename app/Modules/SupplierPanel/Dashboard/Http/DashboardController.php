<?php

namespace App\Modules\SupplierPanel\Dashboard\Http;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\SupplierPanel\Dashboard\Services\DashboardService;

class DashboardController
{
    public function __construct(private DashboardService $service) {}

    public function summary(): \Illuminate\Http\JsonResponse
    {
        return ApiResponse::success($this->service->getSummary(), 'Resumen del dashboard');
    }
}
