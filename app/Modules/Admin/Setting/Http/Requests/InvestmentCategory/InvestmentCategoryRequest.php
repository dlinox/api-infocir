<?php

namespace App\Modules\Admin\Setting\Http\Requests\InvestmentCategory;

use App\Common\Http\Requests\ApiFormRequest;

class InvestmentCategoryRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? 'NULL';
        return [
            'id'                        => 'nullable|integer',
            'name'                      => 'required|string|max:100|unique:dairy_investment_categories,name,' . $id . ',id',
            'group'                     => 'required|in:fixed_asset,working_capital,pre_operative',
            'default_useful_life_years' => 'nullable|integer|min:1|max:100',
            'default_validity_years'    => 'nullable|integer|min:1|max:100',
            'hint'                      => 'nullable|string|max:255',
            'sort_order'                => 'nullable|integer|min:0',
            'is_active'                 => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'El :attribute es requerido.',
            'name.string'    => 'El :attribute debe ser una cadena de texto.',
            'name.max'       => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'    => 'El :attribute ya existe.',
            'group.required' => 'El :attribute es requerido.',
            'group.in'       => 'El :attribute no es válido.',
            'sort_order.integer' => 'El :attribute debe ser un número entero.',
            'sort_order.min'     => 'El :attribute debe ser mayor o igual a 0.',
            'is_active.required' => 'El :attribute es requerido.',
            'is_active.boolean'  => 'El :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'                        => 'ID',
            'name'                      => 'Nombre',
            'group'                     => 'Grupo',
            'default_useful_life_years' => 'Vida útil predeterminada (años)',
            'default_validity_years'    => 'Vigencia predeterminada (años)',
            'hint'                      => 'Ayuda al usuario',
            'sort_order'                => 'Orden',
            'is_active'                 => 'Estado',
        ];
    }
}
