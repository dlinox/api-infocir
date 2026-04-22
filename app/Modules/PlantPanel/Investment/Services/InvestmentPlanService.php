<?php

namespace App\Modules\PlantPanel\Investment\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Dairy\InvestmentPlan;
use App\Modules\Auth\Services\AuthService;
use App\Modules\PlantPanel\Investment\Repositories\InvestmentPlanRepository;

class InvestmentPlanService
{
    public function __construct(
        private InvestmentPlanRepository $repository,
        private AuthService $authService,
    ) {}

    public function getCurrent(): InvestmentPlan
    {
        $entityId = $this->authService->getMyEntityId();
        $plan     = $this->repository->findCurrentForEntity($entityId);

        if (!$plan) {
            $plan = $this->repository->createForEntity($entityId, (int) date('Y'));
            $plan->load(['items.category']);
        }

        return $plan;
    }

    public function save(array $data): InvestmentPlan
    {
        $entityId = $this->authService->getMyEntityId();
        return $this->repository->save($entityId, $data);
    }

    public function approve(int $planId): InvestmentPlan
    {
        $entityId = $this->authService->getMyEntityId();
        return $this->repository->approve($entityId, $planId);
    }
}
