<?php

namespace App\Modules\SupplierPanel\MilkDelivery\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MilkDeliveryDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'collectionDate' => optional($this->collection_date)->format('Y-m-d'),
            'shift'          => $this->shift,
            'quantityLiters' => (float) $this->quantity_liters,
            'pricePerLiter'  => (float) $this->price_per_liter,
            'totalAmount'    => (float) $this->total_amount,
            'paymentStatus'  => $this->payment_status,
            'observations'   => $this->observations,
            'plant'          => $this->plant_id_alias ? [
                'id'        => (int) $this->plant_id_alias,
                'name'      => $this->plant_name,
                'tradeName' => $this->plant_trade_name,
            ] : null,
        ];
    }
}
