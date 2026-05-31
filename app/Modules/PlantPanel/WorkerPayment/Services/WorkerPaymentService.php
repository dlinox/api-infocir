<?php

namespace App\Modules\PlantPanel\WorkerPayment\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Core\Entity;
use App\Models\Dairy\Worker;
use App\Models\Dairy\WorkerPayment;
use App\Modules\Auth\Services\AuthService;
use App\Modules\PlantPanel\WorkerPayment\Repositories\WorkerPaymentRepository;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class WorkerPaymentService
{
    public function __construct(
        private WorkerPaymentRepository $repository,
        private AuthService $authService,
    ) {}

    public function dataTable(Request $request)
    {
        $plantId = $this->authService->getMyPlantId();
        return $this->repository->dataTable($request, $plantId);
    }

    public function findById(int $id): WorkerPayment
    {
        $plantId = $this->authService->getMyPlantId();
        $payment = $this->repository->findByIdForPlant($id, $plantId);
        if (!$payment) {
            throw new ApiException('Pago no encontrado', 404);
        }
        return $payment;
    }

    public function save(array $data): WorkerPayment
    {
        $plantId = $this->authService->getMyPlantId();
        $entityId = $this->authService->getMyEntityId();

        $worker = $this->assertWorkerBelongsToEntity((int) $data['worker_person_id'], $entityId);

        if (!empty($data['id'])) {
            $existing = $this->repository->findByIdForPlant((int) $data['id'], $plantId);
            if (!$existing) {
                throw new ApiException('Pago no encontrado', 404);
            }
        } else {
            $duplicate = $this->repository->findByUniquePeriod(
                $plantId,
                (int) $data['worker_person_id'],
                (int) $data['period_year'],
                (int) $data['period_month']
            );

            if ($duplicate) {
                throw new ApiException('Ya existe un pago registrado para ese trabajador y período', 422);
            }
        }

        $paidBy = JWTAuth::user()?->id;
        if ($paidBy && $data['status'] === 'paid') {
            $data['paid_by'] = $paidBy;
        }
        if ($paidBy && empty($data['id'])) {
            $data['created_by'] = $paidBy;
        }

        $data['base_salary'] = $data['base_salary'] ?? (float) $worker->monthly_salary;

        return $this->repository->createOrUpdate($data, $plantId);
    }

    public function cancel(int $id): void
    {
        $payment = $this->findById($id);
        $payment->update([
            'status' => 'cancelled',
            'paid_at' => null,
        ]);
    }

    public function markAsPaid(int $id): void
    {
        $payment = $this->findById($id);
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
            'paid_by' => JWTAuth::user()?->id,
        ]);
    }

    public function summarizePeriod(int $workerPersonId, int $periodYear, int $periodMonth): array
    {
        $plantId = $this->authService->getMyPlantId();
        $entityId = $this->authService->getMyEntityId();
        $this->assertWorkerBelongsToEntity($workerPersonId, $entityId);

        return $this->repository->summarizePeriod(
            $plantId,
            $workerPersonId,
            $periodYear,
            $periodMonth,
        );
    }

    public function getWorkerSelectItems(): array
    {
        return $this->repository->getWorkerSelectItems($this->authService->getMyEntityId());
    }

    public function payCurrentPeriod(int $workerPersonId): void
    {
        $plantId = $this->authService->getMyPlantId();
        $entityId = $this->authService->getMyEntityId();
        $worker = $this->assertWorkerBelongsToEntity($workerPersonId, $entityId);

        if (!$worker->is_active) {
            throw new ApiException('No se puede registrar pago para un trabajador inactivo', 422);
        }

        $this->repository->payCurrentPeriod(
            $plantId,
            $workerPersonId,
            (float) $worker->monthly_salary,
            JWTAuth::user()?->id,
        );
    }

    private function assertWorkerBelongsToEntity(int $workerPersonId, int $entityId): Worker
    {
        $worker = Worker::query()
            ->where('person_id', $workerPersonId)
            ->where('entity_id', $entityId)
            ->first();

        if (!$worker) {
            throw new ApiException('El trabajador no pertenece a la entidad autenticada', 403);
        }

        $entity = Entity::find($entityId);
        if (!$entity || $entity->entityable_type !== 'dairy_plants') {
            throw new ApiException('La entidad autenticada no es una planta', 403);
        }

        return $worker;
    }
}
