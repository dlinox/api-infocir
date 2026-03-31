<?php

namespace App\Modules\Admin\Setting\Http\Resources\ProductType;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductTypeSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->id,
        ];
    }
}
