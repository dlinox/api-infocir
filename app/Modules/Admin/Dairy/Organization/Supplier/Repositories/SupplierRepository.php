<?php

namespace App\Modules\Admin\Dairy\Organization\Supplier\Repositories;

use App\Models\Dairy\Supplier;

class SupplierRepository
{
    public function dataTable($request)
    {
        $query = Supplier::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findById(int $id): Supplier
    {
        return Supplier::findOrFail($id);
    }

    public function delete(int $id): void
    {
        Supplier::findOrFail($id)->delete();
    }
}

