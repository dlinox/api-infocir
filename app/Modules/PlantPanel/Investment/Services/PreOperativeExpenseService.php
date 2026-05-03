<?php

namespace App\Modules\PlantPanel\Investment\Services;

use App\Models\Dairy\PreOperativeExpense;
use App\Modules\Auth\Services\AuthService;
use App\Modules\PlantPanel\Investment\Repositories\PreOperativeExpenseRepository;

class PreOperativeExpenseService
{
    public function __construct(
        private PreOperativeExpenseRepository $repository,
        private AuthService $authService,
    ) {}

    public function dataTable($request)
    {
        return $this->repository->dataTable($this->authService->getMyEntityId(), $request);
    }

    public function get(int $id): PreOperativeExpense
    {
        return $this->repository->findForEntity($this->authService->getMyEntityId(), $id);
    }

    public function save(array $data): PreOperativeExpense
    {
        return $this->repository->createOrUpdate($this->authService->getMyEntityId(), $data);
    }

    public function delete(int $id): void
    {
        $this->repository->delete($this->authService->getMyEntityId(), $id);
    }
}
