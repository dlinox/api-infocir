<?php

namespace App\Modules\Admin\Setting\Http\Resources\UnitMeasure;

use Illuminate\Http\Resources\Json\JsonResource;

class UnitMeasureSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->id,
        ];
    }
}
