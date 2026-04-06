<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Services;

use App\Modules\Admin\Dairy\Catalog\Presentation\Repositories\PlantProductRepository;

class PlantProductService
{
    public function __construct(
        private PlantProductRepository $plantProductRepository
    ) {}

    public function findById(int $id)
    {
        return $this->plantProductRepository->findById($id);
    }

    public function dataTable($request)
    {
        return $this->plantProductRepository->dataTable($request);
    }

    public function getByPlant(int $plantId)
    {
        $items = $this->plantProductRepository->getByPlant($plantId);
        return $items->pluck('product_id');
    }

    public function getSelectItems(int $plantId): array
    {
        return $this->plantProductRepository->getSelectItems($plantId);
    }

    public function list(?int $plantId = null)
    {
        return $this->plantProductRepository->list($plantId);
    }

    public function sync(int $plantId, array $productIds)
    {
        return $this->plantProductRepository->sync($plantId, $productIds);
    }
}
