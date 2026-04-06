<?php

namespace App\Modules\Admin\Dairy\Catalog\Product\Repositories;

use App\Models\Dairy\Product;

class ProductRepository
{
    public function dataTable($request)
    {
        $query = Product::query()
            ->with(['productType', 'createdByUser']);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findById(string $id)
    {
        return Product::withCount(['plantProducts', 'presentations'])->findOrFail($id);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $product = Product::findOrFail($data['id']);
            $product->update($data);
            return $product;
        }

        return Product::create($data);
    }

    public function delete(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return $product;
    }

    public function getSelectItems()
    {
        return Product::where('is_active', true)
            ->orderBy('name')
            ->get(['id as value', 'name as title']);
    }
}
