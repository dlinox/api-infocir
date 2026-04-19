<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Resources\ProductPresentation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPresentationDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'sku'            => $this->sku,
            'name'           => $this->name,
            'content'        => (float) $this->content,
            'barcode'        => $this->barcode,
            'isActive'       => $this->is_active,
            'plantProductId' => $this->plant_product_id,
            'productName'    => $this->whenLoaded('plantProduct', fn() => $this->plantProduct->product?->name),
            'unitMeasure'    => $this->whenLoaded('unitMeasure', fn() => [
                'id'           => $this->unitMeasure->id,
                'name'         => $this->unitMeasure->name,
                'abbreviation' => $this->unitMeasure->abbreviation,
            ]),
        ];
    }
}
