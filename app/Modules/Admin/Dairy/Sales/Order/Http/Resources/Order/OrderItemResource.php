<?php

namespace App\Modules\Admin\Dairy\Sales\Order\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'presentationId'    => $this->presentation_id,
            'productName'       => $this->product_name,
            'presentationName'  => $this->presentation_name,
            'unitPrice'         => (float) $this->unit_price,
            'quantity'          => (int) $this->quantity,
            'subtotal'          => (float) $this->subtotal,
        ];
    }
}
