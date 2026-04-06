<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Dairy\Catalog\Presentation\Repositories\ProductPresentationRepository;

class ProductPresentationService
{
    public function __construct(
        private ProductPresentationRepository $productPresentationRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->productPresentationRepository->dataTable($request);
    }

    public function findById(string $id)
    {
        return $this->productPresentationRepository->findById($id);
    }

    public function save(array $data)
    {
        return $this->productPresentationRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->productPresentationRepository->delete($id);
    }

    public function getSelectItems(int $plantProductId)
    {
        return $this->productPresentationRepository->getSelectItems($plantProductId);
    }
}
