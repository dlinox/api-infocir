<?php

namespace App\Modules\Auth\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class SignInRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'identifier' => ['required', 'string', 'max:100'],
            'password' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'identifier.required' => 'El usuario o correo es requerido',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'identifier' => 'usuario o correo',
            'password' => 'contraseña',
        ];
    }
}
