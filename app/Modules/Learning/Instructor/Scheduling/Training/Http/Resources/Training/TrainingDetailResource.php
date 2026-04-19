<?php

namespace App\Modules\Learning\Instructor\Scheduling\Training\Http\Resources\Training;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'courseId'              => $this->course_id,
            'instructorId'          => $this->instructor_id,
            'trainingTypeId'        => $this->training_type_id,
            'certificateTemplateId' => $this->certificate_template_id,
            'isEventOnly'           => $this->is_event_only,
            'startDate'             => $this->start_date,
            'endDate'               => $this->end_date,
            'status'                => $this->status,
            'modality'              => $this->modality,
            'location'              => $this->location,
            'latitude'              => $this->latitude ? (float) $this->latitude : null,
            'longitude'             => $this->longitude ? (float) $this->longitude : null,
            'meetingUrl'            => $this->meeting_url,
            'maxParticipants'       => $this->max_participants,
            'isActive'              => $this->is_active,
        ];
    }
}
