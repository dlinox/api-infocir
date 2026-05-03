<?php

namespace App\Modules\Admin\Dairy\Organization\Plant\Repositories;

use App\Models\Dairy\Plant;

class PlantSupplierRepository
{
    public function getAssignedSupplierIds(int $plantId): array
    {
        $plant = Plant::findOrFail($plantId);
        return $plant->suppliers()->pluck('dairy_suppliers.id')->toArray();
    }

    public function syncSuppliers(int $plantId, array $supplierIds): void
    {
        $plant = Plant::findOrFail($plantId);
        $plant->suppliers()->sync($supplierIds);
    }
}
