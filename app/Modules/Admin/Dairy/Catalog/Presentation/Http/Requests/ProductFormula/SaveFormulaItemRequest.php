<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Requests\ProductFormula;

use App\Common\Http\Requests\ApiFormRequest;

class SaveFormulaItemRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'id' => 'nullable|integer|exists:dairy_product_formulas,id',
            'presentation_id' => 'required|integer|exists:dairy_product_presentations,id',
            'supply_id' => 'required|integer|exists:dairy_supplies,id',
            'quantity' => 'required|numeric|min:0.001|max:9999999.999',
            'unit_price' => 'required|numeric|min:0.001|max:9999999.999',
        ];
    }

    public function messages(): array
    {
        return [
            'presentation_id.required' => 'La :attribute es requerida.',
            'presentation_id.exists' => 'La :attribute seleccionada no existe.',
            'supply_id.required' => 'El :attribute es requerido.',
            'supply_id.exists' => 'El :attribute seleccionado no existe.',
            'quantity.required' => 'La :attribute es requerida.',
            'quantity.min' => 'La :attribute debe ser mayor a 0.',
            'unit_price.required' => 'El :attribute es requerido.',
            'unit_price.min' => 'El :attribute debe ser mayor a 0.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'presentation_id' => 'Presentación',
            'supply_id' => 'Insumo',
            'quantity' => 'Cantidad',
            'unit_price' => 'Precio unitario',
        ];
    }
}
