<?php

namespace App\Modules\PlantPanel\CashFlow\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Auth\Services\AuthService;
use App\Modules\Admin\Dairy\Finance\CashFlow\Services\CashFlowService;

class PlantCashFlowController
{
    public function __construct(
        private CashFlowService $cashFlowService,
        private AuthService $authService,
    ) {}

    public function overview(Request $request)
    {
        $data = $this->cashFlowService->overview(
            $this->authService->getMyPlantId(),
            $request->query('from'),
            $request->query('to'),
        );
        return ApiResponse::success($data);
    }
}
