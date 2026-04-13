<?php

namespace App\Modules\Admin\Dairy\Organization\Worker\Http\Requests\Worker;

use App\Common\Http\Requests\ApiFormRequest;

class WorkerRequest extends ApiFormRequest
{
    public function rules()
    {
        $documentType = $this->person['document_type'] ?? null;

        $documentNumberRules = ['required', 'string'];
        if ($documentType === '1') {
            $documentNumberRules[] = 'size:8';
        } elseif ($documentType === '6') {
            $documentNumberRules[] = 'size:11';
        } else {
            $documentNumberRules[] = 'max:20';
        }

        return [
            'person'                    => 'required|array',
            'person.id'                 => 'nullable|integer',
            'person.document_type'      => 'required|string|max:5',
            'person.document_number'    => $documentNumberRules,
            'person.name'               => 'required|string|max:100',
            'person.paternal_surname'   => 'required|string|max:100',
            'person.maternal_surname'   => 'nullable|string|max:100',
            'person.date_birth'         => 'nullable|date',
            'person.cellphone'          => 'nullable|string|max:15',
            'person.email'              => 'nullable|email|max:100',
            'person.gender'             => 'nullable|string|max:5',
            'person.address'            => 'nullable|string|max:200',
            'person.city'               => 'nullable|string|max:6',
            'entity_id'                 => 'required|integer|exists:core_entities,id',
            'role_id'                   => 'nullable|integer|exists:behavior_roles,id',
            'position_id'               => 'nullable|integer|exists:dairy_positions,id',
            'instruction_degree_id'     => 'nullable|integer|exists:core_instruction_degrees,id',
            'profession_id'             => 'nullable|integer|exists:core_professions,id',
            'is_active'                 => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'person.required'                  => 'Los datos personales son requeridos.',
            'person.document_type.required'    => 'El :attribute es requerido.',
            'person.document_number.required'  => 'El :attribute es requerido.',
            'person.document_number.size'      => 'El :attribute debe tener :size caracteres.',
            'person.name.required'             => 'El :attribute es requerido.',
            'person.paternal_surname.required' => 'El :attribute es requerido.',
            'person.email.email'               => 'El :attribute debe ser un correo válido.',
            'entity_id.required'               => 'La :attribute es requerida.',
            'entity_id.exists'                 => 'La :attribute seleccionada no existe.',
            'role_id.exists'                   => 'El :attribute seleccionado no existe.',
            'position_id.exists'               => 'El :attribute seleccionado no existe.',
            'instruction_degree_id.exists'     => 'El :attribute seleccionado no existe.',
            'profession_id.exists'             => 'La :attribute seleccionada no existe.',
            'is_active.required'               => 'El campo :attribute es requerido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'person.document_type'     => 'Tipo de documento',
            'person.document_number'   => 'Número de documento',
            'person.name'              => 'Nombre',
            'person.paternal_surname'  => 'Apellido paterno',
            'person.maternal_surname'  => 'Apellido materno',
            'person.date_birth'        => 'Fecha de nacimiento',
            'person.cellphone'         => 'Celular',
            'person.email'             => 'Email',
            'person.gender'            => 'Género',
            'person.address'           => 'Dirección',
            'person.city'              => 'Ciudad',
            'entity_id'                => 'entidad',
            'role_id'                  => 'rol',
            'position_id'              => 'Cargo',
            'instruction_degree_id'    => 'Grado de instrucción',
            'profession_id'            => 'Profesión',
            'is_active'                => 'Estado',
        ];
    }
}

