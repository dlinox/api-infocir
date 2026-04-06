<?php

namespace App\Modules\Admin\Dairy\Catalog\Product\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'productTypeId' => $this->product_type_id,
            'createdBy' => $this->created_by,
            'isActive' => $this->is_active,
            'plantsCount' => $this->plant_products_count ?? 0,
            'presentationsCount' => $this->presentations_count ?? 0,
        ];
    }
}
