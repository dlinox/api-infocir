<?php

namespace App\Modules\Admin\Dairy\Catalog\Product\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->id,
        ];
    }
}
