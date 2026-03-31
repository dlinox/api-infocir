<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\Plant\PlantRequest;
use App\Modules\Admin\Setting\Http\Resources\Plant\PlantDataTableItemResource;
use App\Modules\Admin\Setting\Services\PlantService;

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
}
