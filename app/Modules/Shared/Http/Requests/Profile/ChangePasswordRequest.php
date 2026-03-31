<?php

namespace App\Modules\Shared\Http\Requests\Profile;

use App\Common\Http\Requests\ApiFormRequest;

class ChangePasswordRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'current_password'          => ['required', 'string'],
            'new_password'              => ['required', 'string', 'min:8', 'max:20', 'confirmed'],
            'new_password_confirmation' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required'          => 'La contraseña actual es requerida',
            'new_password.required'              => 'La nueva contraseña es requerida',
            'new_password.min'                   => 'La nueva contraseña debe tener mínimo 8 caracteres',
            'new_password.max'                   => 'La nueva contraseña debe tener máximo 20 caracteres',
            'new_password.confirmed'             => 'Las contraseñas no coinciden',
            'new_password_confirmation.required' => 'La confirmación de contraseña es requerida',
        ];
    }

    public function attributes(): array
    {
        return [
            'current_password'          => 'Contraseña actual',
            'new_password'              => 'Nueva contraseña',
            'new_password_confirmation' => 'Confirmar contraseña',
        ];
    }
}
