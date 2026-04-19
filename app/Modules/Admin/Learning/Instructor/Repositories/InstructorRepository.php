<?php

namespace App\Modules\Admin\Learning\Instructor\Repositories;

use App\Models\Behavior\BehaviorProfile;
use App\Models\Core\Profile;
use App\Models\Learning\Instructor;
use Illuminate\Support\Facades\DB;

class InstructorRepository
{
    public function dataTable($request)
    {
        $query = Instructor::query()
            ->select(
                'learning_instructors.*',
                'core_persons.document_type as person_document_type',
                'core_persons.document_number as person_document_number',
                'core_persons.name as person_name',
                'core_persons.paternal_surname as person_paternal_surname',
                'core_persons.maternal_surname as person_maternal_surname',
                'core_persons.cellphone as person_cellphone',
                'core_persons.email as person_email',
            )
            ->join('core_persons', 'core_persons.id', '=', 'learning_instructors.person_id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('learning_instructors.person_id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findByPersonId(int $personId): Instructor
    {
        return Instructor::where('person_id', $personId)
            ->with('person')
            ->firstOrFail();
    }

    public function delete(int $personId): void
    {
        try {
            DB::beginTransaction();

            $instructor = Instructor::where('person_id', $personId)->firstOrFail();

            $coreProfile = Profile::where('profileable_type', 'learning_instructors')
                ->where('profileable_id', $instructor->id)
                ->first();

            if ($coreProfile) {
                BehaviorProfile::where('core_profile_id', $coreProfile->id)->delete();
                $coreProfile->delete();
            }

            $instructor->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getSelectItems()
    {
        return Instructor::query()
            ->select(
                'learning_instructors.id',
                'core_persons.name as person_name',
                'core_persons.paternal_surname as person_paternal_surname',
                'core_persons.maternal_surname as person_maternal_surname',
            )
            ->join('core_persons', 'core_persons.id', '=', 'learning_instructors.person_id')
            ->where('learning_instructors.is_active', true)
            ->orderBy('core_persons.paternal_surname')
            ->get();
    }
}
