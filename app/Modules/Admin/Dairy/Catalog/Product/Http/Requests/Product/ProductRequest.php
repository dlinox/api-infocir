<?php

namespace App\Modules\Admin\Dairy\Catalog\Product\Http\Requests\Product;

use App\Common\Http\Requests\ApiFormRequest;

class ProductRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? 'NULL';
        return [
            'id'              => 'nullable|integer',
            'name'            => 'required|string|max:100|unique:dairy_products,name,' . $id . ',id',
            'description'     => 'nullable|string|max:255',
            'product_type_id' => 'nullable|integer|exists:dairy_product_types,id',
            'created_by'      => 'nullable|integer|exists:auth_users,id',
            'is_active'       => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'El :attribute es requerido.',
            'name.string'            => 'El :attribute debe ser una cadena de texto.',
            'name.max'               => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'            => 'El :attribute ya existe.',
            'description.string'     => 'La :attribute debe ser una cadena de texto.',
            'description.max'        => 'La :attribute no debe exceder los :max caracteres.',
            'product_type_id.exists' => 'El :attribute seleccionado no existe.',
            'created_by.exists'      => 'El :attribute seleccionado no existe.',
            'is_active.required'     => 'El :attribute es requerido.',
            'is_active.boolean'      => 'El :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'              => 'ID',
            'name'            => 'Nombre',
            'description'     => 'Descripción',
            'product_type_id' => 'Tipo de producto',
            'created_by'      => 'Creado por',
            'is_active'       => 'Estado',
        ];
    }
}
