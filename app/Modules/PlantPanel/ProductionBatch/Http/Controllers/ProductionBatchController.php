<?php

namespace App\Modules\PlantPanel\ProductionBatch\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\PlantPanel\ProductionBatch\Http\Requests\ProductionBatchRequest;
use App\Modules\PlantPanel\ProductionBatch\Http\Resources\ProductionBatchDataTableItemResource;
use App\Modules\PlantPanel\ProductionBatch\Http\Resources\ProductionBatchFormResource;
use App\Modules\PlantPanel\ProductionBatch\Services\ProductionBatchService;
use Illuminate\Http\Request;

class ProductionBatchController
{
    public function __construct(
        private ProductionBatchService $service,
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->service->dataTable($request);
        $items['data'] = ProductionBatchDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $batch = $this->service->findById((int) $id);
        return ApiResponse::success(new ProductionBatchFormResource($batch));
    }

    public function save(ProductionBatchRequest $request)
    {
        $this->service->save($request->validated());
        return ApiResponse::success(null, 'Lote guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->service->delete((int) $id);
        return ApiResponse::success(null, 'Lote eliminado correctamente');
    }

    public function cancel(string $id)
    {
        $this->service->cancel((int) $id);
        return ApiResponse::success(null, 'Lote anulado correctamente');
    }

    public function markReady(string $id)
    {
        $this->service->markReady((int) $id);
        return ApiResponse::success(null, 'Lote marcado como listo e ingresado al inventario');
    }

    public function supplierSelectItems()
    {
        return ApiResponse::success($this->service->getSupplierSelectItems());
    }
}
