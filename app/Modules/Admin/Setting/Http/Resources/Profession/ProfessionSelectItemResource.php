<?php

namespace App\Modules\Admin\Setting\Http\Resources\Profession;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfessionSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->id,
        ];
    }
}
