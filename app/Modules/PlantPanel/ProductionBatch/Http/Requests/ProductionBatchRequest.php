<?php

namespace App\Modules\PlantPanel\ProductionBatch\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class ProductionBatchRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? 'NULL';
        return [
            'id'                    => ['nullable', 'integer'],
            'production_date'       => ['required', 'date'],
            'quantity_units'        => ['required', 'integer', 'min:1'],
            'status'                => ['required', 'in:in_production,maturing,ready,sold,rejected'],
            'presentation_id'       => ['required', 'integer', 'exists:dairy_product_presentations,id'],
            'maturation_start_date' => ['nullable', 'date'],
            'maturation_end_date'   => ['nullable', 'date', 'after_or_equal:maturation_start_date'],
            'observations'          => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'        => 'El campo :attribute es obligatorio.',
            'numeric'         => 'El campo :attribute debe ser un número.',
            'integer'         => 'El campo :attribute debe ser un entero.',
            'date'            => 'El campo :attribute debe ser una fecha válida.',
            'in'              => 'El campo :attribute tiene un valor no permitido.',
            'min'             => 'El campo :attribute no puede ser menor a :min.',
            'max'             => 'El campo :attribute no puede superar :max.',
            'exists'          => 'El registro relacionado no existe',
            'distinct'        => 'No puede repetir el mismo proveedor.',
            'after_or_equal'  => 'La fecha de fin debe ser igual o posterior al inicio.',
        ];
    }

    public function attributes(): array
    {
        return [
            'production_date'             => 'fecha de producción',
            'quantity_units'              => 'cantidad de unidades',
            'status'                      => 'estado',
            'presentation_id'             => 'presentación',
            'maturation_start_date'       => 'inicio de maduración',
            'maturation_end_date'         => 'fin de maduración',
        ];
    }
}
