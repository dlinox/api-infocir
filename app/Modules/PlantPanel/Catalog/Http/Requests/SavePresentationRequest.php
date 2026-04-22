<?php

namespace App\Modules\PlantPanel\Catalog\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class SavePresentationRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'plant_product_id' => 'required|integer|exists:dairy_plant_products,id',
            'name'             => 'required|string|max:100',
            'unit_measure_id'  => 'nullable|integer|exists:core_unit_measures,id',
            'content'          => 'required|numeric|min:0.001|max:9999999.999',
        ];
    }

    public function messages(): array
    {
        return [
            'plant_product_id.required' => 'El :attribute es requerido.',
            'plant_product_id.exists'   => 'El :attribute seleccionado no existe.',
            'name.required'             => 'El :attribute es requerido.',
            'name.max'                  => 'El :attribute no debe exceder los :max caracteres.',
            'unit_measure_id.exists'    => 'La :attribute seleccionada no existe.',
            'content.required'          => 'El :attribute es requerido.',
            'content.min'               => 'El :attribute debe ser mayor a :min.',
        ];
    }

    public function attributes(): array
    {
        return [
            'plant_product_id' => 'Producto',
            'name'             => 'Nombre',
            'unit_measure_id'  => 'Unidad de medida',
            'content'          => 'Contenido',
        ];
    }
}
