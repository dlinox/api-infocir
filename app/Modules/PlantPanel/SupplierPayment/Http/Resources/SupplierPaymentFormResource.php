<?php

namespace App\Modules\PlantPanel\SupplierPayment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierPaymentFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'supplierId'   => $this->supplier_id,
            'periodStart'  => optional($this->period_start)->format('Y-m-d'),
            'periodEnd'    => optional($this->period_end)->format('Y-m-d'),
            'totalLiters'  => (float) $this->total_liters,
            'totalAmount'  => (float) $this->total_amount,
            'deductions'   => (float) $this->deductions,
            'netAmount'    => (float) $this->net_amount,
            'status'       => $this->status,
            'paidAt'       => optional($this->paid_at)->format('Y-m-d H:i:s'),
            'observations' => $this->observations,
        ];
    }
}
