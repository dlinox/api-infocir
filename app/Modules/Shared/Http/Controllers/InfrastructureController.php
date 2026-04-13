<?php

namespace App\Modules\Shared\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Shared\Repositories\InfrastructureRepository;
use App\Modules\Shared\Http\Resources\InfrastructureSelectItemResource;
use App\Modules\Shared\Http\Resources\InfrastructureItemResource;
use Illuminate\Http\Request;

class InfrastructureController
{
    public function __construct(
        private InfrastructureRepository $infrastructureRepository
    ) {}

    public function selectItems(Request $request)
    {
        $items = $this->infrastructureRepository->getSelectItems($request->query('type'));
        $items = InfrastructureSelectItemResource::collection($items);
        return ApiResponse::success($items);
    }

    public function items()
    {
        $items = $this->infrastructureRepository->getSelectItems();
        $items = InfrastructureItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
