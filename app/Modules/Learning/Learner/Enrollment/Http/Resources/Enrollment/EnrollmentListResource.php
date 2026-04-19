<?php

namespace App\Modules\Learning\Learner\Enrollment\Http\Resources\Enrollment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'status'         => $this->status,
            'progress'       => (float) $this->progress,
            'enrolledAt'     => $this->enrolled_at?->toIso8601String(),
            'completedAt'    => $this->completed_at?->toIso8601String(),
            'enrollableType' => $this->enrollable_type,
            'enrollable'     => $this->buildEnrollable(),
            'certification'  => $this->certification ? [
                'id'                 => $this->certification->id,
                'certificateNumber'  => $this->certification->certificate_number,
                'issuedAt'           => $this->certification->issued_at,
                'expiresAt'          => $this->certification->expires_at,
            ] : null,
        ];
    }

    private function buildEnrollable(): ?array
    {
        $e = $this->enrollable;
        if (!$e) return null;

        return match ($this->enrollable_type) {
            'learning_courses'            => $this->buildCourse($e),
            'learning_trainings'          => $this->buildTraining($e),
            'learning_program_deliveries' => $this->buildProgramDelivery($e),
            default                       => null,
        };
    }

    private function buildCourse($course): array
    {
        return [
            'id'          => $course->id,
            'name'        => $course->name,
            'description' => $course->description,
            'durationMin' => $course->duration_min,
            'area'        => $course->area ? ['id' => $course->area->id, 'name' => $course->area->name] : null,
            'coverImage'  => $course->coverImageFile ? [
                'id'       => $course->coverImageFile->id,
                'url'      => $course->coverImageFile->url,
                'filename' => $course->coverImageFile->filename,
            ] : null,
            'hasContent'  => true,
        ];
    }

    private function buildTraining($training): array
    {
        $course = $training->course;

        return [
            'id'           => $training->id,
            'name'         => $course?->name ?? 'Capacitación',
            'description'  => $course?->description,
            'durationMin'  => $course?->duration_min,
            'modality'     => $training->modality,
            'startDate'    => $training->start_date,
            'endDate'      => $training->end_date,
            'status'       => $training->status,
            'location'     => $training->location,
            'meetingUrl'   => $training->meeting_url ?? null,
            'isEventOnly'  => (bool) $training->is_event_only,
            'trainingType' => $training->trainingType ? [
                'id' => $training->trainingType->id,
                'name' => $training->trainingType->name,
            ] : null,
            'instructor'   => $this->buildInstructor($training->instructor),
            'coverImage'   => $course?->coverImageFile ? [
                'id'       => $course->coverImageFile->id,
                'url'      => $course->coverImageFile->url,
                'filename' => $course->coverImageFile->filename,
            ] : null,
            'courseId'     => $course?->id,
            'hasContent'   => !$training->is_event_only && $course !== null,
        ];
    }

    private function buildProgramDelivery($delivery): array
    {
        return [
            'id'           => $delivery->id,
            'name'         => $delivery->program?->name ?? 'Programa',
            'description'  => $delivery->program?->description,
            'modality'     => $delivery->modality,
            'startDate'    => $delivery->start_date,
            'endDate'      => $delivery->end_date,
            'status'       => $delivery->status,
            'location'     => $delivery->location,
            'trainingType' => $delivery->trainingType ? [
                'id' => $delivery->trainingType->id,
                'name' => $delivery->trainingType->name,
            ] : null,
            'instructor'   => $this->buildInstructor($delivery->instructor),
            'programId'    => $delivery->program?->id,
            'hasContent'   => false,
        ];
    }

    private function buildInstructor($instructor): ?array
    {
        if (!$instructor || !$instructor->person) return null;

        return [
            'id'       => $instructor->id,
            'fullName' => collect([
                $instructor->person->name,
                $instructor->person->paternal_surname,
                $instructor->person->maternal_surname,
            ])->filter()->implode(' '),
        ];
    }
}
