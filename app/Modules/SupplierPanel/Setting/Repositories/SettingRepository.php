<?php

namespace App\Modules\SupplierPanel\Setting\Repositories;

use App\Models\Dairy\Supplier;

class SettingRepository
{
    public function findById(int $id): ?Supplier
    {
        return Supplier::find($id);
    }

    public function update(Supplier $supplier, array $data): Supplier
    {
        $supplier->fill($data);
        $supplier->total_cows = (int) ($data['cows_in_production'] ?? 0) + (int) ($data['dry_cows'] ?? 0);
        $supplier->save();
        return $supplier;
    }
}
