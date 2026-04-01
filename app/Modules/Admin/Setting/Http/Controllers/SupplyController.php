<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\Supply\SupplyRequest;
use App\Modules\Admin\Setting\Http\Resources\Supply\SupplyDataTableItemResource;
use App\Modules\Admin\Setting\Http\Resources\Supply\SupplySelectItemResource;
use App\Modules\Admin\Setting\Services\SupplyService;

class SupplyController
{
    public function __construct(
        private SupplyService $supplyService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->supplyService->dataTable($request);
        $items['data'] = SupplyDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(SupplyRequest $request)
    {
        $data = $request->validated();
        $this->supplyService->save($data);
        return ApiResponse::success($data, 'Insumo guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->supplyService->delete($id);
        return ApiResponse::success(null, 'Insumo eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->supplyService->getSelectItems();
        return ApiResponse::success(SupplySelectItemResource::collection($items));
    }
}
