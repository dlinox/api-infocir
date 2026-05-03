<?php

namespace App\Modules\PlantPanel\Investment\Http\Resources\FixedAsset;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FixedAssetFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'investmentCategoryId' => $this->investment_category_id,
            'name'                 => $this->name,
            'serialNumber'         => $this->serial_number,
            'purchaseDate'         => $this->purchase_date?->toDateString(),
            'purchaseCost'         => (float) $this->purchase_cost,
            'quantity'             => (int) ($this->quantity ?? 1),
            'residualValue'        => (float) ($this->residual_value ?? 0),
            'usefulLifeYears'      => $this->useful_life_years,
            'depreciationMethod'   => $this->depreciation_method ?? 'straight_line',
            'status'               => $this->status,
            'notes'                => $this->notes,
        ];
    }
}
