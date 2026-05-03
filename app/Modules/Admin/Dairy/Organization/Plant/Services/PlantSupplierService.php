<?php

namespace App\Modules\Admin\Dairy\Organization\Plant\Services;

use App\Modules\Admin\Dairy\Organization\Plant\Repositories\PlantSupplierRepository;

class PlantSupplierService
{
    public function __construct(
        private PlantSupplierRepository $plantSupplierRepository
    ) {}

    public function getAssignedSupplierIds(int $plantId): array
    {
        return $this->plantSupplierRepository->getAssignedSupplierIds($plantId);
    }

    public function syncSuppliers(int $plantId, array $supplierIds): void
    {
        $this->plantSupplierRepository->syncSuppliers($plantId, $supplierIds);
    }
}
