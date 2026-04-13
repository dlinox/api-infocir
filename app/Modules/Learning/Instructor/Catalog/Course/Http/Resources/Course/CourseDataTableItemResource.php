<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'area'          => $this->area ? ['id' => $this->area->id, 'name' => $this->area->name] : null,
            'durationMin'   => $this->duration_min,
            'status'        => $this->status,
            'createdAt'     => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
