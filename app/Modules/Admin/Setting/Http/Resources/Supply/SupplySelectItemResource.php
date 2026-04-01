<?php

namespace App\Modules\Admin\Setting\Http\Resources\Supply;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplySelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->id,
        ];
    }
}
