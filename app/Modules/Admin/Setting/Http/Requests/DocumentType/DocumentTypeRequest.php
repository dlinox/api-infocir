<?php

namespace App\Modules\Admin\Setting\Http\Requests\DocumentType;

use App\Common\Http\Requests\ApiFormRequest;

class DocumentTypeRequest extends ApiFormRequest
{
    public function rules()
    {
        $code = $this->code ?? null;
        return [
            'code'      => 'required',
            'name'      => 'required|string|max:100|unique:core_document_types,name,' . $code . ',code',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required'      => 'El :attribute es requerido.',
            'name.required'      => 'El :attribute es requerido.',
            'name.string'        => 'El :attribute debe ser una cadena de texto.',
            'name.max'           => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'        => 'El :attribute ya existe.',
            'is_active.required' => 'El :attribute es requerido.',
            'is_active.boolean'  => 'El :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'code'      => 'Código',
            'name'      => 'Nombre',
            'is_active' => 'Estado',
        ];
    }
}
