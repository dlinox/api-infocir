<?php

namespace App\Modules\SupplierPanel\MilkRegistration\Repositories;

use App\Models\Dairy\SupplierMilkRegistration;

class MilkRegistrationRepository
{
    public function dataTable($request, int $supplierId)
    {
        $query = SupplierMilkRegistration::query()
            ->where('supplier_id', $supplierId);

        if (empty($request->sortBy)) {
            $query->orderBy('registration_date', 'desc')
                  ->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findByIdForSupplier(int $id, int $supplierId): ?SupplierMilkRegistration
    {
        return SupplierMilkRegistration::where('id', $id)
            ->where('supplier_id', $supplierId)
            ->first();
    }

    public function createOrUpdate(array $data, int $supplierId): SupplierMilkRegistration
    {
        $data['supplier_id'] = $supplierId;

        if (!empty($data['id'])) {
            $record = SupplierMilkRegistration::where('id', $data['id'])
                ->where('supplier_id', $supplierId)
                ->firstOrFail();
            $record->update($data);
            return $record;
        }

        return SupplierMilkRegistration::create($data);
    }

    public function delete(int $id, int $supplierId): void
    {
        $record = SupplierMilkRegistration::where('id', $id)
            ->where('supplier_id', $supplierId)
            ->firstOrFail();
        $record->delete();
    }
}
