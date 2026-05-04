<?php

namespace App\Modules\Admin\Setting\Http\Resources\WorkingCapitalCatalog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkingCapitalCatalogDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'description'        => $this->description,
            'isActive'           => $this->is_active,
            'investmentCategory' => $this->investmentCategory
                ? ['id' => $this->investmentCategory->id, 'name' => $this->investmentCategory->name]
                : null,
            'unitMeasure'        => $this->unitMeasure
                ? ['id' => $this->unitMeasure->id, 'name' => $this->unitMeasure->name, 'abbreviation' => $this->unitMeasure->abbreviation]
                : null,
        ];
    }
}
