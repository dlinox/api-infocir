<?php

namespace App\Modules\Learning\Instructor\Catalog\CertificateTemplate\Http\Requests\CertificateTemplate;

use App\Common\Http\Requests\ApiFormRequest;

class CertificateTemplateRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'course_id'    => 'required|integer|exists:learning_courses,id',
            'name'         => 'nullable|string|max:150',
            'fields'       => 'nullable|array',
            'validity_days' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'El :attribute es requerido.',
            'course_id.exists'   => 'El :attribute no existe.',
            'name.max'           => 'El :attribute no debe exceder los :max caracteres.',
            'validity_days.min'  => 'El :attribute debe ser al menos 1 día.',
        ];
    }

    public function attributes(): array
    {
        return [
            'course_id'    => 'curso',
            'name'         => 'nombre de la plantilla',
            'fields'       => 'campos',
            'validity_days' => 'días de vigencia',
        ];
    }
}
