<?php

namespace App\Modules\SupplierPanel\MilkDelivery\Services;

use App\Modules\Auth\Services\AuthService;
use App\Modules\SupplierPanel\MilkDelivery\Repositories\MilkDeliveryRepository;
use Illuminate\Http\Request;

class MilkDeliveryService
{
    public function __construct(
        private MilkDeliveryRepository $repository,
        private AuthService $authService,
    ) {}

    public function dataTable(Request $request)
    {
        $supplierId = $this->authService->getMySupplierId();
        return $this->repository->dataTable($request, $supplierId);
    }
}
