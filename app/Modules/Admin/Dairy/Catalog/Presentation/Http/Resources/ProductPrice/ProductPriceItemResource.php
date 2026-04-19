<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Resources\ProductPrice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPriceItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $item = $this['item'];
        return [
            'id' => $item->id,
            'presentationId' => $item->presentation_id,
            'price' => (float) $item->price,
            'cost' => $item->cost !== null ? (float) $item->cost : null,
            'effectiveFrom' => $item->effective_from?->format('Y-m-d'),
            'effectiveUntil' => $item->effective_until?->format('Y-m-d'),
            'createdBy' => $item->creator?->name,
            'variation' => $this['variation'],
            'margin' => $this['margin'],
        ];
    }
}
