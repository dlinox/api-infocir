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
            'batch_code'            => ['required', 'string', 'max:30', 'unique:dairy_production_batches,batch_code,' . $id . ',id'],
            'production_date'       => ['required', 'date'],
            'quantity_liters_used'  => ['required', 'numeric', 'min:0.01'],
            'quantity_kg'           => ['required', 'numeric', 'min:0.01'],
            'status'                => ['required', 'in:in_production,maturing,ready,sold,rejected'],
            'presentation_id'       => ['nullable', 'integer', 'exists:dairy_product_presentations,id'],
            'maturation_start_date' => ['nullable', 'date'],
            'maturation_end_date'   => ['nullable', 'date', 'after_or_equal:maturation_start_date'],
            'observations'          => ['nullable', 'string', 'max:1000'],

            'suppliers'                       => ['required', 'array', 'min:1'],
            'suppliers.*.supplier_id'         => ['required', 'integer', 'exists:dairy_suppliers,id', 'distinct'],
            'suppliers.*.quantity_liters'     => ['required', 'numeric', 'min:0.01'],
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
            'unique'          => 'El código de lote ya está en uso.',
            'exists'          => 'El registro relacionado no existe.',
            'distinct'        => 'No puede repetir el mismo proveedor.',
            'after_or_equal'  => 'La fecha de fin debe ser igual o posterior al inicio.',
            'suppliers.min'   => 'Debe agregar al menos un proveedor.',
        ];
    }

    public function attributes(): array
    {
        return [
            'batch_code'                  => 'código del lote',
            'production_date'             => 'fecha de producción',
            'quantity_liters_used'        => 'litros utilizados',
            'quantity_kg'                 => 'producción (kg)',
            'status'                      => 'estado',
            'presentation_id'             => 'presentación',
            'maturation_start_date'       => 'inicio de maduración',
            'maturation_end_date'         => 'fin de maduración',
            'suppliers'                   => 'proveedores',
            'suppliers.*.supplier_id'     => 'proveedor',
            'suppliers.*.quantity_liters' => 'litros del proveedor',
        ];
    }
}
