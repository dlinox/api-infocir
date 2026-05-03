<?php

namespace App\Modules\Admin\Setting\Http\Requests\Position;

use App\Common\Http\Requests\ApiFormRequest;

class PositionRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? 'NULL';
        return [
            'id'                      => 'nullable|integer',
            'name'                    => 'required|string|max:100|unique:dairy_positions,name,' . $id . ',id',
            'description'             => 'nullable|string|max:255',
            'entity_type'             => 'required|array|min:1',
            'entity_type.*'            => 'in:plant,supplier',
            'role_id'                 => 'nullable|integer|exists:behavior_roles,id',
            'investment_category_id'  => 'nullable|integer|exists:dairy_investment_categories,id',
            'is_active'               => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                     => 'El :attribute es requerido.',
            'name.string'                       => 'El :attribute debe ser una cadena de texto.',
            'name.max'                          => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'                       => 'El :attribute ya existe.',
            'description.string'                => 'La :attribute debe ser una cadena de texto.',
            'description.max'                   => 'La :attribute no debe exceder los :max caracteres.',
            'entity_type.required'              => 'El :attribute es requerido.',
            'entity_type.array'                 => 'El :attribute debe ser un arreglo.',
            'entity_type.min'                   => 'Debes seleccionar al menos un :attribute.',
            'entity_type.*.in'                  => 'Cada valor del :attribute debe ser planta o proveedor.',
            'role_id.integer'                   => 'El :attribute debe ser un número entero.',
            'role_id.exists'                    => 'El :attribute seleccionado no existe.',
            'investment_category_id.integer'    => 'La :attribute debe ser un número entero.',
            'investment_category_id.exists'     => 'La :attribute seleccionada no existe.',
            'is_active.required'                => 'El :attribute es requerido.',
            'is_active.boolean'                 => 'El :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'                     => 'ID',
            'name'                   => 'Nombre',
            'description'            => 'Descripción',
            'entity_type'            => 'tipo de entidad',
            'role_id'                => 'rol',
            'investment_category_id' => 'categoría de inversión',
            'is_active'              => 'Estado',
        ];
    }
}
