<?php

namespace App\Modules\Admin\Dairy\Sales\Order\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'code'             => $this->code,
            'status'           => $this->status,
            'customerName'     => $this->customer_name,
            'customerPhone'    => $this->customer_phone,
            'customerEmail'    => $this->customer_email,
            'customerDocument' => $this->customer_document,
            'address'          => $this->address,
            'district'         => $this->district,
            'city'             => $this->city,
            'reference'        => $this->reference,
            'inquiry'          => $this->inquiry,
            'subtotal'         => (float) $this->subtotal,
            'total'            => (float) $this->total,
            'stockApplied'     => (bool) $this->stock_applied,
            'receiptNumber'    => $this->receipt_number,
            'receiptIssuedAt'  => $this->receipt_issued_at?->toDateTimeString(),
            'whatsappSentAt'   => $this->whatsapp_sent_at?->toDateTimeString(),
            'createdAt'        => $this->created_at?->toDateTimeString(),
            'plant'            => $this->plant ? ['id' => $this->plant->id, 'name' => $this->plant->name, 'cellphone' => $this->plant->cellphone] : null,
            'items'            => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
