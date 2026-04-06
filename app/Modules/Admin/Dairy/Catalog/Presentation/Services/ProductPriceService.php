<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Services;

use App\Modules\Admin\Dairy\Catalog\Presentation\Repositories\ProductPriceRepository;

class ProductPriceService
{
    public function __construct(
        private ProductPriceRepository $productPriceRepository
    ) {}

    public function getByPresentation(int $presentationId): array
    {
        return $this->productPriceRepository->getByPresentation($presentationId);
    }

    public function save(array $data)
    {
        return $this->productPriceRepository->save($data);
    }

    public function delete(int $id): void
    {
        $this->productPriceRepository->delete($id);
    }
}
