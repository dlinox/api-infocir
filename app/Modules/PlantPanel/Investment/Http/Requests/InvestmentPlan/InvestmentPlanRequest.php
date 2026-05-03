<?php

namespace App\Modules\PlantPanel\Investment\Http\Requests\InvestmentPlan;

use App\Common\Http\Requests\ApiFormRequest;

class InvestmentPlanRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'                              => 'nullable|integer|exists:dairy_investment_plans,id',
            'period_year'                     => 'required|integer|min:2020|max:2099',
            'period_month'                    => 'required|integer|min:1|max:12',
            'notes'                           => 'nullable|string|max:2000',
            'items'                           => 'array',
            'items.*.investment_category_id'  => 'required|integer|exists:dairy_investment_categories,id',
            'items.*.name'                    => 'required|string|max:150',
            'items.*.recurrence_type'         => 'nullable|in:one_time,monthly,annual',
            'items.*.unit_value'              => 'required|numeric|min:0',
            'items.*.quantity'                => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'plan_type.required'                       => 'El tipo de registro es obligatorio.',
            'plan_type.in'                             => 'El tipo de registro no es válido.',
            'period_year.required'                     => 'El :attribute es obligatorio.',
            'period_year.min'                          => 'El :attribute debe ser mayor o igual a :min.',
            'period_year.max'                          => 'El :attribute debe ser menor o igual a :max.',
            'period_month.min'                         => 'El mes debe estar entre 1 y 12.',
            'period_month.max'                         => 'El mes debe estar entre 1 y 12.',
            'items.*.investment_category_id.required'  => 'La categoría de cada ítem es obligatoria.',
            'items.*.investment_category_id.exists'    => 'La categoría seleccionada no existe.',
            'items.*.name.required'                    => 'El nombre del ítem es obligatorio.',
            'items.*.recurrence_type.in'               => 'El tipo de recurrencia no es válido.',
            'items.*.unit_value.required'              => 'El valor unitario es obligatorio.',
            'items.*.unit_value.min'                   => 'El valor unitario no puede ser negativo.',
            'items.*.quantity.required'                => 'La cantidad es obligatoria.',
            'items.*.quantity.min'                     => 'La cantidad no puede ser negativa.',
        ];
    }

    public function attributes(): array
    {
        return [
            'plan_type'    => 'tipo de registro',
            'period_year'  => 'año',
            'period_month' => 'mes',
            'notes'        => 'notas',
            'items'        => 'ítems',
        ];
    }
}
