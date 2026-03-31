<?php

namespace App\Modules\Admin\Setting\Http\Requests\UnitMeasure;

use App\Common\Http\Requests\ApiFormRequest;

class UnitMeasureRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? 'NULL';
        return [
            'id'           => 'nullable|integer',
            'name'         => 'required|string|max:100|unique:core_unit_measures,name,' . $id . ',id',
            'abbreviation' => 'required|string|max:20|unique:core_unit_measures,abbreviation,' . $id . ',id',
            'is_active'    => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'El :attribute es requerido.',
            'name.string'           => 'El :attribute debe ser una cadena de texto.',
            'name.max'              => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'           => 'El :attribute ya existe.',
            'abbreviation.required' => 'La :attribute es requerida.',
            'abbreviation.string'   => 'La :attribute debe ser una cadena de texto.',
            'abbreviation.max'      => 'La :attribute no debe exceder los :max caracteres.',
            'abbreviation.unique'   => 'La :attribute ya existe.',
            'is_active.required'    => 'El :attribute es requerido.',
            'is_active.boolean'     => 'El :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'           => 'ID',
            'name'         => 'Nombre',
            'abbreviation' => 'Abreviatura',
            'is_active'    => 'Estado',
        ];
    }
}
