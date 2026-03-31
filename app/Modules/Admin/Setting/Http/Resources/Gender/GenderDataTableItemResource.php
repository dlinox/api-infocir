<?php

namespace App\Modules\Admin\Setting\Http\Resources\Gender;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GenderDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'isActive' => $this->is_active,
        ];
    }
}
