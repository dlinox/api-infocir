<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Http\Requests\Lesson;

use App\Common\Http\Requests\ApiFormRequest;

class LessonRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'            => 'nullable|integer',
            'module_id'     => 'required|integer|exists:learning_course_modules,id',
            'title'         => 'required|string|max:150',
            'description'   => 'nullable|string',
            'has_quiz'      => 'boolean',
            'passing_score' => 'nullable|numeric|min:0|max:100',
            'is_active'     => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'module_id.required'      => 'El :attribute es requerido.',
            'module_id.exists'        => 'El :attribute seleccionado no existe.',
            'title.required'          => 'El :attribute es requerido.',
            'title.max'               => 'El :attribute no debe exceder los :max caracteres.',
            'passing_score.numeric'   => 'El :attribute debe ser un número.',
            'passing_score.min'       => 'El :attribute no puede ser negativo.',
            'passing_score.max'       => 'El :attribute no puede exceder 100.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'            => 'ID',
            'module_id'     => 'Módulo',
            'title'         => 'Título',
            'description'   => 'Descripción',
            'has_quiz'      => 'Tiene evaluación',
            'passing_score' => 'Puntaje mínimo',
            'is_active'     => 'Estado',
        ];
    }
}
