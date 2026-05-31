<?php

namespace App\Modules\Admin\Dairy\Finance\BusinessPlan\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Finance\BusinessPlan\Services\BusinessPlanService;

class PlantBusinessPlanController
{
    public function __construct(
        private BusinessPlanService $businessPlanService
    ) {}

    public function show(string $plantId)
    {
        return ApiResponse::success($this->businessPlanService->getForPlant((int) $plantId));
    }

    public function save(Request $request, string $plantId)
    {
        // Lo único editable: parámetros y la demanda mensual (mapa presentationId => [12]).
        $payload = $request->only(['parametros', 'demanda']);
        $result = $this->businessPlanService->saveForPlant((int) $plantId, $payload);
        return ApiResponse::success($result, 'Proyección guardada correctamente');
    }
}
