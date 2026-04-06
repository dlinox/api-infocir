<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Resources\ProductFormula;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductFormulaItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'supplyId' => $this->supply_id,
            'supplyName' => $this->supply?->name,
            'unitMeasureName' => $this->supply?->unitMeasure?->name,
            'quantity' => $this->quantity,
            'unitPrice' => $this->unit_price,
            'subtotal' => $this->subtotal,
            'percentage' => $this->percentage,
            'change' => $this->change,
            'version' => $this->version,
            'isCurrent' => $this->is_current,
        ];
    }
}
