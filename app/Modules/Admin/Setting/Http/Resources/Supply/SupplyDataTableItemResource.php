<?php

namespace App\Modules\Admin\Setting\Http\Resources\Supply;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplyDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'unitMeasure' => $this->unitMeasure ? [
                'id'   => $this->unitMeasure->id,
                'name' => $this->unitMeasure->name,
            ] : null,
            'description' => $this->description,
            'isActive'    => $this->is_active,
        ];
    }
}
