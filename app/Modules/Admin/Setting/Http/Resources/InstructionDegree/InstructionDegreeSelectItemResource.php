<?php

namespace App\Modules\Admin\Setting\Http\Resources\InstructionDegree;

use Illuminate\Http\Resources\Json\JsonResource;

class InstructionDegreeSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->id,
        ];
    }
}
