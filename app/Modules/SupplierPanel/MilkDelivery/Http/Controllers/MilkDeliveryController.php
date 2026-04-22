<?php

namespace App\Modules\SupplierPanel\MilkDelivery\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\SupplierPanel\MilkDelivery\Http\Resources\MilkDeliveryDataTableItemResource;
use App\Modules\SupplierPanel\MilkDelivery\Services\MilkDeliveryService;
use Illuminate\Http\Request;

class MilkDeliveryController
{
    public function __construct(
        private MilkDeliveryService $service,
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->service->dataTable($request);
        $items['data'] = MilkDeliveryDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }
}
