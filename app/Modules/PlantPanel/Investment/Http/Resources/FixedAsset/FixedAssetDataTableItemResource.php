<?php

namespace App\Modules\PlantPanel\Investment\Http\Resources\FixedAsset;

use App\Modules\PlantPanel\Investment\Support\DepreciationCalculator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FixedAssetDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $purchaseDate  = $this->purchase_date ? Carbon::parse($this->purchase_date) : null;
        $purchaseCost  = (float) $this->purchase_cost;
        $residualValue = (float) ($this->residual_value ?? 0);
        $method        = $this->depreciation_method ?? 'straight_line';

        $depr = DepreciationCalculator::compute(
            purchaseCost:    $purchaseCost,
            residualValue:   $residualValue,
            usefulLifeYears: $this->useful_life_years,
            purchaseDate:    $purchaseDate?->toDateString(),
            method:          $method,
        );

        return [
            'id'                  => $this->id,
            'name'                => $this->name,
            'category'            => $this->investment_category_id ? [
                'id'   => $this->investment_category_id,
                'name' => $this->category_name ?? null,
            ] : null,
            'serialNumber'        => $this->serial_number,
            'purchaseDate'        => $purchaseDate?->toDateString(),
            'purchaseCost'        => $purchaseCost,
            'quantity'            => (int) ($this->quantity ?? 1),
            'residualValue'       => $residualValue,
            'usefulLifeYears'     => $this->useful_life_years,
            'depreciationMethod'  => $method,
            'status'              => $this->status,
            'notes'               => $this->notes,
            'monthlyDepreciation' => $depr['monthlyDepreciation'],
            'bookValue'           => $depr['bookValue'],
        ];
    }
}
