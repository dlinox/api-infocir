<?php

namespace App\Modules\PlantPanel\WorkerPayment\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class WorkerPaymentRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'              => ['nullable', 'integer'],
            'worker_person_id' => ['required', 'integer', 'exists:dairy_workers,person_id'],
            'period_year'     => ['required', 'integer', 'min:2000', 'max:2200'],
            'period_month'    => ['required', 'integer', 'min:1', 'max:12'],
            'base_salary'     => ['required', 'numeric', 'min:0'],
            'bonuses'         => ['nullable', 'numeric', 'min:0'],
            'deductions'      => ['nullable', 'numeric', 'min:0'],
            'net_amount'      => ['required', 'numeric', 'min:0'],
            'status'          => ['required', 'in:pending,paid,cancelled'],
            'paid_at'         => ['nullable', 'date'],
            'observations'    => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'numeric' => 'El campo :attribute debe ser un número.',
            'integer' => 'El campo :attribute debe ser un entero.',
            'date' => 'El campo :attribute debe ser una fecha válida.',
            'in' => 'El campo :attribute tiene un valor no permitido.',
            'min' => 'El campo :attribute no puede ser menor a :min.',
            'max' => 'El campo :attribute no puede ser mayor a :max.',
            'exists' => 'El trabajador seleccionado no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'worker_person_id' => 'trabajador',
            'period_year' => 'año del período',
            'period_month' => 'mes del período',
            'base_salary' => 'salario base',
            'bonuses' => 'bonificaciones',
            'deductions' => 'descuentos',
            'net_amount' => 'monto neto',
            'status' => 'estado',
            'paid_at' => 'fecha de pago',
        ];
    }
}
