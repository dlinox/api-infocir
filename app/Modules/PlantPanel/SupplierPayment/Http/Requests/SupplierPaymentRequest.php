<?php

namespace App\Modules\PlantPanel\SupplierPayment\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class SupplierPaymentRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'           => ['nullable', 'integer'],
            'supplier_id'  => ['required', 'integer', 'exists:dairy_suppliers,id'],
            'period_start' => ['required', 'date'],
            'period_end'   => ['required', 'date', 'after_or_equal:period_start'],
            'total_liters' => ['required', 'numeric', 'min:0'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'deductions'   => ['nullable', 'numeric', 'min:0'],
            'status'       => ['required', 'in:pending,approved,paid,cancelled'],
            'paid_at'      => ['nullable', 'date'],
            'observations' => ['nullable', 'string', 'max:1000'],
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
            'exists'          => 'El proveedor seleccionado no existe.',
            'after_or_equal'  => 'La fecha de fin debe ser igual o posterior al inicio.',
        ];
    }

    public function attributes(): array
    {
        return [
            'supplier_id'  => 'proveedor',
            'period_start' => 'inicio del período',
            'period_end'   => 'fin del período',
            'total_liters' => 'total de litros',
            'total_amount' => 'monto total',
            'deductions'   => 'descuentos',
            'status'       => 'estado',
            'paid_at'      => 'fecha de pago',
        ];
    }
}
