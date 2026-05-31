<?php

namespace App\Modules\SupplierPanel\MilkPayment\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\SupplierPanel\MilkPayment\Http\Resources\MilkPaymentDataTableItemResource;
use App\Modules\SupplierPanel\MilkPayment\Services\MilkPaymentService;
use Illuminate\Http\Request;

class MilkPaymentController
{
    public function __construct(
        private MilkPaymentService $milkPaymentService,
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->milkPaymentService->dataTable($request);
        $items['data'] = MilkPaymentDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }
}
