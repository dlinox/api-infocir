<?php

namespace App\Modules\Admin\Setting\Http\Requests\WorkingCapitalCatalog;

use App\Common\Http\Requests\ApiFormRequest;

class WorkingCapitalCatalogRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? 'NULL';
        return [
            'id'                     => 'nullable|integer',
            'investment_category_id' => 'required|integer|exists:dairy_investment_categories,id',
            'unit_measure_id'        => 'nullable|integer|exists:core_unit_measures,id',
            'name'                   => 'required|string|max:100|unique:dairy_working_capital_catalog,name,' . $id . ',id',
            'description'            => 'nullable|string|max:255',
            'is_active'              => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'investment_category_id.required' => 'La :attribute es requerida.',
            'investment_category_id.exists'   => 'La :attribute seleccionada no existe.',
            'unit_measure_id.exists'          => 'La :attribute seleccionada no existe.',
            'name.required'                   => 'El :attribute es requerido.',
            'name.max'                        => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'                     => 'El :attribute ya existe.',
            'description.max'                 => 'La :attribute no debe exceder los :max caracteres.',
            'is_active.required'              => 'El :attribute es requerido.',
            'is_active.boolean'               => 'El :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'                     => 'ID',
            'investment_category_id' => 'Categoría de inversión',
            'unit_measure_id'        => 'Unidad de medida',
            'name'                   => 'Nombre',
            'description'            => 'Descripción',
            'is_active'              => 'Estado',
        ];
    }
}
