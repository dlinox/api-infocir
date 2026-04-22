<?php

namespace App\Modules\PlantPanel\PlantSettings\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class PlantSettingsRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'ruc'                       => 'nullable|string|max:20',
            'name'                      => 'nullable|string|max:255',
            'trade_name'                => 'nullable|string|max:150',
            'type'                      => 'nullable|in:A,B,C',
            'brand'                     => 'nullable|string|max:100',
            'city'                      => 'nullable|string|max:100',
            'address'                   => 'nullable|string|max:255',
            'cellphone'                 => 'nullable|string|max:15',
            'email'                     => 'nullable|email|max:100',
            'latitude'                  => 'nullable|numeric|between:-90,90',
            'longitude'                 => 'nullable|numeric|between:-180,180',
            'capacity_liters'           => 'nullable|numeric|min:0',
            'product_quality'           => 'nullable|in:very_low,low,medium,high,excellent',
            'has_sanitary_registration' => 'nullable|boolean',
            'has_technification'        => 'nullable|boolean',
            'has_production_parameters' => 'nullable|boolean',
            'has_digesa_parameters'     => 'nullable|boolean',
            'has_tdd_training'          => 'nullable|boolean',
            'description'               => 'nullable|string|max:2000',
            'company_type_id'           => 'nullable|exists:dairy_company_types,id',
            'training_level_id'         => 'nullable|exists:dairy_training_levels,id',
            'institution_type_id'       => 'nullable|exists:dairy_institution_types,id',
        ];
    }

    public function messages(): array
    {
        return [
            'ruc.max'                      => 'El :attribute no debe exceder los :max caracteres.',
            'name.max'                     => 'La :attribute no debe exceder los :max caracteres.',
            'trade_name.max'               => 'El :attribute no debe exceder los :max caracteres.',
            'type.in'                      => 'El :attribute debe ser A, B o C.',
            'brand.max'                    => 'La :attribute no debe exceder los :max caracteres.',
            'city.max'                     => 'La :attribute no debe exceder los :max caracteres.',
            'address.max'                  => 'La :attribute no debe exceder los :max caracteres.',
            'cellphone.max'                => 'El :attribute no debe exceder los :max caracteres.',
            'email.email'                  => 'El :attribute debe ser un correo electrónico válido.',
            'email.max'                    => 'El :attribute no debe exceder los :max caracteres.',
            'latitude.between'             => 'La :attribute debe estar entre -90 y 90.',
            'longitude.between'            => 'La :attribute debe estar entre -180 y 180.',
            'capacity_liters.min'          => 'La :attribute no puede ser negativa.',
            'product_quality.in'           => 'La :attribute seleccionada no es válida.',
            'company_type_id.exists'       => 'El :attribute seleccionado no existe.',
            'training_level_id.exists'     => 'El :attribute seleccionado no existe.',
            'institution_type_id.exists'   => 'El :attribute seleccionado no existe.',
            'description.max'              => 'La :attribute no debe exceder los :max caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'ruc'                       => 'RUC',
            'name'                      => 'razón social',
            'trade_name'                => 'nombre comercial',
            'type'                      => 'tipo',
            'brand'                     => 'marca',
            'city'                      => 'ciudad',
            'address'                   => 'dirección',
            'cellphone'                 => 'celular',
            'email'                     => 'correo',
            'latitude'                  => 'latitud',
            'longitude'                 => 'longitud',
            'capacity_liters'           => 'capacidad (litros)',
            'product_quality'           => 'calidad de producto',
            'has_sanitary_registration' => 'registro sanitario',
            'has_technification'        => 'tecnificación',
            'has_production_parameters' => 'parámetros de producción',
            'has_digesa_parameters'     => 'parámetros DIGESA',
            'has_tdd_training'          => 'capacitación TDD',
            'description'               => 'descripción',
            'company_type_id'           => 'tipo de empresa',
            'training_level_id'         => 'nivel de capacitación',
            'institution_type_id'       => 'tipo de institución',
        ];
    }
}
