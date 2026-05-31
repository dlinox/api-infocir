<?php

namespace App\Modules\Admin\Security\Http\Requests\User;

use App\Common\Http\Requests\ApiFormRequest;

class AssignProfilesRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'profiles'                   => 'present|array',
            'profiles.*.id'              => 'nullable|integer',
            'profiles.*.role_id'         => 'required|integer|exists:behavior_roles,id',
            'profiles.*.core_profile_id' => 'required|integer|exists:core_profiles,id',
            'profiles.*.is_active'       => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'profiles.present'                  => 'Los :attribute son requeridos.',
            'profiles.array'                    => 'Los :attribute deben ser una lista.',
            'profiles.*.role_id.required'       => 'El rol es requerido.',
            'profiles.*.role_id.exists'         => 'El rol seleccionado no existe.',
            'profiles.*.core_profile_id.required' => 'El perfil es requerido.',
            'profiles.*.core_profile_id.exists' => 'El perfil seleccionado no existe.',
            'profiles.*.is_active.required'     => 'El estado del perfil es requerido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'profiles' => 'perfiles',
        ];
    }
}
