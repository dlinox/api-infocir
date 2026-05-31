<?php

namespace App\Modules\Admin\Setting\Http\Resources\UnitMeasure;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitMeasureDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'abbreviation'     => $this->abbreviation,
            'isActive'         => $this->is_active,
            'isSystem'         => $this->is_system,
            'baseUnitId'       => $this->base_unit_id,
            'baseUnitName'     => $this->baseUnit?->name,
            'conversionFactor' => $this->conversion_factor ? (float) $this->conversion_factor : null,
        ];
    }
}
