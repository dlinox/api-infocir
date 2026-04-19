<?php

namespace App\Modules\Learning\Instructor\Catalog\Program\Http\Requests\ProgramCourse;

use App\Common\Http\Requests\ApiFormRequest;

class ProgramCourseRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? 'NULL';

        return [
            'id'          => 'nullable|integer',
            'program_id'  => 'required|integer|exists:learning_programs,id',
            'course_id'   => 'required|integer|exists:learning_courses,id|unique:learning_program_courses,course_id,'. $id .',id,program_id,' . $this->program_id,
            'order'       => 'nullable|integer|min:1',
            'is_required' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'program_id.required' => 'El :attribute es requerido.',
            'program_id.exists'   => 'El :attribute seleccionado no existe.',
            'course_id.required'  => 'El :attribute es requerido.',
            'course_id.exists'    => 'El :attribute seleccionado no existe.',
            'course_id.unique'    => 'El :attribute ya está asignado a este programa.',
            'order.integer'       => 'El :attribute debe ser un número entero.',
            'order.min'           => 'El :attribute debe ser al menos :min.',
            'is_required.required' => 'El :attribute es requerido.',
            'is_required.boolean'  => 'El :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'          => 'ID',
            'program_id'  => 'Programa',
            'course_id'   => 'Curso',
            'order'       => 'Orden',
            'is_required' => 'Obligatorio',
        ];
    }
}
