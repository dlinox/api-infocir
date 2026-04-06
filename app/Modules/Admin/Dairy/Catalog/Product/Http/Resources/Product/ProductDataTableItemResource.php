<?php

namespace App\Modules\Admin\Dairy\Catalog\Product\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'isActive'    => $this->is_active,
            'productType' => $this->productType ? [
                'id'   => $this->productType->id,
                'name' => $this->productType->name,
            ] : null,
            'createdBy'   => $this->createdByUser ? [
                'id'   => $this->createdByUser->id,
                'name' => $this->createdByUser->name,
            ] : null,
        ];
    }
}
