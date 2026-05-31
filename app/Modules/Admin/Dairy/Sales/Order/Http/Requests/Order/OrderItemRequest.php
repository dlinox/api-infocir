<?php

namespace App\Modules\Admin\Dairy\Sales\Order\Http\Requests\Order;

use App\Common\Http\Requests\ApiFormRequest;

class OrderItemRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'presentation_id' => 'nullable|integer|exists:dairy_product_presentations,id',
            'quantity'        => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'La :attribute es requerida.',
            'quantity.integer'  => 'La :attribute debe ser un número.',
            'quantity.min'      => 'La :attribute no puede ser negativa.',
        ];
    }

    public function attributes(): array
    {
        return [
            'presentation_id' => 'presentación',
            'quantity'        => 'cantidad',
        ];
    }
}
