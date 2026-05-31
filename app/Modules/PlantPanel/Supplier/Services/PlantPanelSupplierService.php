<?php

namespace App\Modules\PlantPanel\Supplier\Services;

use App\Common\Exceptions\ApiException;
use App\Modules\Auth\Services\AuthService;
use App\Modules\PlantPanel\Supplier\Repositories\PlantPanelSupplierRepository;
use Illuminate\Support\Collection;

class PlantPanelSupplierService
{
    public function __construct(
        private PlantPanelSupplierRepository $repository,
        private AuthService $authService,
    ) {}

    public function list(): Collection
    {
        $plantId = $this->authService->getMyPlantId();
        return $this->repository->getForPlant($plantId);
    }

    public function save(array $data): void
    {
        $plantId = $this->authService->getMyPlantId();
        $this->repository->createForPlant($plantId, $data);
    }

    public function toggleActive(int $supplierId): void
    {
        $plantId = $this->authService->getMyPlantId();
        $pivot = $this->repository->getPivot($plantId, $supplierId);
        if (!$pivot) throw new ApiException('Proveedor no asignado a esta planta', 404);
        $this->repository->toggleActive($plantId, $supplierId);
    }

    public function updatePrice(int $supplierId, ?float $price): void
    {
        $plantId = $this->authService->getMyPlantId();
        $pivot = $this->repository->getPivot($plantId, $supplierId);
        if (!$pivot) throw new ApiException('Proveedor no asignado a esta planta', 404);
        $this->repository->updatePrice($plantId, $supplierId, $price);
    }
}
