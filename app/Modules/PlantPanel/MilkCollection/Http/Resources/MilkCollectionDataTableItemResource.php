<?php

namespace App\Modules\PlantPanel\MilkCollection\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MilkCollectionDataTableItemResource extends JsonResource
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
            'photoUrl'       => $this->file ? $this->file->url : null,
            'observations'   => $this->observations,
            'supplier'       => $this->supplier_id_alias ? [
                'id'             => (int) $this->supplier_id_alias,
                'name'           => $this->supplier_name,
                'tradeName'      => $this->supplier_trade_name,
                'documentNumber' => $this->supplier_document_number,
            ] : null,
        ];
    }
}
