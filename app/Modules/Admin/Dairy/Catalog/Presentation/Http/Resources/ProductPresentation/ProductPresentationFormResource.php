<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Resources\ProductPresentation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPresentationFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'plantProductId' => $this->plant_product_id,
            'name'           => $this->name,
            'unitMeasureId'  => $this->unit_measure_id,
            'content'        => $this->content,
            'isActive'       => $this->is_active,
            'sku'            => $this->sku,
            'barcode'        => $this->barcode,
        ];
    }
}
