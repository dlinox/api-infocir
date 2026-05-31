<?php

namespace App\Modules\PlantPanel\WorkerPayment\Repositories;

use App\Models\Core\Entity;
use App\Models\Dairy\Worker;
use App\Models\Dairy\WorkerPayment;
use Carbon\Carbon;

class WorkerPaymentRepository
{
    public function dataTable($request, int $plantId)
    {
        $query = WorkerPayment::query()
            ->leftJoin('dairy_workers', 'dairy_workers.person_id', '=', 'dairy_worker_payments.worker_person_id')
            ->leftJoin('core_persons', 'core_persons.id', '=', 'dairy_workers.person_id')
            ->where('dairy_worker_payments.plant_id', $plantId)
            ->select([
                'dairy_worker_payments.*',
                'dairy_workers.person_id as worker_person_id_alias',
                'dairy_workers.monthly_salary as worker_monthly_salary',
                'core_persons.document_type as worker_document_type',
                'core_persons.document_number as worker_document_number',
                'core_persons.name as worker_name',
                'core_persons.paternal_surname as worker_paternal_surname',
                'core_persons.maternal_surname as worker_maternal_surname',
            ]);

        if (empty($request->sortBy)) {
            $query->orderBy('dairy_worker_payments.period_year', 'desc')
                ->orderBy('dairy_worker_payments.period_month', 'desc')
                ->orderBy('dairy_worker_payments.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findByIdForPlant(int $id, int $plantId): ?WorkerPayment
    {
        return WorkerPayment::where('id', $id)
            ->where('plant_id', $plantId)
            ->first();
    }

    public function createOrUpdate(array $data, int $plantId): WorkerPayment
    {
        $data['plant_id'] = $plantId;
        $data['bonuses'] = $data['bonuses'] ?? 0;
        $data['deductions'] = $data['deductions'] ?? 0;
        $data['net_amount'] = round(((float) $data['base_salary'] + (float) $data['bonuses']) - (float) $data['deductions'], 2);

        if ($data['status'] === 'paid' && empty($data['paid_at'])) {
            $data['paid_at'] = now();
        }

        if ($data['status'] !== 'paid') {
            $data['paid_at'] = null;
        }

        if (!empty($data['id'])) {
            $payment = WorkerPayment::where('id', $data['id'])
                ->where('plant_id', $plantId)
                ->firstOrFail();
            $payment->update($data);
            return $payment;
        }

        return WorkerPayment::create($data);
    }

    public function findByUniquePeriod(
        int $plantId,
        int $workerPersonId,
        int $periodYear,
        int $periodMonth
    ): ?WorkerPayment {
        return WorkerPayment::query()
            ->where('plant_id', $plantId)
            ->where('worker_person_id', $workerPersonId)
            ->where('period_year', $periodYear)
            ->where('period_month', $periodMonth)
            ->first();
    }

    public function summarizePeriod(int $plantId, int $workerPersonId, int $periodYear, int $periodMonth): array
    {
        $entity = Entity::query()
            ->where('entityable_type', 'dairy_plants')
            ->where('entityable_id', $plantId)
            ->first();

        if (!$entity) {
            return [
                'baseSalary' => 0.0,
                'bonuses' => 0.0,
                'deductions' => 0.0,
                'netAmount' => 0.0,
                'existingStatus' => null,
            ];
        }

        $worker = Worker::query()
            ->where('person_id', $workerPersonId)
            ->where('entity_id', $entity->id)
            ->first();

        $baseSalary = (float) ($worker?->monthly_salary ?? 0);

        $existing = $this->findByUniquePeriod($plantId, $workerPersonId, $periodYear, $periodMonth);

        if (!$existing) {
            return [
                'baseSalary' => $baseSalary,
                'bonuses' => 0.0,
                'deductions' => 0.0,
                'netAmount' => round($baseSalary, 2),
                'existingStatus' => null,
            ];
        }

        return [
            'baseSalary' => (float) $existing->base_salary,
            'bonuses' => (float) $existing->bonuses,
            'deductions' => (float) $existing->deductions,
            'netAmount' => (float) $existing->net_amount,
            'existingStatus' => $existing->status,
        ];
    }

    public function getWorkerSelectItems(int $entityId): array
    {
        return Worker::query()
            ->join('core_persons', 'core_persons.id', '=', 'dairy_workers.person_id')
            ->where('dairy_workers.entity_id', $entityId)
            ->where('dairy_workers.is_active', true)
            ->orderBy('core_persons.paternal_surname')
            ->orderBy('core_persons.name')
            ->select([
                'dairy_workers.person_id',
                'dairy_workers.monthly_salary',
                'core_persons.name',
                'core_persons.paternal_surname',
                'core_persons.maternal_surname',
            ])
            ->get()
            ->map(fn ($worker) => [
                'value' => (int) $worker->person_id,
                'title' => collect([
                    $worker->name,
                    $worker->paternal_surname,
                    $worker->maternal_surname,
                ])->filter()->implode(' '),
                'monthlySalary' => (float) $worker->monthly_salary,
            ])
            ->toArray();
    }

    public function payCurrentPeriod(
        int $plantId,
        int $workerPersonId,
        float $baseSalary,
        ?int $paidBy,
    ): WorkerPayment {
        $now = Carbon::now();
        $periodYear = (int) $now->year;
        $periodMonth = (int) $now->month;

        $payment = $this->findByUniquePeriod($plantId, $workerPersonId, $periodYear, $periodMonth);

        if ($payment) {
            $payment->update([
                'base_salary' => $baseSalary,
                'net_amount' => round($baseSalary, 2),
                'status' => 'paid',
                'paid_at' => now(),
                'paid_by' => $paidBy,
            ]);
            return $payment;
        }

        return WorkerPayment::create([
            'plant_id' => $plantId,
            'worker_person_id' => $workerPersonId,
            'period_year' => $periodYear,
            'period_month' => $periodMonth,
            'base_salary' => $baseSalary,
            'bonuses' => 0,
            'deductions' => 0,
            'net_amount' => round($baseSalary, 2),
            'status' => 'paid',
            'paid_at' => now(),
            'paid_by' => $paidBy,
            'created_by' => $paidBy,
        ]);
    }
}
