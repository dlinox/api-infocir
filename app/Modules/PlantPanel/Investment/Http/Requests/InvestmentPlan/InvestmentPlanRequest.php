<?php

namespace App\Modules\PlantPanel\Investment\Http\Requests\InvestmentPlan;

use App\Common\Http\Requests\ApiFormRequest;

class InvestmentPlanRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'                              => 'nullable|integer|exists:dairy_investment_plans,id',
            'name'                            => 'required|string|max:150',
            'period_year'                     => 'required|integer|min:2020|max:2099',
            'notes'                           => 'nullable|string|max:2000',
            'items'                           => 'array',
            'items.*.investment_category_id'  => 'required|integer|exists:dairy_investment_categories,id',
            'items.*.name'                    => 'required|string|max:150',
            'items.*.unit_value'              => 'required|numeric|min:0',
            'items.*.quantity'                => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'                            => 'El :attribute es obligatorio.',
            'name.max'                                 => 'El :attribute no debe exceder los :max caracteres.',
            'period_year.required'                     => 'El :attribute es obligatorio.',
            'period_year.min'                          => 'El :attribute debe ser mayor o igual a :min.',
            'period_year.max'                          => 'El :attribute debe ser menor o igual a :max.',
            'items.*.investment_category_id.required'  => 'La categoría de cada ítem es obligatoria.',
            'items.*.investment_category_id.exists'    => 'La categoría seleccionada no existe.',
            'items.*.name.required'                    => 'El nombre del ítem es obligatorio.',
            'items.*.unit_value.required'              => 'El valor unitario es obligatorio.',
            'items.*.unit_value.min'                   => 'El valor unitario no puede ser negativo.',
            'items.*.quantity.required'                => 'La cantidad es obligatoria.',
            'items.*.quantity.min'                     => 'La cantidad no puede ser negativa.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'nombre del plan',
            'period_year' => 'año',
            'notes'       => 'notas',
            'items'       => 'ítems',
        ];
    }
}
