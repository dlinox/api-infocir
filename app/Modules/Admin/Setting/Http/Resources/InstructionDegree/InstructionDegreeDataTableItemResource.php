<?php

namespace App\Modules\Admin\Setting\Http\Resources\InstructionDegree;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructionDegreeDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'isActive' => $this->is_active,
        ];
    }
}
