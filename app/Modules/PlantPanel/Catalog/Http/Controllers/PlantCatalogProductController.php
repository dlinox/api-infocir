<?php

namespace App\Modules\PlantPanel\Catalog\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\PlantPanel\Catalog\Http\Requests\AddProductRequest;
use App\Modules\PlantPanel\Catalog\Http\Requests\CreateProductRequest;
use App\Modules\PlantPanel\Catalog\Http\Requests\SavePresentationRequest;
use App\Modules\PlantPanel\Catalog\Services\PlantCatalogProductService;

class PlantCatalogProductController
{
    public function __construct(
        private PlantCatalogProductService $service,
    ) {}

    public function plantProducts()
    {
        $items = $this->service->plantProducts();
        return ApiResponse::success($items);
    }

    public function availableProducts()
    {
        $items = $this->service->availableProducts();
        return ApiResponse::success($items);
    }

    public function addProduct(AddProductRequest $request)
    {
        $data = $request->validated();
        $this->service->addProduct((int) $data['product_id']);
        return ApiResponse::success(null, 'Producto agregado correctamente');
    }

    public function createAndAdd(CreateProductRequest $request)
    {
        $data = $request->validated();
        $this->service->createAndAdd($data);
        return ApiResponse::success(null, 'Producto creado y agregado correctamente');
    }

    public function savePresentation(SavePresentationRequest $request)
    {
        $data = $request->validated();
        $presentation = $this->service->savePresentation($data);
        return ApiResponse::success(['id' => $presentation->id], 'Presentación guardada correctamente');
    }
}
