<?php

namespace App\Modules\Admin\Setting\Http\Resources\TrainingLevel;

use Illuminate\Http\Resources\Json\JsonResource;

class TrainingLevelSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->id,
        ];
    }
}
