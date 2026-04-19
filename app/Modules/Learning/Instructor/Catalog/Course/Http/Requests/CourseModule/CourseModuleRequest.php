<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Http\Requests\CourseModule;

use App\Common\Http\Requests\ApiFormRequest;

class CourseModuleRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'          => 'nullable|integer',
            'course_id'   => 'required|integer|exists:learning_courses,id',
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'El :attribute es requerido.',
            'course_id.exists'   => 'El :attribute seleccionado no existe.',
            'title.required'     => 'El :attribute es requerido.',
            'title.max'          => 'El :attribute no debe exceder los :max caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'          => 'ID',
            'course_id'   => 'Curso',
            'title'       => 'Título',
            'description' => 'Descripción',
            'is_active'   => 'Estado',
        ];
    }
}
