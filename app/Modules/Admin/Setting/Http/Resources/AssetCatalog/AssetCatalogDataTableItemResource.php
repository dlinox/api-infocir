<?php

namespace App\Modules\Admin\Setting\Http\Resources\AssetCatalog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetCatalogDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'brand'              => $this->brand,
            'model'              => $this->model,
            'usefulLifeYears'    => $this->useful_life_years,
            'depreciationMethod' => $this->depreciation_method,
            'isActive'           => $this->is_active,
            'investmentCategory' => $this->investmentCategory
                ? ['id' => $this->investmentCategory->id, 'name' => $this->investmentCategory->name, 'group' => $this->investmentCategory->group]
                : null,
        ];
    }
}
