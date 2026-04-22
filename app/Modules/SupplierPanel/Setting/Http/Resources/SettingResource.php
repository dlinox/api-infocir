<?php

namespace App\Modules\SupplierPanel\Setting\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'supplierType'            => $this->supplier_type,
            'documentType'            => $this->document_type,
            'documentNumber'          => $this->document_number,
            'name'                    => $this->name,
            'tradeName'               => $this->trade_name,
            'cellphone'               => $this->cellphone,
            'email'                   => $this->email,
            'address'                 => $this->address,
            'city'                    => $this->city,
            'community'               => $this->community,
            'latitude'                => $this->latitude !== null ? (float) $this->latitude : null,
            'longitude'               => $this->longitude !== null ? (float) $this->longitude : null,
            'cowsInProduction'        => (int) $this->cows_in_production,
            'dryCows'                 => (int) $this->dry_cows,
            'totalCows'               => (int) $this->total_cows,
            'tankCapacityLiters'      => $this->tank_capacity_liters !== null ? (float) $this->tank_capacity_liters : null,
            'tankAlertPercentage'     => $this->tank_alert_percentage !== null ? (int) $this->tank_alert_percentage : null,
            'referencePricePerLiter'  => $this->reference_price_per_liter !== null ? (float) $this->reference_price_per_liter : null,
            'description'             => $this->description,
        ];
    }
}
