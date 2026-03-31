<?php

namespace App\Modules\Admin\Setting\Http\Requests\TrainingLevel;

use App\Common\Http\Requests\ApiFormRequest;

class TrainingLevelRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? 'NULL';
        return [
            'id'        => 'nullable|integer',
            'name'      => 'required|string|max:100|unique:dairy_training_levels,name,' . $id . ',id',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'El :attribute es requerido.',
            'name.string'        => 'El :attribute debe ser una cadena de texto.',
            'name.max'           => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'        => 'El :attribute ya existe.',
            'is_active.required' => 'El :attribute es requerido.',
            'is_active.boolean'  => 'El :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'        => 'ID',
            'name'      => 'Nombre',
            'is_active' => 'Estado',
        ];
    }
}
