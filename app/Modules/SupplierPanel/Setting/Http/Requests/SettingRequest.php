<?php

namespace App\Modules\SupplierPanel\Setting\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class SettingRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'supplier_type'             => ['nullable', 'in:individual,company'],
            'document_type'             => ['nullable', 'string', 'max:1'],
            'document_number'           => ['nullable', 'string', 'max:20'],
            'name'                      => ['nullable', 'string', 'max:100'],
            'trade_name'                => ['nullable', 'string', 'max:100'],
            'cellphone'                 => ['nullable', 'string', 'max:9'],
            'email'                     => ['nullable', 'email', 'max:100'],
            'address'                   => ['nullable', 'string', 'max:200'],
            'city'                      => ['nullable', 'string', 'max:6'],
            'community'                 => ['nullable', 'string', 'max:100'],
            'latitude'                  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'                 => ['nullable', 'numeric', 'between:-180,180'],
            'cows_in_production'        => ['required', 'integer', 'min:0', 'max:65535'],
            'dry_cows'                  => ['required', 'integer', 'min:0', 'max:65535'],
            'tank_capacity_liters'      => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'tank_alert_percentage'     => ['nullable', 'integer', 'min:0', 'max:100'],
            'reference_price_per_liter' => ['nullable', 'numeric', 'min:0', 'max:999999.9999'],
            'description'               => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'integer'  => 'El campo :attribute debe ser un número entero.',
            'numeric'  => 'El campo :attribute debe ser un número.',
            'string'   => 'El campo :attribute debe ser texto.',
            'email'    => 'El campo :attribute debe ser un correo válido.',
            'in'       => 'El campo :attribute tiene un valor no permitido.',
            'min'      => 'El campo :attribute no puede ser menor a :min.',
            'max'      => 'El campo :attribute no puede superar :max.',
            'between'  => 'El campo :attribute debe estar entre :min y :max.',
        ];
    }

    public function attributes(): array
    {
        return [
            'supplier_type'             => 'tipo de proveedor',
            'document_type'             => 'tipo de documento',
            'document_number'           => 'número de documento',
            'name'                      => 'nombre',
            'trade_name'                => 'nombre comercial',
            'cellphone'                 => 'celular',
            'email'                     => 'correo electrónico',
            'address'                   => 'dirección',
            'city'                      => 'ciudad',
            'community'                 => 'comunidad',
            'latitude'                  => 'latitud',
            'longitude'                 => 'longitud',
            'cows_in_production'        => 'vacas en producción',
            'dry_cows'                  => 'vacas secas',
            'tank_capacity_liters'      => 'capacidad del tanque',
            'tank_alert_percentage'     => 'alerta de llenado',
            'reference_price_per_liter' => 'precio de referencia',
            'description'               => 'descripción',
        ];
    }
}
