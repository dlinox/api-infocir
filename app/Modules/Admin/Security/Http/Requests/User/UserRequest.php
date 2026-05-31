<?php

namespace App\Modules\Admin\Security\Http\Requests\User;

use App\Common\Http\Requests\ApiFormRequest;

class UserRequest extends ApiFormRequest
{
    public function rules()
    {
        $id        = $this->id ?? 'NULL';
        $isCreate  = empty($this->id);

        return [
            'id'        => 'nullable|integer',
            'username'  => 'required|string|max:50|unique:auth_users,username,' . $id . ',id',
            'email'     => 'nullable|email|max:100|unique:auth_users,email,' . $id . ',id',
            'password'  => ($isCreate ? 'required' : 'nullable') . '|string|min:6|max:255',
            'is_active' => 'required|boolean',
            'person_id' => ($isCreate ? 'required' : 'nullable') . '|integer|exists:core_persons,id',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'El :attribute es requerido.',
            'username.max'      => 'El :attribute no debe exceder los :max caracteres.',
            'username.unique'   => 'El :attribute ya existe.',
            'email.email'       => 'El :attribute debe ser un correo válido.',
            'email.unique'      => 'El :attribute ya existe.',
            'password.required' => 'La :attribute es requerida.',
            'password.min'      => 'La :attribute debe tener al menos :min caracteres.',
            'is_active.required' => 'El :attribute es requerido.',
            'is_active.boolean'  => 'El :attribute debe ser verdadero o falso.',
            'person_id.required' => 'La :attribute es requerida.',
            'person_id.exists'   => 'La :attribute seleccionada no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'        => 'ID',
            'username'  => 'Usuario',
            'email'     => 'Correo',
            'password'  => 'Contraseña',
            'is_active' => 'Estado',
            'person_id' => 'Persona',
        ];
    }
}
