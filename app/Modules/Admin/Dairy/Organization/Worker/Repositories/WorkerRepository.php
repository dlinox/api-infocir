<?php

namespace App\Modules\Admin\Dairy\Organization\Worker\Repositories;

use App\Models\Behavior\BehaviorProfile;
use App\Models\Core\Profile;
use App\Models\Dairy\Worker;
use Illuminate\Support\Facades\DB;

class WorkerRepository
{
    public function dataTable($request)
    {
        $query = Worker::query()
            ->select(
                'dairy_plant_workers.*',
                'core_persons.document_type as person_document_type',
                'core_persons.document_number as person_document_number',
                'core_persons.name as person_name',
                'core_persons.paternal_surname as person_paternal_surname',
                'core_persons.maternal_surname as person_maternal_surname',
                'core_persons.cellphone as person_cellphone',
                'core_persons.email as person_email',
            )
            ->join('core_persons', 'core_persons.id', '=', 'dairy_plant_workers.person_id')
            ->with(['plant', 'position', 'instructionDegree', 'profession']);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('dairy_plant_workers.person_id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findByPersonId(int $personId): Worker
    {
        return Worker::where('person_id', $personId)
            ->with(['person', 'plant', 'position'])
            ->firstOrFail();
    }

    public function delete(int $personId): void
    {
        try {
            DB::beginTransaction();

            $worker = Worker::where('person_id', $personId)->firstOrFail();

            $coreProfile = Profile::where('person_id', $personId)
                ->where('profileable_type', 'dairy_plant_workers')
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
