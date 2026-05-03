<?php

namespace App\Modules\PlantPanel\Investment\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\PlantPanel\Investment\Http\Requests\FixedAsset\FixedAssetRequest;
use App\Modules\PlantPanel\Investment\Http\Resources\FixedAsset\FixedAssetDataTableItemResource;
use App\Modules\PlantPanel\Investment\Http\Resources\FixedAsset\FixedAssetFormResource;
use App\Modules\PlantPanel\Investment\Services\FixedAssetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FixedAssetController
{
    public function __construct(private FixedAssetService $service) {}

    public function dataTable(Request $request): JsonResponse
    {
        $items = $this->service->dataTable($request);
        $items['data'] = FixedAssetDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function get(int $id): JsonResponse
    {
        return ApiResponse::success(new FixedAssetFormResource($this->service->get($id)));
    }

    public function save(FixedAssetRequest $request): JsonResponse
    {
        $this->service->save($request->validated());
        return ApiResponse::success(null, 'Activo guardado correctamente');
    }

    public function delete(int $id): JsonResponse
    {
        $this->service->delete($id);
        return ApiResponse::success(null, 'Activo eliminado correctamente');
    }
}
