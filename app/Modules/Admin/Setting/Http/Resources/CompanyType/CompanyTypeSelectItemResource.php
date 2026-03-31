<?php

namespace App\Modules\Admin\Setting\Http\Resources\CompanyType;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyTypeSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->id,
        ];
    }
}
