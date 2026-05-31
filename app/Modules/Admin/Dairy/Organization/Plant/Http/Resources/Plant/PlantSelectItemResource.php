<?php

namespace App\Modules\Admin\Dairy\Organization\Plant\Http\Resources\Plant;

use Illuminate\Http\Resources\Json\JsonResource;

class PlantSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->id,
        ];
    }
}
