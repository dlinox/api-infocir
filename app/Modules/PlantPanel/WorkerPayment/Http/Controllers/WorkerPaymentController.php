<?php

namespace App\Modules\PlantPanel\WorkerPayment\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\PlantPanel\WorkerPayment\Http\Requests\WorkerPaymentRequest;
use App\Modules\PlantPanel\WorkerPayment\Http\Resources\WorkerPaymentDataTableItemResource;
use App\Modules\PlantPanel\WorkerPayment\Http\Resources\WorkerPaymentFormResource;
use App\Modules\PlantPanel\WorkerPayment\Services\WorkerPaymentService;
use Illuminate\Http\Request;

class WorkerPaymentController
{
    public function __construct(
        private WorkerPaymentService $service,
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->service->dataTable($request);
        $items['data'] = WorkerPaymentDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $payment = $this->service->findById((int) $id);
        return ApiResponse::success(new WorkerPaymentFormResource($payment));
    }

    public function save(WorkerPaymentRequest $request)
    {
        $this->service->save($request->validated());
        return ApiResponse::success(null, 'Pago guardado correctamente');
    }

    public function summarizePeriod(Request $request)
    {
        $request->validate([
            'workerPersonId' => ['required', 'integer'],
            'periodYear'     => ['required', 'integer', 'min:2000', 'max:2200'],
            'periodMonth'    => ['required', 'integer', 'min:1', 'max:12'],
        ]);

        $summary = $this->service->summarizePeriod(
            (int) $request->workerPersonId,
            (int) $request->periodYear,
            (int) $request->periodMonth,
        );

        return ApiResponse::success($summary);
    }

    public function workerSelectItems()
    {
        return ApiResponse::success($this->service->getWorkerSelectItems());
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

    public function payCurrentPeriod(string $workerPersonId)
    {
        $this->service->payCurrentPeriod((int) $workerPersonId);
        return ApiResponse::success(null, 'Pago del período actual registrado correctamente');
    }
}
