<?php

namespace App\Modules\Admin\Setting\Http\Resources\InvestmentCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentCategoryDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'name'                    => $this->name,
            'group'                   => $this->group,
            'defaultUsefulLifeYears'  => $this->default_useful_life_years,
            'defaultValidityYears'    => $this->default_validity_years,
            'hint'                    => $this->hint,
            'sortOrder'               => $this->sort_order,
            'isActive'                => $this->is_active,
        ];
    }
}
