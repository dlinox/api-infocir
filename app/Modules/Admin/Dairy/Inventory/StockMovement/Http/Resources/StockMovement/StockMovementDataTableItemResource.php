<?php

namespace App\Modules\Admin\Dairy\Inventory\StockMovement\Http\Resources\StockMovement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockMovementDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'presentation'   => $this->presentation ? [
                'id'   => $this->presentation->id,
                'name' => $this->presentation->name,
            ] : null,
            'plant'          => $this->plant ? [
                'id'   => $this->plant->id,
                'name' => $this->plant->name,
            ] : null,
            'type'           => $this->type,
            'quantity'       => $this->quantity,
            'batchCode'      => $this->batch_code,
            'expirationDate' => $this->expiration_date?->format('Y-m-d'),
            'reason'         => $this->reason,
            'createdBy'      => $this->creator?->name,
            'createdAt'      => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
