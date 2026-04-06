<?php

namespace App\Modules\Admin\Dairy\Inventory\StockMovement\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Inventory\StockMovement\Http\Requests\StockMovement\StockMovementRequest;
use App\Modules\Admin\Dairy\Inventory\StockMovement\Http\Resources\StockMovement\StockMovementDataTableItemResource;
use App\Modules\Admin\Dairy\Inventory\StockMovement\Services\StockMovementService;

class StockMovementController
{
    public function __construct(
        private StockMovementService $stockMovementService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->stockMovementService->dataTable($request);
        $items['data'] = StockMovementDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function summary(string $presentationId, Request $request)
    {
        $plantId = (int) $request->query('plant_id');
        $data = $this->stockMovementService->summary((int) $presentationId, $plantId);
        return ApiResponse::success($data);
    }

    public function save(StockMovementRequest $request)
    {
        $data = $request->validated();
        $this->stockMovementService->save($data);
        return ApiResponse::success(null, 'Movimiento registrado correctamente');
    }

    public function delete(string $id)
    {
        $this->stockMovementService->delete((int) $id);
        return ApiResponse::success(null, 'Movimiento eliminado correctamente');
    }
}
