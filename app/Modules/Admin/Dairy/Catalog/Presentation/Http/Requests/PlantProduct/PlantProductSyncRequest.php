<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Requests\PlantProduct;

use App\Common\Http\Requests\ApiFormRequest;

class PlantProductSyncRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'plant_id'      => 'required|integer|exists:dairy_plants,id',
            'product_ids'   => 'required|array',
            'product_ids.*' => 'integer|exists:dairy_products,id',
        ];
    }

    public function messages(): array
    {
        return [
            'plant_id.required'      => 'La :attribute es requerida.',
            'plant_id.exists'        => 'La :attribute seleccionada no existe.',
            'product_ids.required'   => 'Debe seleccionar al menos un :attribute.',
            'product_ids.array'      => 'Los :attribute deben ser una lista.',
            'product_ids.*.exists'   => 'Uno de los :attribute seleccionados no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'plant_id'    => 'Planta',
            'product_ids' => 'Productos',
        ];
    }
}
