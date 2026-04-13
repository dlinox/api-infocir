<?php

namespace App\Modules\Shared\Http\Requests\Person;

use App\Common\Http\Requests\ApiFormRequest;

class SearchByDocumentRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'document_type' => ['required', 'string'],
            'document_number' => ['required', 'string', 'max:20'],
            'profile' => ['required', 'string', 'in:admins,workers,instructors'],
            'id' => ['nullable', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'document_type.required' => 'Tipo de documento es requerido',
            'document_number.required' => 'Número de documento es requerido',
            'profile.required' => 'Perfil es requerido',
            'profile.in' => 'Perfil no es válido',
        ];
    }

    public function attributes(): array
    {
        return [
            'document_type' => 'Tipo de documento',
            'document_number' => 'Número de documento',
            'profile' => 'Perfil',
        ];
    }
}
