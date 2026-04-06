<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Resources\ProductFormula;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductFormulaVersionSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'version' => $this['version'],
            'totalCost' => $this['totalCost'],
            'supplyCount' => $this['supplyCount'],
            'isCurrent' => $this['isCurrent'],
            'updatedAt' => $this['updatedAt'],
            'delta' => $this['delta'],
        ];
    }
}
