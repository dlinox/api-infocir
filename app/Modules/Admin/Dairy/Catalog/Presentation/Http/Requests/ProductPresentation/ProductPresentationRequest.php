<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Requests\ProductPresentation;

use App\Common\Http\Requests\ApiFormRequest;

class ProductPresentationRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'id'               => 'nullable|integer',
            'plant_product_id' => 'required|integer|exists:dairy_plant_products,id',
            'name'             => 'required|string|max:100',
            'unit_measure_id'  => 'nullable|integer|exists:core_unit_measures,id',
            'content'          => 'required|numeric|min:0.001|max:9999999.999',
            'is_active'        => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'plant_product_id.required' => 'El :attribute es requerido.',
            'plant_product_id.exists'   => 'El :attribute seleccionado no existe.',
            'name.required'             => 'El :attribute es requerido.',
            'name.string'               => 'El :attribute debe ser una cadena de texto.',
            'name.max'                  => 'El :attribute no debe exceder los :max caracteres.',
            'unit_measure_id.exists'    => 'La :attribute seleccionada no existe.',
            'content.required'          => 'El :attribute es requerido.',
            'content.numeric'           => 'El :attribute debe ser numérico.',
            'content.min'               => 'El :attribute debe ser mayor a :min.',
            'is_active.required'        => 'El :attribute es requerido.',
            'is_active.boolean'         => 'El :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'               => 'ID',
            'plant_product_id' => 'Producto de planta',
            'name'             => 'Nombre',
            'unit_measure_id'  => 'Unidad de medida',
            'content'          => 'Contenido',
            'is_active'        => 'Estado',
        ];
    }
}
