<?php

namespace App\Modules\SupplierPanel\CattleBreed\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\SupplierPanel\CattleBreed\Http\Requests\CattleBreedRequest;
use App\Modules\SupplierPanel\CattleBreed\Http\Resources\CattleBreedDataTableItemResource;
use App\Modules\SupplierPanel\CattleBreed\Http\Resources\CattleBreedFormResource;
use App\Modules\SupplierPanel\CattleBreed\Services\CattleBreedService;
use Illuminate\Http\Request;

class CattleBreedController
{
    public function __construct(
        private CattleBreedService $service,
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->service->dataTable($request);
        $items['data'] = CattleBreedDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $record = $this->service->findById((int) $id);
        return ApiResponse::success(new CattleBreedFormResource($record));
    }

    public function save(CattleBreedRequest $request)
    {
        $this->service->save($request->validated());
        return ApiResponse::success(null, 'Raza guardada correctamente');
    }

    public function delete(string $id)
    {
        $this->service->delete((int) $id);
        return ApiResponse::success(null, 'Raza eliminada correctamente');
    }
}
