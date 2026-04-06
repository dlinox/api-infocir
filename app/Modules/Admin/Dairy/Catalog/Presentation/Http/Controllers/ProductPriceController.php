<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Catalog\Presentation\Http\Requests\ProductPrice\ProductPriceRequest;
use App\Modules\Admin\Dairy\Catalog\Presentation\Http\Resources\ProductPrice\ProductPriceItemResource;
use App\Modules\Admin\Dairy\Catalog\Presentation\Services\ProductPriceService;

class ProductPriceController
{
    public function __construct(
        private ProductPriceService $productPriceService
    ) {}

    public function getByPresentation(string $presentationId)
    {
        $items = $this->productPriceService->getByPresentation((int) $presentationId);
        $items = ProductPriceItemResource::collection($items);
        return ApiResponse::success($items);
    }

    public function save(ProductPriceRequest $request)
    {
        $data = $request->validated();
        $this->productPriceService->save($data);
        return ApiResponse::success(null, 'Precio registrado correctamente');
    }

    public function delete(string $id)
    {
        $this->productPriceService->delete((int) $id);
        return ApiResponse::success(null, 'Precio eliminado correctamente');
    }
}
