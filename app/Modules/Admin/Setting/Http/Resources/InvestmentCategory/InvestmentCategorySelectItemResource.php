<?php

namespace App\Modules\Admin\Setting\Http\Resources\InvestmentCategory;

use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentCategorySelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title'                  => $this->name,
            'value'                  => $this->id,
            'group'                  => $this->group,
            'defaultUsefulLifeYears' => $this->default_useful_life_years,
            'defaultValidityYears'   => $this->default_validity_years,
            'hint'                   => $this->hint,
        ];
    }
}
