<?php

namespace App\Modules\PlantPanel\MilkCollection\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\PlantPanel\MilkCollection\Http\Requests\MilkCollectionRequest;
use App\Modules\PlantPanel\MilkCollection\Http\Resources\MilkCollectionDataTableItemResource;
use App\Modules\PlantPanel\MilkCollection\Http\Resources\MilkCollectionFormResource;
use App\Modules\PlantPanel\MilkCollection\Services\MilkCollectionService;
use Illuminate\Http\Request;

class MilkCollectionController
{
    public function __construct(
        private MilkCollectionService $service,
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->service->dataTable($request);
        $items['data'] = MilkCollectionDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $collection = $this->service->findById((int) $id);
        return ApiResponse::success(new MilkCollectionFormResource($collection));
    }

    public function save(MilkCollectionRequest $request)
    {
        $this->service->save($request->validated());
        return ApiResponse::success(null, 'Recolección guardada correctamente');
    }

    public function delete(string $id)
    {
        $this->service->delete((int) $id);
        return ApiResponse::success(null, 'Recolección eliminada correctamente');
    }

    public function supplierSelectItems()
    {
        return ApiResponse::success($this->service->getSupplierSelectItems());
    }

    public function updatePaymentStatus(string $id, Request $request)
    {
        $status = $request->input('payment_status');
        if (!in_array($status, ['pending', 'paid', 'cancelled'])) {
            return ApiResponse::error('Estado de pago inválido', 422);
        }
        $this->service->updatePaymentStatus((int) $id, $status);
        return ApiResponse::success(null, 'Estado de pago actualizado correctamente');
    }
}
