<?php

namespace App\Modules\SupplierPanel\MilkPayment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MilkPaymentDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'periodStart' => optional($this->period_start)->format('Y-m-d'),
            'periodEnd'   => optional($this->period_end)->format('Y-m-d'),
            'totalLiters' => (float) $this->total_liters,
            'totalAmount' => (float) $this->total_amount,
            'deductions'  => (float) $this->deductions,
            'netAmount'   => (float) $this->net_amount,
            'status'      => $this->status,
            'paidAt'      => optional($this->paid_at)->toIso8601String(),
            'plant'       => $this->plant_id_alias ? [
                'id'        => (int) $this->plant_id_alias,
                'name'      => $this->plant_name,
                'tradeName' => $this->plant_trade_name,
            ] : null,
        ];
    }
}
