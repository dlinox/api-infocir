<?php

namespace App\Modules\Admin\Setting\Http\Resources\InstitutionType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstitutionTypeDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'nature'   => $this->nature,
            'level'    => $this->level,
            'isActive' => $this->is_active,
        ];
    }
}
