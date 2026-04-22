<?php

namespace App\Modules\Admin\Dairy\Catalog\Product\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Dairy\Catalog\Product\Repositories\ProductGalleryRepository;

class ProductGalleryService
{
    public function __construct(
        private ProductGalleryRepository $productGalleryRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->productGalleryRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->productGalleryRepository->createOrUpdate($data);
    }

    public function delete(int $id): void
    {
        $this->productGalleryRepository->delete($id);
    }

    public function getPresentationsByProduct(int $productId): array
    {
        return $this->productGalleryRepository->getPresentationsByProduct($productId);
    }
}
