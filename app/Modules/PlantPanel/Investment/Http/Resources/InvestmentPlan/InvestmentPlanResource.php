<?php

namespace App\Modules\PlantPanel\Investment\Http\Resources\InvestmentPlan;

use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentPlanResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'planType'        => $this->plan_type,
            'periodYear'      => (int) $this->period_year,
            'periodMonth'     => $this->period_month ? (int) $this->period_month : null,
            'status'          => $this->status,
            'totalAmount'     => (float) $this->total_amount,
            'notes'           => $this->notes,
            'items'           => $this->items->map(fn ($item) => [
                'id'                   => $item->id,
                'investmentCategoryId' => $item->investment_category_id,
                'category'             => $item->category ? [
                    'id'    => $item->category->id,
                    'name'  => $item->category->name,
                    'group' => $item->category->group,
                ] : null,
                'name'                 => $item->name,
                'recurrenceType'       => $item->recurrence_type,
                'unitValue'            => (float) $item->unit_value,
                'quantity'             => (float) $item->quantity,
                'total'                => (float) $item->total,
                'sortOrder'            => (int) $item->sort_order,
            ])->values(),
        ];
    }
}