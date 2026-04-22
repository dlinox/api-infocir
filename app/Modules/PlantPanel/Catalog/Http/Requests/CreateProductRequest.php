<?php

namespace App\Modules\PlantPanel\Catalog\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class CreateProductRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name'            => 'required|string|max:100|unique:dairy_products,name',
            'description'     => 'nullable|string|max:255',
            'product_type_id' => 'nullable|integer|exists:dairy_product_types,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'El :attribute es requerido.',
            'name.string'            => 'El :attribute debe ser una cadena de texto.',
            'name.max'               => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'            => 'Ya existe un producto con ese nombre.',
            'description.string'     => 'La :attribute debe ser una cadena de texto.',
            'description.max'        => 'La :attribute no debe exceder los :max caracteres.',
            'product_type_id.exists' => 'El :attribute seleccionado no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'            => 'Nombre',
            'description'     => 'Descripción',
            'product_type_id' => 'Tipo de producto',
        ];
    }
}
