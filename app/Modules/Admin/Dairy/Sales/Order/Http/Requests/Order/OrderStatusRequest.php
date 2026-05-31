<?php

namespace App\Modules\Admin\Dairy\Sales\Order\Http\Requests\Order;

use App\Common\Http\Requests\ApiFormRequest;

class OrderStatusRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'status' => 'required|in:pending,contacted,closed,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'El :attribute es requerido.',
            'status.in'       => 'El :attribute no es válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'status' => 'Estado',
        ];
    }
}
