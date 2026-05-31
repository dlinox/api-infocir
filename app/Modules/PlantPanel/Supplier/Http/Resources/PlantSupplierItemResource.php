<?php

namespace App\Modules\PlantPanel\Supplier\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlantSupplierItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'pivotId'        => $this->pivot_id,
            'supplierId'     => $this->supplier_id,
            'name'           => $this->name,
            'tradeName'      => $this->trade_name,
            'documentType'   => $this->document_type,
            'documentNumber' => $this->document_number,
            'cellphone'      => $this->cellphone,
            'email'          => $this->email,
            'address'        => $this->address,
            'community'      => $this->community,
            'latitude'       => $this->latitude ? (float) $this->latitude : null,
            'longitude'      => $this->longitude ? (float) $this->longitude : null,
            'totalCows'      => (int) ($this->total_cows ?? 0),
            'cowsInProduction' => (int) ($this->cows_in_production ?? 0),
            'isActive'       => (bool) $this->is_active,
            'pricePerLiter'  => $this->price_per_liter !== null ? (float) $this->price_per_liter : null,
            'avgDailyLiters' => (float) ($this->avg_liters_per_day ?? 0),
        ];
    }
}
