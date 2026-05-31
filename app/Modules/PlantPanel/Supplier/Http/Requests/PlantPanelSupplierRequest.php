<?php

namespace App\Modules\PlantPanel\Supplier\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class PlantPanelSupplierRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'supplier_type'   => ['required', Rule::in(['individual', 'company'])],
            'document_type'   => ['required', 'string', 'max:1'],
            'document_number' => ['required', 'string', 'max:20'],
            'name'            => ['required', 'string', 'max:100', Rule::unique('dairy_suppliers', 'name')],
            'trade_name'      => ['nullable', 'string', 'max:100'],
            'cellphone'       => ['nullable', 'string', 'size:9', Rule::unique('dairy_suppliers', 'cellphone')],
            'email'           => ['nullable', 'email', 'max:100', Rule::unique('dairy_suppliers', 'email')],
            'address'         => ['nullable', 'string', 'max:200'],
            'community'       => ['nullable', 'string', 'max:100'],
            'is_active'       => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'required'      => 'El campo :attribute es obligatorio.',
            'string'        => 'El campo :attribute debe ser texto.',
            'max'           => 'El campo :attribute no debe exceder :max caracteres.',
            'size'          => 'El campo :attribute debe tener :size caracteres.',
            'boolean'       => 'El campo :attribute debe ser verdadero o falso.',
            'email'         => 'El campo :attribute debe ser un correo válido.',
            'unique'        => 'El campo :attribute ya está en uso.',
            'supplier_type.in' => 'El tipo de proveedor seleccionado no es válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'supplier_type'   => 'tipo de proveedor',
            'document_type'   => 'tipo de documento',
            'document_number' => 'número de documento',
            'name'            => 'nombre',
            'trade_name'      => 'nombre comercial',
            'cellphone'       => 'celular',
            'email'           => 'correo',
            'address'         => 'dirección',
            'community'       => 'comunidad',
            'is_active'       => 'estado',
        ];
    }
}
