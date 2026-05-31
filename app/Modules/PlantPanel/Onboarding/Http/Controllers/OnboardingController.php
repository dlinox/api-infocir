<?php

namespace App\Modules\PlantPanel\Onboarding\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Common\Http\Responses\ApiResponse;
use App\Models\Dairy\AssetCatalog;
use App\Models\Dairy\FixedAsset;
use App\Models\Dairy\InvestmentCategory;
use App\Models\Dairy\Plant;
use App\Models\Dairy\PlantProduct;
use App\Models\Dairy\Product;
use App\Models\Dairy\ProductType;
use App\Modules\Auth\Services\AuthService;

class OnboardingController
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function getFixedAssetsCatalog(): JsonResponse
    {
        $categories = InvestmentCategory::query()
            ->where('group', 'fixed_asset')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $items = AssetCatalog::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->groupBy('investment_category_id');

        $payload = $categories->map(fn ($cat) => [
            'id'   => $cat->id,
            'name' => $cat->name,
            'hint' => $cat->hint,
            'defaultUsefulLifeYears' => $cat->default_useful_life_years,
            'items' => ($items->get($cat->id, collect()))->map(fn ($item) => [
                'id'    => $item->id,
                'name'  => $item->name,
                'brand' => $item->brand,
                'model' => $item->model,
                'usefulLifeYears'    => $item->useful_life_years ?? $cat->default_useful_life_years,
                'depreciationMethod' => $item->depreciation_method ?? 'straight_line',
            ])->values(),
        ])->filter(fn ($cat) => $cat['items']->isNotEmpty())->values();

        return ApiResponse::success($payload, '');
    }

    public function getProductsCatalog(): JsonResponse
    {
        $types = ProductType::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->groupBy('product_type_id');

        $payload = $types->map(fn ($type) => [
            'id'   => $type->id,
            'name' => $type->name,
            'products' => ($products->get($type->id, collect()))->map(fn ($p) => [
                'id'          => $p->id,
                'name'        => $p->name,
                'description' => $p->description,
                'containsMilk' => (bool) $p->contains_milk,
            ])->values(),
        ])->filter(fn ($type) => $type['products']->isNotEmpty())->values();

        return ApiResponse::success($payload, '');
    }

    public function complete(Request $request): JsonResponse
    {
        $data = $request->validate([
            'fixed_assets'                     => 'array',
            'fixed_assets.*.asset_catalog_id'  => 'required|integer|exists:dairy_fixed_asset_catalog,id',
            'fixed_assets.*.purchase_cost'     => 'required|numeric|min:0',
            'product_ids'                      => 'array',
            'product_ids.*'                    => 'integer|exists:dairy_products,id',
        ]);

        $plantId = $this->authService->getMyPlantId();
        $plant = Plant::findOrFail($plantId);

        DB::transaction(function () use ($plant, $data) {
            $entityId = $plant->entity->id;

            foreach ($data['fixed_assets'] ?? [] as $row) {
                $catalog = AssetCatalog::with('investmentCategory')->findOrFail($row['asset_catalog_id']);
                FixedAsset::create([
                    'entity_id'              => $entityId,
                    'investment_category_id' => $catalog->investment_category_id,
                    'asset_catalog_id'       => $catalog->id,
                    'name'                   => $catalog->name,
                    'purchase_date'          => now()->toDateString(),
                    'purchase_cost'          => $row['purchase_cost'],
                    'quantity'               => 1,
                    'useful_life_years'      => $catalog->useful_life_years
                        ?? $catalog->investmentCategory?->default_useful_life_years,
                    'depreciation_method'    => $catalog->depreciation_method ?? 'straight_line',
                    'status'                 => 'active',
                ]);
            }

            foreach ($data['product_ids'] ?? [] as $productId) {
                PlantProduct::firstOrCreate(
                    ['plant_id' => $plant->id, 'product_id' => $productId],
                    ['is_active' => true],
                );
            }

            $plant->update(['onboarding_completed_at' => now()]);
        });

        return ApiResponse::success(null, 'Onboarding completado correctamente.');
    }
}
