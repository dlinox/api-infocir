<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Repositories;

use App\Models\Dairy\ProductFormula;
use App\Models\Dairy\ProductPrice;
use Illuminate\Support\Facades\DB;

class ProductPriceRepository
{
    public function getByPresentation(int $presentationId): array
    {
        $prices = ProductPrice::where('presentation_id', $presentationId)
            ->with('creator')
            ->orderBy('effective_from', 'desc')
            ->get();

        $result = [];
        $previousPrice = null;

        $sorted = $prices->sortBy('effective_from')->values();

        foreach ($sorted as $item) {
            $variation = null;
            if ($previousPrice && $previousPrice > 0) {
                $diff = (((float) $item->price - $previousPrice) / $previousPrice) * 100;
                $variation = round($diff, 1);
            }

            $margin = null;
            if ((float) $item->price > 0) {
                $margin = round((((float) $item->price - (float) ($item->cost ?? 0)) / (float) $item->price) * 100, 1);
            }

            $result[] = [
                'item' => $item,
                'variation' => $variation,
                'margin' => $margin,
            ];

            $previousPrice = (float) $item->price;
        }

        return array_reverse($result);
    }

    public function save(array $data): ProductPrice
    {
        return DB::transaction(function () use ($data) {
            if (!isset($data['cost']) || $data['cost'] === null) {
                $currentFormulaCost = ProductFormula::where('presentation_id', $data['presentation_id'])
                    ->where('is_current', true)
                    ->select(DB::raw('SUM(quantity * unit_price) as total'))
                    ->value('total');

                $data['cost'] = $currentFormulaCost ? round((float) $currentFormulaCost, 2) : null;
            }

            ProductPrice::where('presentation_id', $data['presentation_id'])
                ->whereNull('effective_until')
                ->update(['effective_until' => $data['effective_from']]);

            return ProductPrice::create($data);
        });
    }

    public function delete(int $id): void
    {
        $price = ProductPrice::findOrFail($id);
        $price->delete();
    }
}
