<?php

namespace App\Modules\Admin\Dairy\Finance\CashFlow\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Finance\CashFlow\Services\CashFlowService;

class CashFlowController
{
    public function __construct(
        private CashFlowService $cashFlowService
    ) {}

    public function overview(Request $request)
    {
        $plantId = $request->query('plant_id') ? (int) $request->query('plant_id') : null;
        $data = $this->cashFlowService->overview(
            $plantId,
            $request->query('from'),
            $request->query('to'),
        );
        return ApiResponse::success($data);
    }
}
