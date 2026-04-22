<?php

namespace App\Modules\PlantPanel\Catalog\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Dairy\PlantProduct;
use App\Models\Dairy\Product;
use App\Models\Dairy\ProductPresentation;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlantCatalogProductService
{
    public function __construct(
        private AuthService $authService,
    ) {}

    public function plantProducts(): array
    {
        $plantId = $this->authService->getMyPlantId();

        return PlantProduct::with([
                'product.productType',
                'presentations.unitMeasure',
            ])
            ->where('plant_id', $plantId)
            ->where('is_active', true)
            ->orderBy('id')
            ->get()
            ->map(fn (PlantProduct $pp) => [
                'id'          => $pp->id,
                'productId'   => $pp->product->id,
                'productName' => $pp->product->name,
                'productType' => $pp->product->productType?->name,
                'isActive'    => $pp->is_active,
                'presentations' => $pp->presentations->map(fn ($pres) => [
                    'id'          => $pres->id,
                    'name'        => $pres->name,
                    'sku'         => $pres->sku,
                    'content'     => $pres->content,
                    'isActive'    => $pres->is_active,
                    'unitMeasure' => $pres->unitMeasure
                        ? ['id' => $pres->unitMeasure->id, 'abbreviation' => $pres->unitMeasure->abbreviation]
                        : null,
                ])->values()->toArray(),
            ])
            ->values()
            ->toArray();
    }

    public function savePresentation(array $data): ProductPresentation
    {
        $plantId = $this->authService->getMyPlantId();

        $plantProduct = PlantProduct::where('id', $data['plant_product_id'])
            ->where('plant_id', $plantId)
            ->firstOrFail();

        return DB::transaction(function () use ($data, $plantProduct) {
            $presentation = ProductPresentation::create([
                'plant_product_id' => $plantProduct->id,
                'name'             => $data['name'],
                'unit_measure_id'  => $data['unit_measure_id'] ?? null,
                'content'          => $data['content'],
                'is_active'        => true,
                'sku'              => 'TMP-' . uniqid(),
            ]);

            $presentation->update([
                'sku'     => 'SKU-' . str_pad($presentation->id, 6, '0', STR_PAD_LEFT),
                'barcode' => '200' . str_pad($presentation->id, 10, '0', STR_PAD_LEFT),
            ]);

            return $presentation->fresh();
        });
    }

    public function availableProducts(): array
    {
        $plantId = $this->authService->getMyPlantId();

        $assignedProductIds = PlantProduct::where('plant_id', $plantId)
            ->pluck('product_id')
            ->toArray();

        return Product::where('is_active', true)
            ->when(!empty($assignedProductIds), fn ($q) => $q->whereNotIn('id', $assignedProductIds))
            ->orderBy('name')
            ->get(['id as value', 'name as title'])
            ->toArray();
    }

    public function addProduct(int $productId): PlantProduct
    {
        $plantId = $this->authService->getMyPlantId();

        $exists = PlantProduct::where('plant_id', $plantId)
            ->where('product_id', $productId)
            ->exists();

        if ($exists) {
            throw new ApiException('Este producto ya está asignado a la planta', 422);
        }

        return PlantProduct::create([
            'plant_id'   => $plantId,
            'product_id' => $productId,
        ]);
    }

    public function createAndAdd(array $data): PlantProduct
    {
        $plantId = $this->authService->getMyPlantId();

        $product = Product::create([
            'name'            => $data['name'],
            'description'     => $data['description'] ?? null,
            'product_type_id' => $data['product_type_id'] ?? null,
            'created_by'      => Auth::id(),
            'is_active'       => true,
        ]);

        return PlantProduct::create([
            'plant_id'   => $plantId,
            'product_id' => $product->id,
        ]);
    }
}
