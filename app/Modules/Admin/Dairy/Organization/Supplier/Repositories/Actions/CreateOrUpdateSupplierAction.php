<?php

namespace App\Modules\Admin\Dairy\Organization\Supplier\Repositories\Actions;

use App\Models\Dairy\Supplier;

class CreateOrUpdateSupplierAction
{
    public function execute(array $data): Supplier
    {
        if (isset($data['id'])) {
            $supplier = Supplier::findOrFail($data['id']);
            $supplier->update($data);
            return $supplier;
        }

        return Supplier::create($data);
    }
}

