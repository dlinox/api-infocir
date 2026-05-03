<?php

namespace App\Modules\Admin\Dairy\Organization\Plant\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Organization\Plant\Services\PlantSupplierService;

class PlantSupplierController
{
    public function __construct(
        private PlantSupplierService $plantSupplierService
    ) {}

    public function getAssigned(string $plantId)
    {
        $ids = $this->plantSupplierService->getAssignedSupplierIds((int) $plantId);
        return ApiResponse::success($ids);
    }

    public function sync(string $plantId, Request $request)
    {
        $supplierIds = $request->input('supplier_ids', []);
        $this->plantSupplierService->syncSuppliers((int) $plantId, $supplierIds);
        return ApiResponse::success(null, 'Proveedores asignados correctamente');
    }
}
