<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\UnitMeasure\UnitMeasureRequest;
use App\Modules\Admin\Setting\Http\Resources\UnitMeasure\UnitMeasureDataTableItemResource;
use App\Modules\Admin\Setting\Http\Resources\UnitMeasure\UnitMeasureSelectItemResource;
use App\Modules\Admin\Setting\Services\UnitMeasureService;

class UnitMeasureController
{
    public function __construct(
        private UnitMeasureService $unitMeasureService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->unitMeasureService->dataTable($request);
        $items['data'] = UnitMeasureDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(UnitMeasureRequest $request)
    {
        $data = $request->validated();
        $this->unitMeasureService->save($data);
        return ApiResponse::success($data, 'Unidad de medida guardada correctamente');
    }

    public function delete(string $id)
    {
        $this->unitMeasureService->delete($id);
        return ApiResponse::success(null, 'Unidad de medida eliminada correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->unitMeasureService->getSelectItems();
        return ApiResponse::success(UnitMeasureSelectItemResource::collection($items));
    }
}
