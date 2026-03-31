<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\Position\PositionRequest;
use App\Modules\Admin\Setting\Http\Resources\Position\PositionDataTableItemResource;
use App\Modules\Admin\Setting\Http\Resources\Position\PositionSelectItemResource;
use App\Modules\Admin\Setting\Services\PositionService;

class PositionController
{
    public function __construct(
        private PositionService $positionService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->positionService->dataTable($request);
        $items['data'] = PositionDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(PositionRequest $request)
    {
        $data = $request->validated();
        $this->positionService->save($data);
        return ApiResponse::success($data, 'Cargo guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->positionService->delete($id);
        return ApiResponse::success(null, 'Cargo eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->positionService->getSelectItems();
        return ApiResponse::success(PositionSelectItemResource::collection($items));
    }
}
