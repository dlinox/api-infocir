<?php

namespace App\Modules\PlantPanel\Investment\Http\Resources\PreOperativeExpense;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreOperativeExpenseFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'investmentCategoryId' => $this->investment_category_id,
            'name'                 => $this->name,
            'paymentDate'          => $this->payment_date?->toDateString(),
            'amount'               => (float) $this->amount,
            'recurrenceType'       => $this->recurrence_type,
            'validityYears'        => $this->validity_years,
            'expirationDate'       => $this->expiration_date?->toDateString(),
            'notes'                => $this->notes,
        ];
    }
}
