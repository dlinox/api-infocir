<?php

namespace App\Modules\Admin\Dairy\Supplier\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Dairy\Supplier\Repositories\SupplierRepository;
use App\Modules\Admin\Dairy\Supplier\Repositories\Actions\CreateOrUpdateSupplierAction;

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

    public function findByPersonId(int $personId)
    {
        return $this->supplierRepository->findByPersonId($personId);
    }

    public function delete(int $personId)
    {
        return $this->supplierRepository->delete($personId);
    }
}
