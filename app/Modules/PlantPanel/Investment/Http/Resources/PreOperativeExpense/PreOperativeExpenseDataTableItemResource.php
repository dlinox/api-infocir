<?php

namespace App\Modules\PlantPanel\Investment\Http\Resources\PreOperativeExpense;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreOperativeExpenseDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $today          = Carbon::today();
        $expirationDate = $this->expiration_date ? Carbon::parse($this->expiration_date) : null;

        $vigencyStatus = 'no_expira';
        $daysToExpire  = null;

        if ($expirationDate) {
            $daysToExpire = $today->diffInDays($expirationDate, false);
            if ($daysToExpire < 0) {
                $vigencyStatus = 'vencido';
            } elseif ($daysToExpire <= 60) {
                $vigencyStatus = 'por_vencer';
            } else {
                $vigencyStatus = 'vigente';
            }
        }

        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'category'       => $this->investment_category_id ? [
                'id'   => $this->investment_category_id,
                'name' => $this->category_name ?? null,
            ] : null,
            'paymentDate'    => $this->payment_date?->toDateString(),
            'amount'         => (float) $this->amount,
            'recurrenceType' => $this->recurrence_type,
            'validityYears'  => $this->validity_years,
            'expirationDate' => $expirationDate?->toDateString(),
            'vigencyStatus'  => $vigencyStatus,
            'daysToExpire'   => $daysToExpire,
            'notes'          => $this->notes,
        ];
    }
}
