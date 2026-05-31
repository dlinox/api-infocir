<?php

namespace App\Modules\Admin\Setting\Http\Resources\Supply;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplySelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title'                  => $this->name,
            'value'                  => $this->id,
            'unitMeasureId'          => $this->unitMeasure?->id,
            'unitMeasureAbbreviation'=> $this->unitMeasure?->abbreviation,
            'unitMeasureName'        => $this->unitMeasure?->name,
            'unitMeasureBaseUnitId'  => $this->unitMeasure?->base_unit_id,
        ];
    }
}
