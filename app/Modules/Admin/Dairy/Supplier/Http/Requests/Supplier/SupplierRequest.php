<?php

namespace App\Modules\Admin\Dairy\Supplier\Http\Requests\Supplier;

use App\Common\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends ApiFormRequest
{
    public function rules()
    {
        $personId = $this->person['id'] ?? 'NULL';
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
            'person.name'              => 'required|string|max:100',
            'person.paternal_surname'  => 'required|string|max:100',
            'person.maternal_surname'  => 'nullable|string|max:100',
            'person.date_birth'        => 'nullable|date',
            'person.cellphone'         => 'nullable|string|max:15',
            'person.email'             => 'nullable|email|max:100',
            'person.gender'            => 'nullable|string|max:5',
            'person.address'           => 'nullable|string|max:200',
            'person.city'              => 'nullable|string|max:6',
            'person.country'           => 'nullable|string|max:2',
            'supplier_type'            => ['required', Rule::in(['individual', 'company'])],
            'trade_name'               => 'nullable|string|max:100',
            'cellphone'                => 'required|string|max:9',
            'email'                    => 'nullable|email|max:100',
            'address'                  => 'nullable|string|max:200',
            'country'                  => 'nullable|string|max:2',
            'city'                     => 'nullable|string|max:6',
            'latitude'                 => 'nullable|numeric|between:-90,90',
            'longitude'                => 'nullable|numeric|between:-180,180',
            'description'              => 'nullable|string',
            'is_active'                => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'person.required'                      => 'Los datos personales son requeridos.',
            'person.document_type.required'        => 'El :attribute es requerido.',
            'person.document_number.required'      => 'El :attribute es requerido.',
            'person.document_number.size'          => 'El :attribute debe tener :size caracteres.',
            'person.name.required'                 => 'El :attribute es requerido.',
            'person.paternal_surname.required'     => 'El :attribute es requerido.',
            'person.email.email'                   => 'El :attribute debe ser un correo válido.',
            'supplier_type.required'               => 'El :attribute es requerido.',
            'supplier_type.in'                     => 'El :attribute seleccionado no es válido.',
            'cellphone.required'                   => 'El :attribute es requerido.',
            'cellphone.max'                        => 'El :attribute no debe tener más de :max caracteres.',
            'email.email'                          => 'El :attribute debe ser un correo válido.',
            'is_active.required'                   => 'El campo :attribute es requerido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'person.document_type'      => 'Tipo de documento',
            'person.document_number'    => 'Número de documento',
            'person.name'              => 'Nombre',
            'person.paternal_surname'  => 'Apellido paterno',
            'person.maternal_surname'  => 'Apellido materno',
            'person.date_birth'        => 'Fecha de nacimiento',
            'person.cellphone'         => 'Celular',
            'person.email'             => 'Email personal',
            'person.gender'            => 'Género',
            'person.address'           => 'Dirección personal',
            'person.city'              => 'Ciudad personal',
            'person.country'           => 'País personal',
            'supplier_type'            => 'Tipo de proveedor',
            'trade_name'               => 'Nombre comercial',
            'cellphone'                => 'Celular del proveedor',
            'email'                    => 'Email del proveedor',
            'address'                  => 'Dirección',
            'country'                  => 'País',
            'city'                     => 'Ciudad',
            'latitude'                 => 'Latitud',
            'longitude'                => 'Longitud',
            'description'              => 'Descripción',
            'is_active'                => 'Estado',
        ];
    }
}
