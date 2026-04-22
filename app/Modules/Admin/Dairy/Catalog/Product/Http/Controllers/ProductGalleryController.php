<?php

namespace App\Modules\Admin\Dairy\Catalog\Product\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Catalog\Product\Http\Requests\ProductGallery\ProductGalleryRequest;
use App\Modules\Admin\Dairy\Catalog\Product\Http\Resources\ProductGallery\ProductGalleryItemResource;
use App\Modules\Admin\Dairy\Catalog\Product\Services\ProductGalleryService;

class ProductGalleryController
{
    public function __construct(
        private ProductGalleryService $productGalleryService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->productGalleryService->dataTable($request);
        $items['data'] = ProductGalleryItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(ProductGalleryRequest $request)
    {
        $data = $request->validated();
        $this->productGalleryService->save($data);
        return ApiResponse::success(null, 'Imagen guardada correctamente');
    }

    public function delete(int $id)
    {
        $this->productGalleryService->delete($id);
        return ApiResponse::success(null, 'Imagen eliminada correctamente');
    }

    public function selectPresentations(int $productId)
    {
        $items = $this->productGalleryService->getPresentationsByProduct($productId);
        return ApiResponse::success($items);
    }
}
