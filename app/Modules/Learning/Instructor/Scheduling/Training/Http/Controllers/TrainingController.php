<?php

namespace App\Modules\Learning\Instructor\Scheduling\Training\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Learning\Instructor\Scheduling\Training\Http\Requests\Training\TrainingRequest;
use App\Modules\Learning\Instructor\Scheduling\Training\Http\Resources\Training\TrainingDataTableItemResource;
use App\Modules\Learning\Instructor\Scheduling\Training\Http\Resources\Training\TrainingDetailResource;
use App\Modules\Learning\Instructor\Scheduling\Training\Services\TrainingService;

class TrainingController
{
    public function __construct(
        private TrainingService $trainingService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->trainingService->dataTable($request);
        $items['data'] = TrainingDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $training = $this->trainingService->findById((int) $id);
        return ApiResponse::success(new TrainingDetailResource($training));
    }

    public function save(TrainingRequest $request)
    {
        $data = $request->validated();
        $this->trainingService->save($data);
        return ApiResponse::success(null, 'Capacitación guardada correctamente');
    }

    public function delete(string $id)
    {
        $this->trainingService->delete((int) $id);
        return ApiResponse::success(null, 'Capacitación eliminada correctamente');
    }
}
