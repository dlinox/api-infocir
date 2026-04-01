<?php

namespace App\Modules\Admin\Dairy\Supplier\Repositories;

use App\Models\Behavior\BehaviorProfile;
use App\Models\Core\Profile;
use App\Models\Dairy\Supplier;
use Illuminate\Support\Facades\DB;

class SupplierRepository
{
    public function dataTable($request)
    {
        $query = Supplier::query()
            ->select('dairy_suppliers.*')
            ->join('core_persons', 'core_persons.id', '=', 'dairy_suppliers.person_id')
            ->with(['person']);

        if (!empty($request->filters['supplier_type'])) {
            $query->where('dairy_suppliers.supplier_type', $request->filters['supplier_type']);
        }

        if (!empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('core_persons.name', 'like', "%{$search}%")
                    ->orWhere('core_persons.paternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.maternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.document_number', 'like', "%{$search}%")
                    ->orWhere('dairy_suppliers.trade_name', 'like', "%{$search}%")
                    ->orWhere('dairy_suppliers.cellphone', 'like', "%{$search}%")
                    ->orWhere('dairy_suppliers.email', 'like', "%{$search}%");
            });
        }

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
