<?php

namespace App\Modules\Admin\Setting\Http\Resources\Gender;

use Illuminate\Http\Resources\Json\JsonResource;

class GenderSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->code,
        ];
    }
}
