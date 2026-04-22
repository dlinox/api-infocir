<?php

namespace App\Modules\Admin\Dashboard\Services;

use App\Modules\Admin\Dashboard\Repositories\DashboardRepository;

class DashboardService
{
    public function __construct(private DashboardRepository $repository) {}

    public function getSummary(): array
    {
        return $this->repository->getSummary();
    }

    public function getMapData(): array
    {
        return $this->repository->getMapData();
    }
}
