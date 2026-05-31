<?php

namespace App\Modules\SupplierPanel\MilkPayment\Services;

use App\Modules\Auth\Services\AuthService;
use App\Modules\SupplierPanel\MilkPayment\Repositories\MilkPaymentRepository;
use Illuminate\Http\Request;

class MilkPaymentService
{
    public function __construct(
        private MilkPaymentRepository $milkPaymentRepository,
        private AuthService $authService,
    ) {}

    public function dataTable(Request $request)
    {
        $supplierId = $this->authService->getMySupplierId();
        return $this->milkPaymentRepository->dataTable($request, $supplierId);
    }
}
