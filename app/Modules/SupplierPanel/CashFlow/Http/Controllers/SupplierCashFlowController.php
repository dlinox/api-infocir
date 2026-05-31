<?php

namespace App\Modules\SupplierPanel\CashFlow\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Auth\Services\AuthService;
use App\Modules\SupplierPanel\CashFlow\Services\SupplierCashFlowService;
use Illuminate\Http\Request;

class SupplierCashFlowController
{
    public function __construct(
        private SupplierCashFlowService $supplierCashFlowService,
        private AuthService $authService,
    ) {}

    public function overview(Request $request)
    {
        $data = $this->supplierCashFlowService->overview(
            $this->authService->getMySupplierId(),
            $this->authService->getMyEntityId(),
            $request->query('from'),
            $request->query('to'),
        );
        return ApiResponse::success($data);
    }
}
