<?php

namespace App\Modules\Admin\Dairy\Inventory\StockMovement\Services;

use App\Modules\Admin\Dairy\Inventory\StockMovement\Repositories\StockMovementRepository;

class StockMovementService
{
    public function __construct(
        private StockMovementRepository $stockMovementRepository
    ) {}

    public function dataTable($request)
    {
        return $this->stockMovementRepository->dataTable($request);
    }

    public function summary(int $presentationId, int $plantId): array
    {
        return $this->stockMovementRepository->summary($presentationId, $plantId);
    }

    public function save(array $data)
    {
        return $this->stockMovementRepository->create($data);
    }

    public function delete(int $id): void
    {
        $this->stockMovementRepository->delete($id);
    }
}
