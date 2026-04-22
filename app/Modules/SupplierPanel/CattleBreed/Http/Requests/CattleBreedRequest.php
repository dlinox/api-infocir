<?php

namespace App\Modules\SupplierPanel\CattleBreed\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class CattleBreedRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'         => ['nullable', 'integer'],
            'breed_name' => ['required', 'string', 'max:100'],
            'count'      => ['nullable', 'integer', 'min:0', 'max:9999'],
            'notes'      => ['nullable', 'string', 'max:1000'],
            'is_active'  => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string'   => 'El campo :attribute debe ser texto.',
            'integer'  => 'El campo :attribute debe ser un número entero.',
            'max'      => 'El campo :attribute no puede superar :max caracteres.',
            'min'      => 'El campo :attribute no puede ser menor a :min.',
            'boolean'  => 'El campo :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'breed_name' => 'nombre de raza',
            'count'      => 'cantidad de vacas',
            'notes'      => 'notas',
            'is_active'  => 'estado',
        ];
    }
}
