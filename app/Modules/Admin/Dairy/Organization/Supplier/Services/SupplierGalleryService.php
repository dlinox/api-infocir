<?php

namespace App\Modules\Admin\Dairy\Organization\Supplier\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Dairy\Organization\Supplier\Repositories\SupplierGalleryRepository;

class SupplierGalleryService
{
    public function __construct(
        private SupplierGalleryRepository $supplierGalleryRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->supplierGalleryRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->supplierGalleryRepository->createOrUpdate($data);
    }

    public function delete(int $id): void
    {
        $this->supplierGalleryRepository->delete($id);
    }
}
