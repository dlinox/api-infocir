<?php

namespace App\Modules\SupplierPanel\MilkRegistration\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class MilkRegistrationRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'                => ['nullable', 'integer'],
            'registration_date' => ['required', 'date'],
            'shift'             => ['required', 'in:morning,afternoon'],
            'quantity_liters'   => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'number_of_cows'    => ['nullable', 'integer', 'min:1', 'max:9999'],
            'observations'      => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'numeric'  => 'El campo :attribute debe ser un número.',
            'integer'  => 'El campo :attribute debe ser un entero.',
            'date'     => 'El campo :attribute debe ser una fecha válida.',
            'in'       => 'El campo :attribute tiene un valor no permitido.',
            'min'      => 'El campo :attribute no puede ser menor a :min.',
            'max'      => 'El campo :attribute no puede superar :max.',
        ];
    }

    public function attributes(): array
    {
        return [
            'registration_date' => 'fecha de registro',
            'shift'             => 'turno',
            'quantity_liters'   => 'cantidad (litros)',
            'number_of_cows'    => 'número de vacas',
            'observations'      => 'observaciones',
        ];
    }
}
