<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Repositories;

use App\Models\Dairy\PlantProduct;
use Illuminate\Validation\ValidationException;

class PlantProductRepository
{
    public function findById(int $id)
    {
        return PlantProduct::with(['plant', 'product.productType'])
            ->withCount('presentations')
            ->findOrFail($id);
    }

    public function dataTable($request)
    {
        $query = PlantProduct::query()
            ->select(
                'dairy_plant_products.id',
                'dairy_plant_products.is_active',

                'dairy_plant_products.product_id',
                'dairy_products.name as product_name',
                'dairy_product_types.name as product_type_name',

                'dairy_plant_products.plant_id',
                'dairy_plants.name as plant_name',
            )
            ->join('dairy_products', 'dairy_plant_products.product_id', '=', 'dairy_products.id')
            ->join('dairy_product_types', 'dairy_products.product_type_id', '=', 'dairy_product_types.id')
            ->join('dairy_plants', 'dairy_plant_products.plant_id', '=', 'dairy_plants.id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('dairy_plant_products.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function getByPlant(int $plantId)
    {
        return PlantProduct::select('id', 'product_id')
            ->where('plant_id', $plantId)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function list(?int $plantId = null)
    {
        $query = PlantProduct::with(['plant', 'product.productType'])
            ->withCount('presentations')
            ->orderBy('id', 'desc');

        if ($plantId) {
            $query->where('plant_id', $plantId);
        }

        return $query->get();
    }

    public function getSelectItems(int $plantId): array
    {
        return PlantProduct::where('plant_id', $plantId)
            ->where('dairy_plant_products.is_active', true)
            ->join('dairy_products', 'dairy_plant_products.product_id', '=', 'dairy_products.id')
            ->orderBy('dairy_products.name')
            ->get(['dairy_plant_products.id as value', 'dairy_products.name as title'])
            ->toArray();
    }

    public function sync(int $plantId, array $productIds)
    {
        $existing = PlantProduct::where('plant_id', $plantId)->withCount('presentations')->get();
        $existingProductIds = $existing->pluck('product_id')->toArray();

        $toAdd = array_diff($productIds, $existingProductIds);
        $toRemove = $existing->filter(fn($pp) => !in_array($pp->product_id, $productIds));

        $protected = $toRemove->filter(fn($pp) => $pp->presentations_count > 0);
        if ($protected->isNotEmpty()) {
            $names = $protected->map(fn($pp) => $pp->product->name)->implode(', ');
            throw ValidationException::withMessages([
                'product_ids' => ["No se pueden desasignar productos con presentaciones: {$names}. Elimine las presentaciones primero."],
            ]);
        }

        foreach ($toRemove as $pp) {
            $pp->delete();
        }

        foreach ($toAdd as $productId) {
            PlantProduct::create([
                'plant_id' => $plantId,
                'product_id' => $productId,
            ]);
        }

        return $this->getByPlant($plantId);
    }
}
