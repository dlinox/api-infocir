<?php

namespace App\Modules\Learning\Instructor\Catalog\Program\Http\Resources\Program;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'name'                 => $this->name,
            'description'          => $this->description,
            'certificateTemplate'  => $this->certificateTemplate ? [
                'id'   => $this->certificateTemplate->id,
                'name' => $this->certificateTemplate->name,
            ] : null,
            'status'               => $this->status,
            'isActive'             => $this->is_active,
            'createdAt'            => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
