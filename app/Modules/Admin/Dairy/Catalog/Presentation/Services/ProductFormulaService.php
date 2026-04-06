<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Dairy\Catalog\Presentation\Repositories\ProductFormulaRepository;

class ProductFormulaService
{
    public function __construct(
        private ProductFormulaRepository $productFormulaRepository
    ) {}

    public function getByPresentation(int $presentationId, ?int $version = null): array
    {
        return $this->productFormulaRepository->getByPresentation($presentationId, $version);
    }

    public function getVersions(int $presentationId): array
    {
        return $this->productFormulaRepository->getVersions($presentationId);
    }

    public function saveItem(array $data)
    {
        return $this->productFormulaRepository->saveItem($data);
    }

    public function createVersion(int $presentationId): int
    {
        return $this->productFormulaRepository->createVersion($presentationId);
    }

    public function deleteItem(int $id): void
    {
        $this->productFormulaRepository->deleteItem($id);
    }
}
