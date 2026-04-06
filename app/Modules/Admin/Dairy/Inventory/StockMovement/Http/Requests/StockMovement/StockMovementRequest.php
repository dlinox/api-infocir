<?php

namespace App\Modules\Admin\Dairy\Inventory\StockMovement\Http\Requests\StockMovement;

use App\Common\Http\Requests\ApiFormRequest;

class StockMovementRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'presentation_id' => 'required|integer|exists:dairy_product_presentations,id',
            'plant_id' => 'required|integer|exists:dairy_plants,id',
            'type' => 'required|in:entry,exit,adjustment,loss',
            'quantity' => 'required|integer|min:1',
            'batch_code' => 'nullable|string|max:30',
            'expiration_date' => 'nullable|date',
            'reason' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'presentation_id.required' => 'La :attribute es requerida.',
            'plant_id.required' => 'La :attribute es requerida.',
            'type.required' => 'El :attribute es requerido.',
            'type.in' => 'El :attribute debe ser: entrada, salida, ajuste o merma.',
            'quantity.required' => 'La :attribute es requerida.',
            'quantity.min' => 'La :attribute debe ser al menos 1.',
        ];
    }

    public function attributes(): array
    {
        return [
            'presentation_id' => 'Presentación',
            'plant_id' => 'Planta',
            'type' => 'Tipo de movimiento',
            'quantity' => 'Cantidad',
            'batch_code' => 'Código de lote',
            'expiration_date' => 'Fecha de vencimiento',
            'reason' => 'Motivo',
        ];
    }
}
