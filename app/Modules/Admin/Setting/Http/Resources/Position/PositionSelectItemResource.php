<?php

namespace App\Modules\Admin\Setting\Http\Resources\Position;

use Illuminate\Http\Resources\Json\JsonResource;

class PositionSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->id,
        ];
    }
}
