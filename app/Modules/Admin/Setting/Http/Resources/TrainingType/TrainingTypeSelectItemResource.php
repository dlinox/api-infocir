<?php

namespace App\Modules\Admin\Setting\Http\Resources\TrainingType;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingTypeSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->id,
        ];
    }
}
