<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Resources\PlantProduct;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlantProductItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'product' => [
                'id'   => $this->product_id,
                'name' => $this->whenLoaded('product', fn() => $this->product->name),
                'productType' => [
                    'name' => $this->whenLoaded('product', fn() => $this->product->productType?->name),
                ],
            ],
            'plant' => [
                'id'   => $this->plant_id,
                'name' => $this->whenLoaded('plant', fn() => $this->plant->name),
            ],
            'presentationsCount' => $this->presentations_count ?? 0,
            'isActive'           => $this->is_active,
        ];
    }
}
