<?php

namespace App\Modules\Admin\Setting\Http\Resources\Supply;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplyDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'unitMeasureId'    => $this->unit_measure_id,
            'unitMeasureName'  => $this->whenLoaded('unitMeasure', fn() => $this->unitMeasure?->name),
            'description'      => $this->description,
            'isActive'         => $this->is_active,
        ];
    }
}
