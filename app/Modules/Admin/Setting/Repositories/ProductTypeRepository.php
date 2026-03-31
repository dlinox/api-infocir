<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Dairy\ProductType;

class ProductTypeRepository
{
    public function dataTable($request)
    {
        $query = ProductType::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $productType = ProductType::findOrFail($data['id']);
            $productType->update($data);
            return $productType;
        }

        return ProductType::create($data);
    }

    public function delete(string $id)
    {
        $productType = ProductType::findOrFail($id);
        $productType->delete();
        return $productType;
    }

    public function getSelectItems()
    {
        return ProductType::where('is_active', true)->orderBy('name')->get();
    }
}
