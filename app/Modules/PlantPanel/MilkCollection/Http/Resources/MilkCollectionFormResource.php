<?php

namespace App\Modules\PlantPanel\MilkCollection\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MilkCollectionFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $qt = $this->qualityTest;

        return [
            'id'             => $this->id,
            'supplierId'     => $this->supplier_id,
            'collectionDate' => optional($this->collection_date)->format('Y-m-d'),
            'shift'          => $this->shift,
            'quantityLiters' => (float) $this->quantity_liters,
            'pricePerLiter'  => (float) $this->price_per_liter,
            'totalAmount'    => (float) $this->total_amount,
            'latitude'       => $this->latitude !== null ? (float) $this->latitude : null,
            'longitude'      => $this->longitude !== null ? (float) $this->longitude : null,
            'paymentStatus'  => $this->payment_status,
            'photoUrl'       => $this->file ? $this->file->url : null,
            'observations'   => $this->observations,
            'qualityTest'    => $qt ? [
                'fatPercentage'  => $qt->fat_percentage !== null ? (float) $qt->fat_percentage : null,
                'snfPercentage'  => $qt->snf_percentage !== null ? (float) $qt->snf_percentage : null,
                'density'        => $qt->density !== null ? (float) $qt->density : null,
                'acidity'        => $qt->acidity !== null ? (float) $qt->acidity : null,
                'temperature'    => $qt->temperature !== null ? (float) $qt->temperature : null,
                'qualityGrade'   => $qt->quality_grade,
                'observations'   => $qt->observations,
            ] : null,
        ];
    }
}
