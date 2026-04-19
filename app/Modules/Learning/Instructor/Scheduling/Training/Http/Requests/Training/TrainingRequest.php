<?php

namespace App\Modules\Learning\Instructor\Scheduling\Training\Http\Requests\Training;

use App\Common\Http\Requests\ApiFormRequest;

class TrainingRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'                      => 'nullable|integer',
            'course_id'               => 'nullable|integer|exists:learning_courses,id',
            'instructor_id'           => 'nullable|integer|exists:learning_instructors,id',
            'training_type_id'        => 'nullable|integer|exists:learning_training_types,id',
            'certificate_template_id' => 'nullable|integer|exists:learning_certificate_templates,id',
            'is_event_only'           => 'required|boolean',
            'start_date'              => 'nullable|date',
            'end_date'                => 'nullable|date|after_or_equal:start_date',
            'status'                  => 'required|in:scheduled,ongoing,completed,cancelled',
            'modality'                => 'required|in:in_person,virtual,mixed',
            'location'                => 'nullable|string|max:200',
            'latitude'                => 'nullable|numeric|between:-90,90',
            'longitude'               => 'nullable|numeric|between:-180,180',
            'meeting_url'             => 'nullable|url|max:500',
            'max_participants'        => 'nullable|integer|min:1',
            'is_active'               => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.integer'               => 'El :attribute debe ser un número entero.',
            'course_id.exists'                => 'El :attribute seleccionado no existe.',
            'instructor_id.integer'           => 'El :attribute debe ser un número entero.',
            'instructor_id.exists'            => 'El :attribute seleccionado no existe.',
            'training_type_id.integer'        => 'El :attribute debe ser un número entero.',
            'training_type_id.exists'         => 'El :attribute seleccionado no existe.',
            'certificate_template_id.integer' => 'El :attribute debe ser un número entero.',
            'certificate_template_id.exists'  => 'El :attribute seleccionado no existe.',
            'is_event_only.required'          => 'El :attribute es requerido.',
            'is_event_only.boolean'           => 'El :attribute debe ser verdadero o falso.',
            'start_date.date'                 => 'El :attribute debe ser una fecha válida.',
            'end_date.date'                   => 'El :attribute debe ser una fecha válida.',
            'end_date.after_or_equal'         => 'El :attribute debe ser igual o posterior a la fecha de inicio.',
            'status.required'                 => 'El :attribute es requerido.',
            'status.in'                       => 'El :attribute debe ser: programada, en curso, completada o cancelada.',
            'modality.required'               => 'La :attribute es requerida.',
            'modality.in'                     => 'La :attribute debe ser: presencial, virtual o mixta.',
            'location.max'                    => 'El :attribute no debe exceder los :max caracteres.',
            'latitude.numeric'                => 'La :attribute debe ser un valor numérico.',
            'latitude.between'               => 'La :attribute debe estar entre -90 y 90.',
            'longitude.numeric'              => 'La :attribute debe ser un valor numérico.',
            'longitude.between'             => 'La :attribute debe estar entre -180 y 180.',
            'meeting_url.url'                 => 'El :attribute debe ser una URL válida.',
            'meeting_url.max'                 => 'El :attribute no debe exceder los :max caracteres.',
            'max_participants.integer'        => 'El :attribute debe ser un número entero.',
            'max_participants.min'            => 'El :attribute debe ser al menos :min.',
            'is_active.required'              => 'El :attribute es requerido.',
            'is_active.boolean'               => 'El :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'                      => 'ID',
            'course_id'               => 'Curso',
            'instructor_id'           => 'Instructor',
            'training_type_id'        => 'Tipo de capacitación',
            'certificate_template_id' => 'Plantilla de certificado',
            'is_event_only'           => 'Solo evento',
            'start_date'              => 'Fecha de inicio',
            'end_date'                => 'Fecha de fin',
            'status'                  => 'Estado',
            'modality'                => 'Modalidad',
            'location'                => 'Lugar',
            'latitude'                => 'Latitud',
            'longitude'               => 'Longitud',
            'meeting_url'             => 'Enlace de reunión',
            'max_participants'        => 'Máximo de participantes',
            'is_active'               => 'Activo',
        ];
    }
}
