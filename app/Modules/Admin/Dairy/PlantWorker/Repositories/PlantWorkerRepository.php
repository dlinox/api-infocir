<?php

namespace App\Modules\Admin\Dairy\PlantWorker\Repositories;

use App\Models\Behavior\BehaviorProfile;
use App\Models\Core\Profile;
use App\Models\Dairy\PlantWorker;
use Illuminate\Support\Facades\DB;

class PlantWorkerRepository
{
    public function dataTable($request)
    {
        $query = PlantWorker::query()
            ->select('dairy_plant_workers.*')
            ->join('core_persons', 'core_persons.id', '=', 'dairy_plant_workers.person_id')
            ->with(['person', 'plant', 'position', 'instructionDegree', 'profession']);

        if (!empty($request->filters['plant_id'])) {
            $query->where('dairy_plant_workers.plant_id', $request->filters['plant_id']);
        }

        if (!empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('core_persons.name', 'like', "%{$search}%")
                    ->orWhere('core_persons.paternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.maternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.document_number', 'like', "%{$search}%")
                    ->orWhere('core_persons.cellphone', 'like', "%{$search}%");
            });
        }

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('dairy_plant_workers.person_id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findByPersonId(int $personId): PlantWorker
    {
        return PlantWorker::where('person_id', $personId)
            ->with(['person', 'plant', 'position'])
            ->firstOrFail();
    }

    public function delete(int $personId): void
    {
        try {
            DB::beginTransaction();

            $worker = PlantWorker::where('person_id', $personId)->firstOrFail();

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
