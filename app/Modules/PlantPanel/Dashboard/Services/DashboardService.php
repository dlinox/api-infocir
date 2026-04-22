<?php

namespace App\Modules\PlantPanel\Dashboard\Services;

use App\Modules\Auth\Services\AuthService;
use App\Modules\PlantPanel\Dashboard\Repositories\DashboardRepository;

class DashboardService
{
    public function __construct(
        private DashboardRepository $repository,
        private AuthService $authService,
    ) {}

    public function getSummary(): array
    {
        $plantId = $this->authService->getMyPlantId();
        return $this->repository->getSummary($plantId);
    }
}
