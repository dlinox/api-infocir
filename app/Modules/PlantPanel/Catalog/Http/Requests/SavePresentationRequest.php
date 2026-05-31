<?php

namespace App\Modules\PlantPanel\Catalog\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class SavePresentationRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'               => 'nullable|integer|exists:dairy_product_presentations,id',
            'plant_product_id' => 'required|integer|exists:dairy_plant_products,id',
            'name'             => 'required|string|max:100',
            'unit_measure_id'  => 'nullable|integer|exists:core_unit_measures,id',
            'content'          => 'required|numeric|min:0.001|max:9999999.999',
            'formula_items'                => 'nullable|array',
            'formula_items.*.supply_id'    => 'required|integer|exists:dairy_supplies,id',
            'formula_items.*.unit_measure_id' => 'nullable|integer|exists:core_unit_measures,id',
            'formula_items.*.quantity'     => 'required|numeric|min:0.001|max:9999999.999',
            'formula_items.*.unit_price'   => 'required|numeric|min:0.001|max:9999999.999',
            'price'            => 'nullable|numeric|min:0.01|max:9999999.99',
            'cost'             => 'nullable|numeric|min:0|max:9999999.99',
            'effective_from'   => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists'                    => 'La presentación seleccionada no existe.',
            'plant_product_id.required' => 'El :attribute es requerido.',
            'plant_product_id.exists'   => 'El :attribute seleccionado no existe.',
            'name.required'             => 'El :attribute es requerido.',
            'name.max'                  => 'El :attribute no debe exceder los :max caracteres.',
            'unit_measure_id.exists'    => 'La :attribute seleccionada no existe.',
            'content.required'          => 'El :attribute es requerido.',
            'content.min'               => 'El :attribute debe ser mayor a :min.',
            'formula_items.array'             => 'La fórmula debe ser una lista de insumos.',
            'formula_items.*.supply_id.required' => 'El insumo es requerido.',
            'formula_items.*.supply_id.exists'   => 'El insumo seleccionado no existe.',
            'formula_items.*.unit_measure_id.exists' => 'La unidad de medida seleccionada no existe.',
            'formula_items.*.quantity.required'  => 'La cantidad es requerida.',
            'formula_items.*.quantity.min'       => 'La cantidad debe ser mayor a 0.',
            'formula_items.*.unit_price.required' => 'El precio unitario es requerido.',
            'formula_items.*.unit_price.min'      => 'El precio unitario debe ser mayor a 0.',
            'price.min'                 => 'El precio de venta debe ser mayor a 0.',
            'effective_from.date'       => 'La fecha de vigencia debe ser una fecha válida.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'               => 'Presentación',
            'plant_product_id' => 'Producto',
            'name'             => 'Nombre',
            'unit_measure_id'  => 'Unidad de medida',
            'content'          => 'Contenido',
            'formula_items'    => 'Fórmula',
            'price'            => 'Precio de venta',
            'cost'             => 'Costo de producción',
            'effective_from'   => 'Fecha de vigencia',
        ];
    }
}
