<?php

namespace App\Modules\Learning\Instructor\Catalog\Program\Http\Resources\Program;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramDetailResource extends JsonResource
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
            'programCourses'       => $this->programCourses->map(fn ($pc) => [
                'id'         => $pc->id,
                'courseId'   => $pc->course_id,
                'course'     => $pc->course ? [
                    'id'          => $pc->course->id,
                    'name'        => $pc->course->name,
                    'status'      => $pc->course->status,
                    'durationMin' => $pc->course->duration_min,
                    'area'        => $pc->course->area ? [
                        'id'   => $pc->course->area->id,
                        'name' => $pc->course->area->name,
                    ] : null,
                ] : null,
                'order'      => $pc->order,
                'isRequired' => $pc->is_required,
            ])->toArray(),
        ];
    }
}
