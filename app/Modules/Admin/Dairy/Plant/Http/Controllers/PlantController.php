<?php

namespace App\Modules\Admin\Dairy\Plant\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Plant\Http\Requests\Plant\PlantRequest;
use App\Modules\Admin\Dairy\Plant\Http\Resources\Plant\PlantDataTableItemResource;
use App\Modules\Admin\Dairy\Plant\Http\Resources\Plant\PlantFormResource;
use App\Modules\Admin\Dairy\Plant\Services\PlantService;

class PlantController
{
    public function __construct(
        private PlantService $plantService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->plantService->dataTable($request);
        $items['data'] = PlantDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $plant = $this->plantService->findById($id);
        return ApiResponse::success(new PlantFormResource($plant));
    }

    public function save(PlantRequest $request)
    {
        $data = $request->validated();
        $this->plantService->save($data);
        return ApiResponse::success($data, 'Planta guardada correctamente');
    }

    public function delete(string $id)
    {
        $this->plantService->delete($id);
        return ApiResponse::success(null, 'Planta eliminada correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->plantService->getSelectItems();
        return ApiResponse::success($items);
    }
}
