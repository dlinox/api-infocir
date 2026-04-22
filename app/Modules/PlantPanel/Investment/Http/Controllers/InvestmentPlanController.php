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

    public function getCurrent(): JsonResponse
    {
        $plan = $this->service->getCurrent();
        return ApiResponse::success(new InvestmentPlanResource($plan));
    }

    public function save(InvestmentPlanRequest $request): JsonResponse
    {
        $plan = $this->service->save($request->validated());
        return ApiResponse::success(new InvestmentPlanResource($plan), 'Plan guardado correctamente');
    }

    public function approve(int $id): JsonResponse
    {
        $plan = $this->service->approve($id);
        return ApiResponse::success(new InvestmentPlanResource($plan), 'Plan aprobado correctamente');
    }
}
