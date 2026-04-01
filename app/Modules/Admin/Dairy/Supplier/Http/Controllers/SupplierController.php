<?php

namespace App\Modules\Admin\Dairy\Supplier\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Supplier\Http\Requests\Supplier\SupplierRequest;
use App\Modules\Admin\Dairy\Supplier\Http\Resources\Supplier\SupplierDataTableItemResource;
use App\Modules\Admin\Dairy\Supplier\Http\Resources\Supplier\SupplierFormResource;
use App\Modules\Admin\Dairy\Supplier\Services\SupplierService;

class SupplierController
{
    public function __construct(
        private SupplierService $supplierService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->supplierService->dataTable($request);
        $items['data'] = SupplierDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $supplier = $this->supplierService->findByPersonId((int) $id);
        return ApiResponse::success(new SupplierFormResource($supplier));
    }

    public function save(SupplierRequest $request)
    {
        $data = $request->validated();
        $this->supplierService->save($data);
        return ApiResponse::success(null, 'Proveedor guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->supplierService->delete((int) $id);
        return ApiResponse::success(null, 'Proveedor eliminado correctamente');
    }
}
