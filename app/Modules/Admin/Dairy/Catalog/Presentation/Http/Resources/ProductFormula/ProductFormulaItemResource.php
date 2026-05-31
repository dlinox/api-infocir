<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Resources\ProductFormula;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductFormulaItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $unit = $this->unitMeasure ?? $this->supply?->unitMeasure;

        return [
            'id'                     => $this->id,
            'supplyId'               => $this->supply_id,
            'supplyName'             => $this->supply?->name,
            'unitMeasureId'          => $unit?->id,
            'unitMeasureName'        => $unit?->name,
            'unitMeasureAbbreviation'=> $unit?->abbreviation,
            'quantity'               => (float) $this->quantity,
            'unitPrice'              => (float) $this->unit_price,
            'subtotal'               => $this->subtotal,
            'percentage'             => $this->percentage,
            'change'                 => $this->change,
            'version'                => $this->version,
            'isCurrent'              => $this->is_current,
        ];
    }
}
