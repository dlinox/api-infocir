<?php

namespace App\Modules\Admin\Security\Http\Requests\User;

use App\Common\Http\Requests\ApiFormRequest;

class ResetPasswordRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'password' => 'required|string|min:6|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'La :attribute es requerida.',
            'password.min'      => 'La :attribute debe tener al menos :min caracteres.',
            'password.max'      => 'La :attribute no debe exceder los :max caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'password' => 'contraseña',
        ];
    }
}
