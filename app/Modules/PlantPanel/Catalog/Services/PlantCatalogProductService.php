<?php

namespace App\Modules\PlantPanel\Catalog\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Dairy\PlantProduct;
use App\Models\Dairy\ProductFormula;
use App\Models\Dairy\Product;
use App\Models\Dairy\ProductPrice;
use App\Models\Dairy\ProductPresentation;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

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
                'product.galleries.file',
                'product.unitMeasure',
            ])
            ->withCount('presentations')
            ->where('plant_id', $plantId)
            ->where('is_active', true)
            ->orderBy('id')
            ->get()
            ->map(fn (PlantProduct $pp) => $this->mapProductSummary($pp))
            ->values()
            ->toArray();
    }

    public function plantProductsWithPresentations(): array
    {
        $plantId = $this->authService->getMyPlantId();

        return PlantProduct::with([
                'product.productType',
                'product.galleries.file',
                'product.unitMeasure',
                'presentations.unitMeasure',
            ])
            ->where('plant_id', $plantId)
            ->where('is_active', true)
            ->orderBy('id')
            ->get()
            ->map(fn (PlantProduct $pp) => array_merge(
                $this->mapProductSummary($pp),
                [
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
                ],
            ))
            ->values()
            ->toArray();
    }

    public function plantProduct(int $plantProductId): array
    {
        $plantId = $this->authService->getMyPlantId();

        $pp = PlantProduct::with([
                'product.productType',
                'product.galleries.file',
                'product.unitMeasure',
                'presentations.unitMeasure',
            ])
            ->where('plant_id', $plantId)
            ->where('id', $plantProductId)
            ->firstOrFail();

        return array_merge(
            $this->mapProductSummary($pp),
            [
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
            ],
        );
    }

    private function mapProductSummary(PlantProduct $pp): array
    {
        return [
            'id'                 => $pp->id,
            'productId'          => $pp->product->id,
            'productName'        => $pp->product->name,
            'productType'        => $pp->product->productType?->name,
            'isActive'           => $pp->is_active,
            'containsMilk'       => $pp->product->contains_milk,
            'presentationsCount' => (int) ($pp->presentations_count ?? $pp->presentations?->count() ?? 0),
            'galleries' => $pp->product->galleries
                ->where('is_active', true)
                ->filter(fn ($g) => $g->file !== null)
                ->map(fn ($g) => [
                    'id'      => $g->id,
                    'url'     => $g->file->url,
                    'caption' => $g->caption,
                ])
                ->values()
                ->toArray(),
            'unitMeasureId'           => $pp->product->unit_measure_id,
            'unitMeasureName'         => $pp->product->unitMeasure?->name,
            'unitMeasureAbbreviation' => $pp->product->unitMeasure?->abbreviation,
        ];
    }

    public function savePresentation(array $data): ProductPresentation
    {
        $plantId = $this->authService->getMyPlantId();

        $plantProduct = PlantProduct::where('id', $data['plant_product_id'])
            ->where('plant_id', $plantId)
            ->firstOrFail();
        try {
            DB::beginTransaction();

            $presentation = $this->savePresentationModel($data, $plantProduct->id);
            $this->saveFormulaItems($presentation->id, $data['formula_items'] ?? null);
            $this->savePresentationPrice($presentation->id, $data);

            DB::commit();
            return $presentation->fresh();
        } catch (Throwable $exception) {
            DB::rollBack();

            if ($exception instanceof ApiException) {
                throw $exception;
            }

            throw new ApiException('No se pudo guardar la presentación completa.', 500);
        }
    }

    private function savePresentationModel(array $data, int $plantProductId): ProductPresentation
    {
        if (!empty($data['id'])) {
            $presentation = ProductPresentation::where('id', $data['id'])
                ->where('plant_product_id', $plantProductId)
                ->firstOrFail();

            $presentation->update([
                'name'            => $data['name'],
                'unit_measure_id' => $data['unit_measure_id'] ?? null,
                'content'         => $data['content'],
            ]);

            return $presentation;
        }

        $presentation = ProductPresentation::create([
            'plant_product_id' => $plantProductId,
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

        return $presentation;
    }

    private function saveFormulaItems(int $presentationId, ?array $formulaItems): void
    {
        if ($formulaItems === null) {
            return;
        }

        $currentVersion = ProductFormula::where('presentation_id', $presentationId)
            ->where('is_current', true)
            ->max('version');

        if ($currentVersion !== null) {
            ProductFormula::where('presentation_id', $presentationId)
                ->where('version', $currentVersion)
                ->update(['is_current' => false]);
        }

        if (empty($formulaItems)) {
            return;
        }

        $newVersion = $currentVersion ? $currentVersion + 1 : 1;

        foreach ($formulaItems as $item) {
            ProductFormula::create([
                'presentation_id' => $presentationId,
                'supply_id'       => $item['supply_id'],
                'unit_measure_id' => $item['unit_measure_id'] ?? null,
                'quantity'        => $item['quantity'],
                'unit_price'      => $item['unit_price'],
                'version'         => $newVersion,
                'is_current'      => true,
            ]);
        }
    }

    private function savePresentationPrice(int $presentationId, array $data): void
    {
        if (!isset($data['price']) || $data['price'] === null) {
            return;
        }

        $effectiveFrom = $data['effective_from'] ?? now()->toDateString();

        ProductPrice::where('presentation_id', $presentationId)
            ->whereNull('effective_until')
            ->update(['effective_until' => $effectiveFrom]);

        $cost = $data['cost'] ?? null;
        if ($cost === null) {
            $formulaCost = ProductFormula::where('presentation_id', $presentationId)
                ->where('is_current', true)
                ->select(DB::raw('SUM(quantity * unit_price) as total'))
                ->value('total');
            $cost = $formulaCost ? round((float) $formulaCost, 2) : null;
        }

        ProductPrice::create([
            'presentation_id' => $presentationId,
            'price'           => $data['price'],
            'cost'            => $cost,
            'effective_from'  => $effectiveFrom,
        ]);
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
