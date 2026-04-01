<?php

namespace App\Modules\Shared\Http\Requests\Profile;

use App\Common\Http\Requests\ApiFormRequest;

class UpdatePersonalRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name'              => ['required', 'string', 'max:100'],
            'paternal_surname'  => ['nullable', 'string', 'max:80'],
            'maternal_surname'  => ['nullable', 'string', 'max:80'],
            'date_birth'        => ['nullable', 'date'],
            'cellphone'         => ['nullable', 'string', 'size:9'],
            'email'             => ['nullable', 'email', 'max:100'],
            'gender'            => ['nullable', 'string', 'size:1'],
            'address'           => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'             => 'El nombre es requerido',
            'name.max'                  => 'El nombre debe tener máximo 100 caracteres',
            'paternal_surname.max'      => 'El apellido paterno debe tener máximo 80 caracteres',
            'maternal_surname.max'      => 'El apellido materno debe tener máximo 80 caracteres',
            'date_birth.date'           => 'La fecha de nacimiento es inválida',
            'cellphone.size'            => 'El celular debe tener exactamente :size caracteres',
            'email.email'               => 'El correo personal es inválido',
            'email.max'                 => 'El correo personal debe tener máximo 100 caracteres',
            'address.max'               => 'La dirección debe tener máximo 255 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'             => 'Nombre',
            'paternal_surname' => 'Apellido paterno',
            'maternal_surname' => 'Apellido materno',
            'date_birth'       => 'Fecha de nacimiento',
            'cellphone'        => 'Celular',
            'email'            => 'Correo personal',
            'gender'           => 'Género',
            'address'          => 'Dirección',
        ];
    }
}
