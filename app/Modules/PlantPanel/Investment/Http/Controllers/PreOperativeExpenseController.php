<?php

namespace App\Modules\PlantPanel\Investment\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\PlantPanel\Investment\Http\Requests\PreOperativeExpense\PreOperativeExpenseRequest;
use App\Modules\PlantPanel\Investment\Http\Resources\PreOperativeExpense\PreOperativeExpenseDataTableItemResource;
use App\Modules\PlantPanel\Investment\Http\Resources\PreOperativeExpense\PreOperativeExpenseFormResource;
use App\Modules\PlantPanel\Investment\Services\PreOperativeExpenseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PreOperativeExpenseController
{
    public function __construct(private PreOperativeExpenseService $service) {}

    public function dataTable(Request $request): JsonResponse
    {
        $items = $this->service->dataTable($request);
        $items['data'] = PreOperativeExpenseDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function get(int $id): JsonResponse
    {
        return ApiResponse::success(new PreOperativeExpenseFormResource($this->service->get($id)));
    }

    public function save(PreOperativeExpenseRequest $request): JsonResponse
    {
        $this->service->save($request->validated());
        return ApiResponse::success(null, 'Permiso guardado correctamente');
    }

    public function delete(int $id): JsonResponse
    {
        $this->service->delete($id);
        return ApiResponse::success(null, 'Permiso eliminado correctamente');
    }
}
