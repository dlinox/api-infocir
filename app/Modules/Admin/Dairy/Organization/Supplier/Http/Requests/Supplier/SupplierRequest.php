<?php

namespace App\Modules\Admin\Dairy\Organization\Supplier\Http\Requests\Supplier;

use App\Common\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends ApiFormRequest
{
    public function rules()
    {
        $supplierId = $this->id ?? 'NULL';

        return [
            'id'              => 'nullable|integer',
            'supplier_type'   => ['required', Rule::in(['individual', 'company'])],
            'document_type'   => 'required|string|max:1',
            'document_number' => 'required|string|max:20',
            'name'            => ['required', 'string', 'max:100', Rule::unique('dairy_suppliers', 'name')->ignore($supplierId)],
            'trade_name'      => 'nullable|string|max:100',
            'cellphone'       => ['nullable', 'string', 'max:9', Rule::unique('dairy_suppliers', 'cellphone')->ignore($supplierId)],
            'email'           => ['nullable', 'email', 'max:100', Rule::unique('dairy_suppliers', 'email')->ignore($supplierId)],
            'address'         => 'nullable|string|max:200',
            'city'            => 'nullable|string|max:6',
            'latitude'        => 'nullable|numeric|between:-90,90',
            'longitude'       => 'nullable|numeric|between:-180,180',
            'description'     => 'nullable|string',
            'is_active'       => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_type.required'   => 'El :attribute es requerido.',
            'supplier_type.in'         => 'El :attribute seleccionado no es válido.',
            'document_type.required'   => 'El :attribute es requerido.',
            'document_number.required' => 'El :attribute es requerido.',
            'name.required'            => 'El :attribute es requerido.',
            'name.unique'              => 'El :attribute ya está en uso.',
            'cellphone.unique'         => 'El :attribute ya está en uso.',
            'email.email'              => 'El :attribute debe ser un correo válido.',
            'email.unique'             => 'El :attribute ya está en uso.',
            'is_active.required'       => 'El campo :attribute es requerido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'supplier_type'   => 'Tipo de proveedor',
            'document_type'   => 'Tipo de documento',
            'document_number' => 'Número de documento',
            'name'            => 'Nombre / Razón social',
            'trade_name'      => 'Nombre comercial',
            'cellphone'       => 'Celular',
            'email'           => 'Email',
            'address'         => 'Dirección',
            'city'            => 'Ciudad',
            'latitude'        => 'Latitud',
            'longitude'       => 'Longitud',
            'description'     => 'Descripción',
            'is_active'       => 'Estado',
        ];
    }
}

