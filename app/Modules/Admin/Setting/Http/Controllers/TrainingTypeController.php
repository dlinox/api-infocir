<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\TrainingType\TrainingTypeRequest;
use App\Modules\Admin\Setting\Http\Resources\TrainingType\TrainingTypeDataTableItemResource;
use App\Modules\Admin\Setting\Http\Resources\TrainingType\TrainingTypeSelectItemResource;
use App\Modules\Admin\Setting\Services\TrainingTypeService;

class TrainingTypeController
{
    public function __construct(
        private TrainingTypeService $trainingTypeService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->trainingTypeService->dataTable($request);
        $items['data'] = TrainingTypeDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(TrainingTypeRequest $request)
    {
        $data = $request->validated();
        $this->trainingTypeService->save($data);
        return ApiResponse::success($data, 'Tipo de capacitación guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->trainingTypeService->delete($id);
        return ApiResponse::success(null, 'Tipo de capacitación eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->trainingTypeService->getSelectItems();
        return ApiResponse::success(TrainingTypeSelectItemResource::collection($items));
    }
}
