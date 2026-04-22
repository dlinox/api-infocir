<?php

namespace App\Modules\PlantPanel\SupplierPayment\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\PlantPanel\SupplierPayment\Http\Requests\SupplierPaymentRequest;
use App\Modules\PlantPanel\SupplierPayment\Http\Resources\SupplierPaymentDataTableItemResource;
use App\Modules\PlantPanel\SupplierPayment\Http\Resources\SupplierPaymentFormResource;
use App\Modules\PlantPanel\SupplierPayment\Services\SupplierPaymentService;
use Illuminate\Http\Request;

class SupplierPaymentController
{
    public function __construct(
        private SupplierPaymentService $service,
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->service->dataTable($request);
        $items['data'] = SupplierPaymentDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $payment = $this->service->findById((int) $id);
        return ApiResponse::success(new SupplierPaymentFormResource($payment));
    }

    public function save(SupplierPaymentRequest $request)
    {
        $this->service->save($request->validated());
        return ApiResponse::success(null, 'Pago guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->service->delete((int) $id);
        return ApiResponse::success(null, 'Pago eliminado correctamente');
    }

    public function summarizePeriod(Request $request)
    {
        $request->validate([
            'supplierId' => ['required', 'integer'],
            'from'       => ['required', 'date'],
            'to'         => ['required', 'date', 'after_or_equal:from'],
        ]);

        $summary = $this->service->summarizePeriod(
            (int) $request->supplierId,
            $request->from,
            $request->to,
        );

        return ApiResponse::success($summary);
    }

    public function supplierSelectItems()
    {
        return ApiResponse::success($this->service->getSupplierSelectItems());
    }

    public function markAsPaid(string $id)
    {
        $this->service->markAsPaid((int) $id);
        return ApiResponse::success(null, 'Pago marcado como pagado correctamente');
    }

    public function cancel(string $id)
    {
        $this->service->cancel((int) $id);
        return ApiResponse::success(null, 'Pago anulado correctamente');
    }
}
