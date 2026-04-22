<?php

namespace App\Modules\PlantPanel\ProductionBatch\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionBatchDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'batchCode'           => $this->batch_code,
            'productionDate'      => optional($this->production_date)->format('Y-m-d'),
            'quantityLitersUsed'  => (float) $this->quantity_liters_used,
            'quantityKg'          => (float) $this->quantity_kg,
            'yieldRatio'          => $this->yield_ratio !== null ? (float) $this->yield_ratio : null,
            'status'              => $this->status,
            'maturationStartDate' => optional($this->maturation_start_date)->format('Y-m-d'),
            'maturationEndDate'   => optional($this->maturation_end_date)->format('Y-m-d'),
            'presentation'        => $this->presentation_id_alias ? [
                'id'   => (int) $this->presentation_id_alias,
                'name' => $this->presentation_name,
                'sku'  => $this->presentation_sku,
            ] : null,
        ];
    }
}
