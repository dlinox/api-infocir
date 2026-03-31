<?php

namespace App\Modules\Admin\Setting\Http\Resources\Position;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PositionDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'isActive'    => $this->is_active,
        ];
    }
}
