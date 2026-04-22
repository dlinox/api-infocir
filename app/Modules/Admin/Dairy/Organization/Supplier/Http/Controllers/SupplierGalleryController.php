<?php

namespace App\Modules\Admin\Dairy\Organization\Supplier\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Organization\Supplier\Http\Requests\SupplierGallery\SupplierGalleryRequest;
use App\Modules\Admin\Dairy\Organization\Supplier\Http\Resources\SupplierGallery\SupplierGalleryItemResource;
use App\Modules\Admin\Dairy\Organization\Supplier\Services\SupplierGalleryService;

class SupplierGalleryController
{
    public function __construct(
        private SupplierGalleryService $supplierGalleryService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->supplierGalleryService->dataTable($request);
        $items['data'] = SupplierGalleryItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(SupplierGalleryRequest $request)
    {
        $data = $request->validated();
        $this->supplierGalleryService->save($data);
        return ApiResponse::success(null, 'Imagen guardada correctamente');
    }

    public function delete(int $id)
    {
        $this->supplierGalleryService->delete($id);
        return ApiResponse::success(null, 'Imagen eliminada correctamente');
    }
}
