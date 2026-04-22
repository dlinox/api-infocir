<?php

namespace App\Modules\PlantPanel\SupplierPayment\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Dairy\Supplier;
use App\Models\Dairy\SupplierPayment;
use App\Modules\Auth\Services\AuthService;
use App\Modules\PlantPanel\SupplierPayment\Repositories\SupplierPaymentRepository;
use Illuminate\Http\Request;

class SupplierPaymentService
{
    public function __construct(
        private SupplierPaymentRepository $repository,
        private AuthService $authService,
    ) {}

    public function dataTable(Request $request)
    {
        $plantId = $this->authService->getMyPlantId();
        return $this->repository->dataTable($request, $plantId);
    }

    public function findById(int $id): SupplierPayment
    {
        $plantId = $this->authService->getMyPlantId();
        $payment = $this->repository->findByIdForPlant($id, $plantId);
        if (!$payment) throw new ApiException('Pago no encontrado', 404);
        return $payment;
    }

    public function save(array $data): SupplierPayment
    {
        $plantId = $this->authService->getMyPlantId();
        return $this->repository->createOrUpdate($data, $plantId);
    }

    public function delete(int $id): void
    {
        $plantId = $this->authService->getMyPlantId();
        $this->repository->delete($id, $plantId);
    }

    public function cancel(int $id): void
    {
        $plantId = $this->authService->getMyPlantId();
        $payment = $this->repository->findByIdForPlant($id, $plantId);
        if (!$payment) throw new ApiException('Pago no encontrado', 404);
        $payment->update(['status' => 'cancelled']);
    }

    public function summarizePeriod(int $supplierId, string $from, string $to): array
    {
        $plantId = $this->authService->getMyPlantId();
        return $this->repository->summarizePeriod($plantId, $supplierId, $from, $to);
    }

    public function markAsPaid(int $id): void
    {
        $plantId = $this->authService->getMyPlantId();
        $payment = $this->repository->findByIdForPlant($id, $plantId);
        if (!$payment) throw new ApiException('Pago no encontrado', 404);
        $payment->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function getSupplierSelectItems(): array
    {
        return Supplier::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'trade_name'])
            ->map(fn ($s) => [
                'value' => $s->id,
                'title' => $s->trade_name ?: $s->name,
            ])
            ->toArray();
    }
}
