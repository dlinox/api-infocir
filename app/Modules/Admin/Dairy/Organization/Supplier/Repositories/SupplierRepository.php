<?php

namespace App\Modules\Admin\Dairy\Organization\Supplier\Repositories;

use App\Models\Behavior\BehaviorProfile;
use App\Models\Core\Profile;
use App\Models\Dairy\Supplier;
use Illuminate\Support\Facades\DB;

class SupplierRepository
{
    public function dataTable($request)
    {
        $query = Supplier::query()
            ->select(
                'dairy_suppliers.*',
                'core_persons.document_type as person_document_type',
                'core_persons.document_number as person_document_number',
                'core_persons.name as person_name',
                'core_persons.paternal_surname as person_paternal_surname',
                'core_persons.maternal_surname as person_maternal_surname',
                'core_persons.cellphone as person_cellphone',
                'core_persons.email as person_email',
            )
            ->join('core_persons', 'core_persons.id', '=', 'dairy_suppliers.person_id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('dairy_suppliers.person_id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findByPersonId(int $personId): Supplier
    {
        return Supplier::where('person_id', $personId)
            ->with(['person'])
            ->firstOrFail();
    }

    public function delete(int $personId): void
    {
        try {
            DB::beginTransaction();

            $supplier = Supplier::where('person_id', $personId)->firstOrFail();

            $coreProfile = Profile::where('person_id', $personId)
                ->where('profileable_type', 'dairy_suppliers')
                ->first();

            if ($coreProfile) {
                BehaviorProfile::where('core_profile_id', $coreProfile->id)->delete();
                $coreProfile->delete();
            }

            $supplier->delete();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
