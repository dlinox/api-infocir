<?php

namespace App\Modules\Admin\Dairy\Organization\Supplier\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Dairy\Organization\Supplier\Repositories\SupplierRepository;
use App\Modules\Admin\Dairy\Organization\Supplier\Repositories\Actions\CreateOrUpdateSupplierAction;

class SupplierService
{
    public function __construct(
        private SupplierRepository $supplierRepository,
        private CreateOrUpdateSupplierAction $createOrUpdateAction,
    ) {}

    public function dataTable(Request $request)
    {
        return $this->supplierRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->createOrUpdateAction->execute($data);
    }

    public function findById(int $id)
    {
        return $this->supplierRepository->findById($id);
    }

    public function delete(int $id)
    {
        return $this->supplierRepository->delete($id);
    }
}

