<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\TrainingLevel\TrainingLevelRequest;
use App\Modules\Admin\Setting\Http\Resources\TrainingLevel\TrainingLevelDataTableItemResource;
use App\Modules\Admin\Setting\Http\Resources\TrainingLevel\TrainingLevelSelectItemResource;
use App\Modules\Admin\Setting\Services\TrainingLevelService;

class TrainingLevelController
{
    public function __construct(
        private TrainingLevelService $trainingLevelService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->trainingLevelService->dataTable($request);
        $items['data'] = TrainingLevelDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(TrainingLevelRequest $request)
    {
        $data = $request->validated();
        $this->trainingLevelService->save($data);
        return ApiResponse::success($data, 'Nivel de capacitación guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->trainingLevelService->delete($id);
        return ApiResponse::success(null, 'Nivel de capacitación eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->trainingLevelService->getSelectItems();
        return ApiResponse::success(TrainingLevelSelectItemResource::collection($items));
    }
}
