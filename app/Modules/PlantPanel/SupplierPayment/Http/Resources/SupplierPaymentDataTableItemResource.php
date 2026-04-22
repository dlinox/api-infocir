<?php

namespace App\Modules\PlantPanel\SupplierPayment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierPaymentDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'periodStart'  => optional($this->period_start)->format('Y-m-d'),
            'periodEnd'    => optional($this->period_end)->format('Y-m-d'),
            'totalLiters'  => (float) $this->total_liters,
            'totalAmount'  => (float) $this->total_amount,
            'deductions'   => (float) $this->deductions,
            'netAmount'    => (float) $this->net_amount,
            'status'       => $this->status,
            'paidAt'       => optional($this->paid_at)->format('Y-m-d H:i:s'),
            'supplier'     => $this->supplier_id_alias ? [
                'id'             => (int) $this->supplier_id_alias,
                'name'           => $this->supplier_name,
                'tradeName'      => $this->supplier_trade_name,
                'documentNumber' => $this->supplier_document_number,
            ] : null,
        ];
    }
}
