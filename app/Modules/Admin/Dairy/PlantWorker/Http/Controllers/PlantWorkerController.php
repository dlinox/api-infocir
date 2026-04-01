<?php

namespace App\Modules\Admin\Dairy\PlantWorker\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\PlantWorker\Http\Requests\PlantWorker\PlantWorkerRequest;
use App\Modules\Admin\Dairy\PlantWorker\Http\Resources\PlantWorker\PlantWorkerDataTableItemResource;
use App\Modules\Admin\Dairy\PlantWorker\Http\Resources\PlantWorker\PlantWorkerFormResource;
use App\Modules\Admin\Dairy\PlantWorker\Services\PlantWorkerService;

class PlantWorkerController
{
    public function __construct(
        private PlantWorkerService $plantWorkerService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->plantWorkerService->dataTable($request);
        $items['data'] = PlantWorkerDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $worker = $this->plantWorkerService->findByPersonId((int) $id);
        return ApiResponse::success(new PlantWorkerFormResource($worker));
    }

    public function save(PlantWorkerRequest $request)
    {
        $data = $request->validated();
        $this->plantWorkerService->save($data);
        return ApiResponse::success(null, 'Trabajador guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->plantWorkerService->delete((int) $id);
        return ApiResponse::success(null, 'Trabajador eliminado correctamente');
    }
}
