<?php

namespace App\Modules\Learning\Instructor\Catalog\Program\Http\Requests\Program;

use App\Common\Http\Requests\ApiFormRequest;

class ProgramRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? 'NULL';

        return [
            'id'                       => 'nullable|integer',
            'name'                     => 'required|string|max:150|unique:learning_programs,name,' . $id . ',id',
            'description'              => 'nullable|string',
            'certificate_template_id'  => 'nullable|integer|exists:learning_certificate_templates,id',
            'status'                   => 'required|in:draft,published,archived',
            'is_active'                => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                    => 'El :attribute es requerido.',
            'name.string'                      => 'El :attribute debe ser una cadena de texto.',
            'name.max'                         => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'                      => 'El :attribute ya existe.',
            'certificate_template_id.integer'  => 'El :attribute debe ser un número entero.',
            'certificate_template_id.exists'   => 'El :attribute seleccionado no existe.',
            'status.required'                  => 'El :attribute es requerido.',
            'status.in'                        => 'El :attribute debe ser draft, published o archived.',
            'is_active.required'               => 'El :attribute es requerido.',
            'is_active.boolean'                => 'El :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'                      => 'ID',
            'name'                    => 'Nombre',
            'description'             => 'Descripción',
            'certificate_template_id' => 'Plantilla de certificado',
            'status'                  => 'Estado',
            'is_active'               => 'Activo',
        ];
    }
}
