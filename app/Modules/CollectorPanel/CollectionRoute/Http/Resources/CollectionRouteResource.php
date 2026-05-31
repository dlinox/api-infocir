<?php

namespace App\Modules\CollectorPanel\CollectionRoute\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectionRouteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'status'          => $this->status,
            'startedAt'       => $this->started_at?->toISOString(),
            'endedAt'         => $this->ended_at?->toISOString(),
            'startLatitude'   => $this->start_latitude ? (float) $this->start_latitude : null,
            'startLongitude'  => $this->start_longitude ? (float) $this->start_longitude : null,
            'endLatitude'     => $this->end_latitude ? (float) $this->end_latitude : null,
            'endLongitude'    => $this->end_longitude ? (float) $this->end_longitude : null,
            'initialMileage'  => $this->initial_mileage ? (float) $this->initial_mileage : null,
            'finalMileage'    => $this->final_mileage ? (float) $this->final_mileage : null,
            'observations'    => $this->observations,
            'collectionsCount' => (int) ($this->collections_count ?? $this->milkCollections()->count()),
            'totalLiters'     => (float) ($this->total_liters ?? $this->milkCollections()->sum('quantity_liters')),
            'totalExpenses'   => (float) $this->expenses->sum('amount'),
            'expenses'        => $this->expenses->map(fn ($expense) => [
                'id'       => $expense->id,
                'amount'   => (float) $expense->amount,
                'quantity' => $expense->quantity !== null ? (float) $expense->quantity : null,
                'notes'    => $expense->notes,
                'catalogItem' => $expense->catalogItem ? [
                    'id'          => $expense->catalogItem->id,
                    'name'        => $expense->catalogItem->name,
                    'color'       => $expense->catalogItem->color,
                    'unitMeasure' => $expense->catalogItem->unitMeasure
                        ? ['id' => $expense->catalogItem->unitMeasure->id, 'abbreviation' => $expense->catalogItem->unitMeasure->abbreviation]
                        : null,
                ] : null,
            ])->values(),
        ];
    }
}
