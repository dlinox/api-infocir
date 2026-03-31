<?php

namespace App\Modules\Admin\Setting\Http\Resources\InstitutionType;

use Illuminate\Http\Resources\Json\JsonResource;

class InstitutionTypeSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->id,
        ];
    }
}
