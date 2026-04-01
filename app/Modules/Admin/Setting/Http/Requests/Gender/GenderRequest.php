<?php

namespace App\Modules\Admin\Setting\Http\Requests\Gender;

use App\Common\Http\Requests\ApiFormRequest;

class GenderRequest extends ApiFormRequest
{
    public function rules()
    {
        $code = $this->code ?? null;
        return [
            'code' => 'required',
            'name' => 'required|string|max:100|unique:core_genders,name,' . $code . ',code',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'El campo :attribute es obligatorio.',
            'name.required' => 'El campo :attribute es obligatorio.',
            'name.string' => 'El campo :attribute debe ser una cadena de texto.',
            'name.max' => 'El campo :attribute no debe exceder los 100 caracteres.',
            'name.unique' => 'El :attribute ya está en uso.',
            'is_active.required' => 'El campo :attribute es obligatorio.',
            'is_active.boolean' => 'El campo :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'Código',
            'name' => 'Nombre',
            'is_active' => 'Estado',
        ];
    }
}
