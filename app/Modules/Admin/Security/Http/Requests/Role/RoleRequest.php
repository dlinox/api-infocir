<?php

namespace App\Modules\Admin\Security\Http\Requests\Role;

use App\Common\Http\Requests\ApiFormRequest;

class RoleRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? 'NULL';

        return [
            'id'             => 'nullable|integer',
            'name'           => 'required|string|max:50|regex:/^[a-z0-9_]+$/|unique:behavior_roles,name,' . $id . ',id',
            'display_name'   => 'required|string|max:100|unique:behavior_roles,display_name,' . $id . ',id',
            'level'          => 'required|integer|between:0,4',
            'scope'          => 'required|string|in:admin,plant,supplier,worker,instructor',
            'is_active'      => 'required|boolean',
            'redirect_to'    => 'nullable|string|max:255',
            'permission_ids' => 'nullable|array',
            'permission_ids.*' => 'integer|exists:behavior_permissions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'El :attribute es requerido.',
            'name.string'        => 'El :attribute debe ser una cadena de texto.',
            'name.max'           => 'El :attribute no debe exceder los :max caracteres.',
            'name.regex'         => 'El :attribute solo admite minúsculas, números y guiones bajos.',
            'name.unique'        => 'El :attribute ya existe.',
            'display_name.required' => 'El :attribute es requerido.',
            'display_name.string'   => 'El :attribute debe ser una cadena de texto.',
            'display_name.max'      => 'El :attribute no debe exceder los :max caracteres.',
            'display_name.unique'   => 'El :attribute ya existe.',
            'level.required'     => 'El :attribute es requerido.',
            'level.integer'      => 'El :attribute debe ser un número entero.',
            'level.between'      => 'El :attribute debe estar entre :min y :max.',
            'scope.required'     => 'El :attribute es requerido.',
            'scope.in'           => 'El :attribute seleccionado no es válido.',
            'is_active.required' => 'El :attribute es requerido.',
            'is_active.boolean'  => 'El :attribute debe ser verdadero o falso.',
            'permission_ids.array'    => 'Los :attribute deben ser una lista.',
            'permission_ids.*.exists' => 'Uno de los permisos seleccionados no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'             => 'ID',
            'name'           => 'Código',
            'display_name'   => 'Nombre',
            'level'          => 'Nivel',
            'scope'          => 'Ámbito',
            'is_active'      => 'Estado',
            'redirect_to'    => 'Ruta de redirección',
            'permission_ids' => 'Permisos',
        ];
    }
}
