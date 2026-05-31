<?php

namespace App\Modules\Admin\Security\Http\Requests\Permission;

use App\Common\Http\Requests\ApiFormRequest;

class PermissionRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? 'NULL';

        return [
            'id'           => 'nullable|integer',
            'name'         => 'required|string|max:50|regex:/^[a-z0-9_.:-]+$/|unique:behavior_permissions,name,' . $id . ',id',
            'display_name' => 'required|string|max:100',
            'type'         => 'required|string|in:module,menu,view,action,feature',
            'parent_id'    => 'nullable|integer|exists:behavior_permissions,id',
            'level'        => 'required|integer|between:0,4',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'El :attribute es requerido.',
            'name.string'           => 'El :attribute debe ser una cadena de texto.',
            'name.max'              => 'El :attribute no debe exceder los :max caracteres.',
            'name.regex'            => 'El :attribute solo admite minúsculas, números y los signos . : - _',
            'name.unique'           => 'El :attribute ya existe.',
            'display_name.required' => 'El :attribute es requerido.',
            'display_name.max'      => 'El :attribute no debe exceder los :max caracteres.',
            'type.required'         => 'El :attribute es requerido.',
            'type.in'               => 'El :attribute seleccionado no es válido.',
            'parent_id.exists'      => 'El :attribute seleccionado no existe.',
            'level.required'        => 'El :attribute es requerido.',
            'level.integer'         => 'El :attribute debe ser un número entero.',
            'level.between'         => 'El :attribute debe estar entre :min y :max.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'           => 'ID',
            'name'         => 'Código',
            'display_name' => 'Nombre',
            'type'         => 'Tipo',
            'parent_id'    => 'Permiso padre',
            'level'        => 'Nivel',
        ];
    }
}
