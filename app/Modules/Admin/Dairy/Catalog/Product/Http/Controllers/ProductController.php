<?php

namespace App\Modules\Admin\Dairy\Catalog\Product\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Catalog\Product\Http\Requests\Product\ProductRequest;
use App\Modules\Admin\Dairy\Catalog\Product\Http\Resources\Product\ProductDataTableItemResource;
use App\Modules\Admin\Dairy\Catalog\Product\Http\Resources\Product\ProductFormResource;
use App\Modules\Admin\Dairy\Catalog\Product\Services\ProductService;

class ProductController
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->productService->dataTable($request);
        $items['data'] = ProductDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $product = $this->productService->findById($id);
        return ApiResponse::success(new ProductFormResource($product));
    }

    public function save(ProductRequest $request)
    {
        $data = $request->validated();
        $this->productService->save($data);
        return ApiResponse::success($data, 'Producto guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->productService->delete($id);
        return ApiResponse::success(null, 'Producto eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->productService->getSelectItems();
        return ApiResponse::success($items);
    }
}
