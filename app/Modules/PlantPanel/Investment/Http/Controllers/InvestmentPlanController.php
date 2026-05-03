<?php

namespace App\Modules\PlantPanel\Investment\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\PlantPanel\Investment\Http\Requests\InvestmentPlan\InvestmentPlanRequest;
use App\Modules\PlantPanel\Investment\Http\Resources\InvestmentPlan\InvestmentPlanResource;
use App\Modules\PlantPanel\Investment\Services\InvestmentPlanService;
use Illuminate\Http\JsonResponse;

class InvestmentPlanController
{
    public function __construct(
        private InvestmentPlanService $service,
    ) {}

    public function getWorkingCapital(int $year, int $month): JsonResponse
    {
        return ApiResponse::success(new InvestmentPlanResource($this->service->getWorkingCapital($year, $month)));
    }

    public function save(InvestmentPlanRequest $request): JsonResponse
    {
        $plan = $this->service->save($request->validated());
        return ApiResponse::success(new InvestmentPlanResource($plan), 'Gastos del mes guardados correctamente');
    }

    public function copyPreviousMonth(int $year, int $month): JsonResponse
    {
        $plan = $this->service->copyPreviousMonth($year, $month);
        return ApiResponse::success(new InvestmentPlanResource($plan), 'Gastos del mes anterior copiados');
    }

    public function getWorkingCapitalWorkers(): JsonResponse
    {
        return ApiResponse::success($this->service->getWorkingCapitalWorkers());
    }

    public function getSummary(): JsonResponse
    {
        return ApiResponse::success($this->service->getSummary((int) date('Y')));
    }
}