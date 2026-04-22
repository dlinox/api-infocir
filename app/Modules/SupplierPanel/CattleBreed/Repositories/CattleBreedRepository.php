<?php

namespace App\Modules\SupplierPanel\CattleBreed\Repositories;

use App\Models\Dairy\SupplierCattleBreed;

class CattleBreedRepository
{
    public function dataTable($request, int $supplierId)
    {
        $query = SupplierCattleBreed::query()
            ->where('supplier_id', $supplierId);

        if (empty($request->sortBy)) {
            $query->orderBy('breed_name', 'asc');
        }

        return $query->dataTable($request);
    }

    public function findByIdForSupplier(int $id, int $supplierId): ?SupplierCattleBreed
    {
        return SupplierCattleBreed::where('id', $id)
            ->where('supplier_id', $supplierId)
            ->first();
    }

    public function createOrUpdate(array $data, int $supplierId): SupplierCattleBreed
    {
        $data['supplier_id'] = $supplierId;

        if (!empty($data['id'])) {
            $record = SupplierCattleBreed::where('id', $data['id'])
                ->where('supplier_id', $supplierId)
                ->firstOrFail();
            $record->update($data);
            return $record;
        }

        return SupplierCattleBreed::create($data);
    }

    public function delete(int $id, int $supplierId): void
    {
        $record = SupplierCattleBreed::where('id', $id)
            ->where('supplier_id', $supplierId)
            ->firstOrFail();
        $record->delete();
    }
}
