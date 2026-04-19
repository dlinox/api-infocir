<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Http\Requests\LessonResource;

use App\Common\Http\Requests\ApiFormRequest;

class LessonResourceRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'        => 'nullable|integer|exists:learning_lesson_resources,id',
            'lesson_id' => 'required|integer|exists:learning_lessons,id',
            'type'      => 'required|string|in:video,youtube,pdf,image,text,link',
            'title'     => 'nullable|string|max:150',
            'file_id'   => 'nullable|integer|exists:core_files,id|required_if:type,video|required_if:type,pdf|required_if:type,image',
            'body'      => 'nullable|string|required_if:type,youtube|required_if:type,link|required_if:type,text',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'lesson_id.required' => 'La lección es requerida.',
            'lesson_id.exists'   => 'La lección no existe.',
            'type.required'      => 'El tipo de recurso es requerido.',
            'type.in'            => 'El tipo de recurso no es válido.',
            'file_id.required_if' => 'El archivo es requerido para este tipo de recurso.',
            'file_id.exists'     => 'El archivo no existe.',
            'body.required_if'   => 'El contenido es requerido para este tipo de recurso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'lesson_id' => 'lección',
            'type'      => 'tipo',
            'title'     => 'título',
            'file_id'   => 'archivo',
            'body'      => 'contenido',
            'is_active' => 'estado',
        ];
    }
}
