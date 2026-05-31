<?php

namespace App\Modules\PlantPanel\Supplier\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\PlantPanel\Supplier\Http\Requests\PlantPanelSupplierRequest;
use App\Modules\PlantPanel\Supplier\Http\Resources\PlantSupplierItemResource;
use App\Modules\PlantPanel\Supplier\Services\PlantPanelSupplierService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlantPanelSupplierController
{
    public function __construct(
        private PlantPanelSupplierService $service,
    ) {}

    public function list(): JsonResponse
    {
        $suppliers = $this->service->list();
        return ApiResponse::success(PlantSupplierItemResource::collection($suppliers));
    }

    public function save(PlantPanelSupplierRequest $request): JsonResponse
    {
        $this->service->save($request->validated());
        return ApiResponse::success(null, 'Proveedor guardado correctamente');
    }

    public function toggleActive(int $supplierId): JsonResponse
    {
        $this->service->toggleActive($supplierId);
        return ApiResponse::success(null, 'Estado actualizado correctamente');
    }

    public function updatePrice(int $supplierId, Request $request): JsonResponse
    {
        $price = $request->input('price_per_liter');
        $this->service->updatePrice($supplierId, $price !== null ? (float) $price : null);
        return ApiResponse::success(null, 'Precio actualizado correctamente');
    }
}
