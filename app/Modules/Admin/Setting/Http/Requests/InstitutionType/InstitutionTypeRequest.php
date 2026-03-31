<?php

namespace App\Modules\Admin\Setting\Http\Requests\InstitutionType;

use App\Common\Http\Requests\ApiFormRequest;

class InstitutionTypeRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? 'NULL';
        return [
            'id'        => 'nullable|integer',
            'name'      => 'required|string|max:100|unique:dairy_institution_types,name,' . $id . ',id',
            'nature'    => 'required|in:public,private,mixed',
            'level'     => 'required|in:national,regional,provincial,district',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'El :attribute es requerido.',
            'name.string'        => 'El :attribute debe ser una cadena de texto.',
            'name.max'           => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'        => 'El :attribute ya existe.',
            'nature.required'    => 'La :attribute es requerida.',
            'nature.in'          => 'La :attribute debe ser: pública, privada o mixta.',
            'level.required'     => 'El :attribute es requerido.',
            'level.in'           => 'El :attribute debe ser: nacional, regional, provincial o distrital.',
            'is_active.required' => 'El :attribute es requerido.',
            'is_active.boolean'  => 'El :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'        => 'ID',
            'name'      => 'Nombre',
            'nature'    => 'Naturaleza',
            'level'     => 'Nivel',
            'is_active' => 'Estado',
        ];
    }
}
