<?php

namespace App\Modules\Admin\Dairy\Sales\Order\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'code'          => $this->code,
            'status'        => $this->status,
            'customerName'  => $this->customer_name,
            'customerPhone' => $this->customer_phone,
            'total'         => (float) $this->total,
            'itemsCount'    => (int) ($this->items_count ?? 0),
            'plant'         => $this->plant ? ['id' => $this->plant->id, 'name' => $this->plant->name] : null,
            'createdAt'     => $this->created_at?->toDateTimeString(),
        ];
    }
}
