<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Requests\ProductPrice;

use App\Common\Http\Requests\ApiFormRequest;

class ProductPriceRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'id' => 'nullable|integer',
            'presentation_id' => 'required|integer|exists:dairy_product_presentations,id',
            'price' => 'required|numeric|min:0.01|max:9999999.99',
            'cost' => 'nullable|numeric|min:0|max:9999999.99',
            'effective_from' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'presentation_id.required' => 'La :attribute es requerida.',
            'presentation_id.exists' => 'La :attribute seleccionada no existe.',
            'price.required' => 'El :attribute es requerido.',
            'price.min' => 'El :attribute debe ser mayor a 0.',
            'effective_from.required' => 'La :attribute es requerida.',
            'effective_from.date' => 'La :attribute debe ser una fecha válida.',
        ];
    }

    public function attributes(): array
    {
        return [
            'presentation_id' => 'Presentación',
            'price' => 'Precio de venta',
            'cost' => 'Costo de producción',
            'effective_from' => 'Fecha de vigencia',
        ];
    }
}
