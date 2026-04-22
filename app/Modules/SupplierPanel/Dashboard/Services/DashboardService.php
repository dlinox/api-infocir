<?php

namespace App\Modules\SupplierPanel\Dashboard\Services;

use App\Modules\Auth\Services\AuthService;
use App\Modules\SupplierPanel\Dashboard\Repositories\DashboardRepository;

class DashboardService
{
    public function __construct(
        private DashboardRepository $repository,
        private AuthService $authService,
    ) {}

    public function getSummary(): array
    {
        $supplierId = $this->authService->getMySupplierId();
        return $this->repository->getSummary($supplierId);
    }
}
