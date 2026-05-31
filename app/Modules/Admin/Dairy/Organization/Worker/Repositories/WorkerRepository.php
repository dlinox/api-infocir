<?php

namespace App\Modules\Admin\Dairy\Organization\Worker\Repositories;

use App\Models\Behavior\BehaviorProfile;
use App\Models\Core\Profile;
use App\Models\Dairy\Worker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WorkerRepository
{
    public function dataTable($request)
    {
        $query = Worker::query()
            ->select(
                'dairy_workers.*',
                'core_persons.document_type as person_document_type',
                'core_persons.document_number as person_document_number',
                'core_persons.name as person_name',
                'core_persons.paternal_surname as person_paternal_surname',
                'core_persons.maternal_surname as person_maternal_surname',
                'core_persons.cellphone as person_cellphone',
                'core_persons.email as person_email',
            )
            ->join('core_persons', 'core_persons.id', '=', 'dairy_workers.person_id')
            ->with(['entity.entityable', 'position', 'instructionDegree', 'profession']);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('dairy_workers.person_id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function dataTableForEntity($request, int $entityId)
    {
        $now = Carbon::now();

        $query = Worker::query()
            ->select(
                'dairy_workers.*',
                'core_persons.document_type as person_document_type',
                'core_persons.document_number as person_document_number',
                'core_persons.name as person_name',
                'core_persons.paternal_surname as person_paternal_surname',
                'core_persons.maternal_surname as person_maternal_surname',
                'core_persons.cellphone as person_cellphone',
                'core_persons.email as person_email',
                'current_payment.id as current_payment_id',
                'current_payment.status as current_payment_status',
                'current_payment.net_amount as current_payment_net_amount',
                'current_payment.paid_at as current_payment_paid_at',
            )
            ->join('core_persons', 'core_persons.id', '=', 'dairy_workers.person_id')
            ->join('core_entities', 'core_entities.id', '=', 'dairy_workers.entity_id')
            ->leftJoin('dairy_worker_payments as current_payment', function ($join) use ($now) {
                $join->on('current_payment.worker_person_id', '=', 'dairy_workers.person_id')
                    ->on('current_payment.plant_id', '=', 'core_entities.entityable_id')
                    ->where('core_entities.entityable_type', '=', 'dairy_plants')
                    ->where('current_payment.period_year', '=', (int) $now->year)
                    ->where('current_payment.period_month', '=', (int) $now->month);
            })
            ->with(['position', 'instructionDegree', 'profession'])
            ->where('dairy_workers.entity_id', $entityId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('dairy_workers.person_id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findByPersonId(int $personId): Worker
    {
        $worker = Worker::where('person_id', $personId)
            ->with(['person', 'entity', 'position'])
            ->firstOrFail();

        $workerRoleId = DB::table('behavior_roles')
            ->where('name', Worker::ROLE_NAME)
            ->value('id');

        $roleId = DB::table('behavior_profiles')
            ->join('core_profiles', 'core_profiles.id', '=', 'behavior_profiles.core_profile_id')
            ->where('core_profiles.person_id', $personId)
            ->where('core_profiles.profileable_type', 'dairy_workers')
            ->where('behavior_profiles.role_id', '!=', $workerRoleId)
            ->value('behavior_profiles.role_id');

        $worker->setAttribute('behavior_role_id', $roleId);

        return $worker;
    }

    public function delete(int $personId): void
    {
        try {
            DB::beginTransaction();

            $worker = Worker::where('person_id', $personId)->firstOrFail();

            $coreProfile = Profile::where('person_id', $personId)
                ->where('profileable_type', 'dairy_workers')
                ->first();

            if ($coreProfile) {
                BehaviorProfile::where('core_profile_id', $coreProfile->id)->delete();
                $coreProfile->delete();
            }

            $worker->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}

