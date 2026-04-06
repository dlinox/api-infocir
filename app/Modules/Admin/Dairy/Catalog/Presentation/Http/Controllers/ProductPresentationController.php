<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Catalog\Presentation\Http\Requests\ProductPresentation\ProductPresentationRequest;
use App\Modules\Admin\Dairy\Catalog\Presentation\Http\Resources\ProductPresentation\ProductPresentationDataTableItemResource;
use App\Modules\Admin\Dairy\Catalog\Presentation\Http\Resources\ProductPresentation\ProductPresentationFormResource;
use App\Modules\Admin\Dairy\Catalog\Presentation\Http\Resources\ProductPresentation\ProductPresentationSelectItemResource;
use App\Modules\Admin\Dairy\Catalog\Presentation\Services\ProductPresentationService;

class ProductPresentationController
{
    public function __construct(
        private ProductPresentationService $productPresentationService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->productPresentationService->dataTable($request);
        $items['data'] = ProductPresentationDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $presentation = $this->productPresentationService->findById($id);
        return ApiResponse::success(new ProductPresentationFormResource($presentation));
    }

    public function save(ProductPresentationRequest $request)
    {
        $data = $request->validated();
        $this->productPresentationService->save($data);
        return ApiResponse::success($data, 'Presentación guardada correctamente');
    }

    public function delete(string $id)
    {
        $this->productPresentationService->delete($id);
        return ApiResponse::success(null, 'Presentación eliminada correctamente');
    }

    public function getSelectItems(string $plantProductId)
    {
        $items = $this->productPresentationService->getSelectItems((int) $plantProductId);
        $items = ProductPresentationSelectItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
