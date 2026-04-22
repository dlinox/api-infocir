<?php

namespace App\Modules\PlantPanel\ProductionBatch\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionBatchFormResource extends JsonResource
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
            'presentationId'      => $this->presentation_id,
            'maturationStartDate' => optional($this->maturation_start_date)->format('Y-m-d'),
            'maturationEndDate'   => optional($this->maturation_end_date)->format('Y-m-d'),
            'observations'        => $this->observations,
            'suppliers'           => $this->suppliers->map(fn ($s) => [
                'supplierId'     => $s->id,
                'supplierName'   => $s->trade_name ?: $s->name,
                'quantityLiters' => (float) $s->pivot->quantity_liters,
            ])->values(),
        ];
    }
}
