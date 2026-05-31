<?php

namespace App\Modules\Admin\Dairy\Organization\Supplier\Http\Resources\Supplier;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->trade_name ?: $this->name,
            'value' => $this->id,
        ];
    }
}
