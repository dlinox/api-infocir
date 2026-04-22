<?php

namespace App\Modules\PlantPanel\Worker\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\PlantPanel\Worker\Http\Requests\PlantWorkerRequest;
use App\Modules\Admin\Dairy\Organization\Worker\Http\Resources\Worker\WorkerDataTableItemResource;
use App\Modules\Admin\Dairy\Organization\Worker\Http\Resources\Worker\WorkerFormResource;
use App\Modules\PlantPanel\Worker\Services\PlantWorkerService;

class PlantWorkerController
{
    public function __construct(
        private PlantWorkerService $workerService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->workerService->dataTable($request);
        $items['data'] = WorkerDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $worker = $this->workerService->findByPersonId((int) $id);
        return ApiResponse::success(new WorkerFormResource($worker));
    }

    public function save(PlantWorkerRequest $request)
    {
        $data = $request->validated();
        $this->workerService->save($data);
        return ApiResponse::success(null, 'Trabajador guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->workerService->delete((int) $id);
        return ApiResponse::success(null, 'Trabajador eliminado correctamente');
    }
}
