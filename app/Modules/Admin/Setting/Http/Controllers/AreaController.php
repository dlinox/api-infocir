<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\Area\AreaRequest;
use App\Modules\Admin\Setting\Http\Resources\Area\AreaDataTableItemResource;
use App\Modules\Admin\Setting\Http\Resources\Area\AreaSelectItemResource;
use App\Modules\Admin\Setting\Services\AreaService;

class AreaController
{
    public function __construct(
        private AreaService $areaService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->areaService->dataTable($request);
        $items['data'] = AreaDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(AreaRequest $request)
    {
        $data = $request->validated();
        $this->areaService->save($data);
        return ApiResponse::success($data, 'Área guardada correctamente');
    }

    public function delete(string $id)
    {
        $this->areaService->delete($id);
        return ApiResponse::success(null, 'Área eliminada correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->areaService->getSelectItems();
        return ApiResponse::success(AreaSelectItemResource::collection($items));
    }
}
