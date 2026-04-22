<?php

namespace App\Modules\PlantPanel\Catalog\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class AddProductRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:dairy_products,id',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'El :attribute es requerido.',
            'product_id.integer'  => 'El :attribute debe ser un número entero.',
            'product_id.exists'   => 'El :attribute seleccionado no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'product_id' => 'Producto',
        ];
    }
}
