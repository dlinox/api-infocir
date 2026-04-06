<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Catalog\Presentation\Http\Requests\PlantProduct\PlantProductSyncRequest;
use App\Modules\Admin\Dairy\Catalog\Presentation\Http\Resources\PlantProduct\PlantProductDataTableItemResource;
use App\Modules\Admin\Dairy\Catalog\Presentation\Http\Resources\PlantProduct\PlantProductItemResource;
use App\Modules\Admin\Dairy\Catalog\Presentation\Services\PlantProductService;
use Illuminate\Http\Request;

class PlantProductController
{
    public function __construct(
        private PlantProductService $plantProductService
    ) {}

    public function get(string $id)
    {
        $item = $this->plantProductService->findById((int) $id);
        return ApiResponse::success(new PlantProductItemResource($item));
    }

    public function dataTable(Request $request)
    {
        $result = $this->plantProductService->dataTable($request);
        $result['data'] = PlantProductDataTableItemResource::collection($result['data']);
        return ApiResponse::success($result);
    }

    public function list(Request $request)
    {
        $plantId = $request->query('plant_id') ? (int) $request->query('plant_id') : null;
        $items = $this->plantProductService->list($plantId);
        return ApiResponse::success(PlantProductItemResource::collection($items));
    }

    public function getByPlant(string $plantId)
    {
        $items = $this->plantProductService->getByPlant((int) $plantId);
        return ApiResponse::success($items);
    }

    public function getSelectItems(string $plantId)
    {
        $items = $this->plantProductService->getSelectItems((int) $plantId);
        return ApiResponse::success($items);
    }

    public function sync(PlantProductSyncRequest $request)
    {
        $data = $request->validated();
        $this->plantProductService->sync($data['plant_id'], $data['product_ids']);
        return ApiResponse::success(null, 'Productos asignados correctamente');
    }
}
