<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\ProductType\ProductTypeRequest;
use App\Modules\Admin\Setting\Http\Resources\ProductType\ProductTypeDataTableItemResource;
use App\Modules\Admin\Setting\Http\Resources\ProductType\ProductTypeSelectItemResource;
use App\Modules\Admin\Setting\Services\ProductTypeService;

class ProductTypeController
{
    public function __construct(
        private ProductTypeService $productTypeService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->productTypeService->dataTable($request);
        $items['data'] = ProductTypeDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(ProductTypeRequest $request)
    {
        $data = $request->validated();
        $this->productTypeService->save($data);
        return ApiResponse::success($data, 'Tipo de producto guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->productTypeService->delete($id);
        return ApiResponse::success(null, 'Tipo de producto eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->productTypeService->getSelectItems();
        return ApiResponse::success(ProductTypeSelectItemResource::collection($items));
    }
}
