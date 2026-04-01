<?php

namespace App\Modules\Admin\Setting\Http\Requests\Supply;

use App\Common\Http\Requests\ApiFormRequest;

class SupplyRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? 'NULL';
        return [
            'id'               => 'nullable|integer',
            'name'             => 'required|string|max:100|unique:dairy_supplies,name,' . $id . ',id',
            'unit_measure_id'  => 'nullable|integer|exists:core_unit_measures,id',
            'description'      => 'nullable|string|max:255',
            'is_active'        => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'El :attribute es requerido.',
            'name.string'              => 'El :attribute debe ser una cadena de texto.',
            'name.max'                 => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'              => 'El :attribute ya existe.',
            'unit_measure_id.exists'   => 'La :attribute seleccionada no existe.',
            'description.string'       => 'La :attribute debe ser una cadena de texto.',
            'description.max'          => 'La :attribute no debe exceder los :max caracteres.',
            'is_active.required'       => 'El :attribute es requerido.',
            'is_active.boolean'        => 'El :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'              => 'ID',
            'name'            => 'Nombre',
            'unit_measure_id' => 'Unidad de medida',
            'description'     => 'Descripción',
            'is_active'       => 'Estado',
        ];
    }
}
