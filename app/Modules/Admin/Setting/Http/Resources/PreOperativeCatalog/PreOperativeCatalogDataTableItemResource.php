<?php

namespace App\Modules\Admin\Setting\Http\Resources\PreOperativeCatalog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreOperativeCatalogDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'issuingEntity'      => $this->issuing_entity,
            'recurrenceType'     => $this->recurrence_type,
            'validityYears'      => $this->validity_years,
            'isPublic'           => $this->is_public,
            'isActive'           => $this->is_active,
            'investmentCategory' => $this->investmentCategory
                ? ['id' => $this->investmentCategory->id, 'name' => $this->investmentCategory->name]
                : null,
        ];
    }
}
