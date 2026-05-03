<?php

namespace App\Modules\PlantPanel\Investment\Services;

use App\Models\Dairy\FixedAsset;
use App\Modules\Auth\Services\AuthService;
use App\Modules\PlantPanel\Investment\Repositories\FixedAssetRepository;

class FixedAssetService
{
    public function __construct(
        private FixedAssetRepository $repository,
        private AuthService $authService,
    ) {}

    public function dataTable($request)
    {
        return $this->repository->dataTable($this->authService->getMyEntityId(), $request);
    }

    public function get(int $id): FixedAsset
    {
        return $this->repository->findForEntity($this->authService->getMyEntityId(), $id);
    }

    public function save(array $data): FixedAsset
    {
        return $this->repository->createOrUpdate($this->authService->getMyEntityId(), $data);
    }

    public function delete(int $id): void
    {
        $this->repository->delete($this->authService->getMyEntityId(), $id);
    }
}
