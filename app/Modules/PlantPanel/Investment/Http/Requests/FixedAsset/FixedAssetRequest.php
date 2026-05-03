<?php

namespace App\Modules\PlantPanel\Investment\Http\Requests\FixedAsset;

use App\Common\Http\Requests\ApiFormRequest;

class FixedAssetRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'                     => 'nullable|integer',
            'investment_category_id' => 'required|integer|exists:dairy_investment_categories,id',
            'name'                   => 'required|string|max:150',
            'purchase_date'          => 'required|date',
            'purchase_cost'          => 'required|numeric|min:0',
            'quantity'               => 'nullable|integer|min:1',
            'useful_life_years'      => 'nullable|integer|min:1|max:100',
            'depreciation_method'    => 'nullable|in:straight_line,declining_balance',
            'residual_value'         => 'nullable|numeric|min:0',
            'status'                 => 'nullable|in:active,maintenance,disposed',
            'serial_number'          => 'nullable|string|max:100',
            'notes'                  => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'investment_category_id' => 'Categoría',
            'name'                   => 'Nombre del activo',
            'purchase_date'          => 'Fecha de compra',
            'purchase_cost'          => 'Valor de compra',
            'quantity'               => 'Cantidad',
            'useful_life_years'      => 'Vida útil (años)',
            'depreciation_method'    => 'Método de depreciación',
            'residual_value'         => 'Valor residual',
            'status'                 => 'Estado',
            'serial_number'          => 'Serie / Placa',
            'notes'                  => 'Notas',
        ];
    }
}
