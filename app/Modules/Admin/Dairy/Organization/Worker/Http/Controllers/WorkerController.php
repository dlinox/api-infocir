<?php

namespace App\Modules\Admin\Dairy\Organization\Worker\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Organization\Worker\Http\Requests\Worker\WorkerRequest;
use App\Modules\Admin\Dairy\Organization\Worker\Http\Resources\Worker\WorkerDataTableItemResource;
use App\Modules\Admin\Dairy\Organization\Worker\Http\Resources\Worker\WorkerFormResource;
use App\Modules\Admin\Dairy\Organization\Worker\Services\WorkerService;

class WorkerController
{
    public function __construct(
        private WorkerService $workerService
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

    public function save(WorkerRequest $request)
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
