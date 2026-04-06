<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Resources\PlantProduct;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlantProductDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'isActive'           => $this->is_active,
            'product' => [
                'id'   => $this->product_id,
                'name' => $this->product_name,
                'productType' => [
                    'name' => $this->product_type_name,
                ],
            ],
            'plant' => [
                'id'   => $this->plant_id,
                'name' => $this->plant_name,
            ],
            'presentationsCount' => $this->presentations->count(),
        ];
    }
}
