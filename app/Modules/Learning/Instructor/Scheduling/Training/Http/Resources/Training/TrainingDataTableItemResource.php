<?php

namespace App\Modules\Learning\Instructor\Scheduling\Training\Http\Resources\Training;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $instructorFullName = collect([
            $this->instructor_name,
            $this->instructor_paternal_surname,
            $this->instructor_maternal_surname,
        ])->filter()->implode(' ');

        return [
            'id'                  => $this->id,
            'course'              => $this->course_name ? ['name' => $this->course_name] : null,
            'instructor'          => $instructorFullName ? ['fullName' => $instructorFullName] : null,
            'trainingType'        => $this->training_type_name ? ['name' => $this->training_type_name] : null,
            'isEventOnly'         => $this->is_event_only,
            'startDate'           => $this->start_date,
            'endDate'             => $this->end_date,
            'status'              => $this->status,
            'modality'            => $this->modality,
            'location'            => $this->location,
            'maxParticipants'     => $this->max_participants,
            'isActive'            => $this->is_active,
            'createdAt'           => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
