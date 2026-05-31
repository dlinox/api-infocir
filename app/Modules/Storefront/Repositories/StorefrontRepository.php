<?php

namespace App\Modules\Storefront\Repositories;

use App\Models\Dairy\Plant;
use App\Models\Dairy\PlantProduct;
use App\Models\Dairy\ProductType;
use App\Models\Dairy\StockMovement;
use App\Models\Dairy\Supplier;
use Illuminate\Support\Collection;

class StorefrontRepository
{
    public function categories()
    {
        return ProductType::where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function products(array $filters)
    {
        $query = PlantProduct::query()
            ->where('is_active', true)
            ->whereHas('presentations', fn ($q) => $q->where('is_active', true))
            ->whereHas('product', fn ($q) => $q->where('is_active', true))
            ->with([
                'product.productType',
                'plant.cityRelation',
                'presentations' => fn ($q) => $q->where('is_active', true)->with(['unitMeasure', 'prices']),
            ]);

        if (!empty($filters['category'])) {
            $query->whereHas('product', fn ($q) => $q->where('product_type_id', $filters['category']));
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('product', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $plantProducts = $query->orderByDesc('id')->get();
        $this->attachStock($plantProducts);
        return $plantProducts;
    }

    public function productById(int $plantProductId)
    {
        $plantProduct = PlantProduct::where('is_active', true)
            ->whereHas('presentations', fn ($q) => $q->where('is_active', true))
            ->with([
                'product.productType',
                'plant.cityRelation',
                'presentations' => fn ($q) => $q->where('is_active', true)->with(['unitMeasure', 'prices']),
            ])
            ->findOrFail($plantProductId);

        $this->attachStock(collect([$plantProduct]));
        return $plantProduct;
    }

    /**
     * Adjunta el stock disponible (available_stock) a cada presentación de los plant_products.
     */
    private function attachStock(Collection $plantProducts): void
    {
        $presentationIds = $plantProducts
            ->flatMap(fn ($pp) => $pp->presentations->pluck('id'))
            ->unique()
            ->values()
            ->all();

        $stockMap = StockMovement::availableFor($presentationIds);

        foreach ($plantProducts as $plantProduct) {
            foreach ($plantProduct->presentations as $presentation) {
                $presentation->setAttribute('available_stock', $stockMap[$presentation->id] ?? 0);
            }
        }
    }

    public function plants()
    {
        return Plant::where('is_active', true)
            ->with(['cityRelation', 'plantProducts.product.productType'])
            ->orderBy('name')
            ->get();
    }

    public function plantBySlug(string $slug)
    {
        return Plant::where('is_active', true)
            ->where('slug', $slug)
            ->with(['cityRelation', 'plantProducts.product.productType'])
            ->firstOrFail();
    }

    public function suppliers()
    {
        return Supplier::where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
