<?php

namespace App\Modules\Admin\Dairy\Plant\Http\Requests\Plant;

use App\Common\Http\Requests\ApiFormRequest;

class PlantRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? 'NULL';
        return [
            'id'                         => 'nullable|integer',
            'ruc'                        => 'required|string|size:11|unique:dairy_plants,ruc,' . $id . ',id',
            'name'                       => 'required|string|max:100|unique:dairy_plants,name,' . $id . ',id',
            'trade_name'                 => 'nullable|string|max:100',
            'type'                       => 'required|in:A,B,C',
            'brand'                      => 'nullable|string|max:100',
            'country'                    => 'nullable|string|size:2|exists:core_countries,code',
            'city'                       => 'nullable|string|size:6|exists:core_cities,code',
            'address'                    => 'nullable|string|max:200',
            'cellphone'                  => 'required|string|size:9|unique:dairy_plants,cellphone,' . $id . ',id',
            'email'                      => 'nullable|email|max:100|unique:dairy_plants,email,' . $id . ',id',
            'latitude'                   => 'nullable|numeric|between:-90,90',
            'longitude'                  => 'nullable|numeric|between:-180,180',
            'product_quality'            => 'required|in:very_low,low,medium,high,excellent',
            'has_sanitary_registration'  => 'required|boolean',
            'has_technification'         => 'required|boolean',
            'has_production_parameters'  => 'required|boolean',
            'has_digesa_parameters'      => 'required|boolean',
            'has_tdd_training'           => 'required|boolean',
            'description'                => 'nullable|string',
            'is_active'                  => 'required|boolean',
            'company_type_id'            => 'nullable|integer|exists:dairy_company_types,id',
            'training_level_id'          => 'nullable|integer|exists:dairy_training_levels,id',
            'institution_type_id'        => 'nullable|integer|exists:dairy_institution_types,id',
        ];
    }

    public function messages(): array
    {
        return [
            'ruc.required'                        => 'El :attribute es requerido.',
            'ruc.size'                            => 'El :attribute debe tener :size caracteres.',
            'ruc.unique'                          => 'El :attribute ya existe.',
            'name.required'                       => 'El :attribute es requerido.',
            'name.max'                            => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'                         => 'El :attribute ya existe.',
            'trade_name.max'                      => 'La :attribute no debe exceder los :max caracteres.',
            'type.required'                       => 'El :attribute es requerido.',
            'type.in'                             => 'El :attribute debe ser A, B o C.',
            'brand.max'                           => 'La :attribute no debe exceder los :max caracteres.',
            'country.size'                        => 'El :attribute debe tener :size caracteres.',
            'country.exists'                      => 'El :attribute seleccionado no existe.',
            'city.size'                           => 'La :attribute debe tener :size caracteres.',
            'city.exists'                         => 'La :attribute seleccionada no existe.',
            'address.max'                         => 'La :attribute no debe exceder los :max caracteres.',
            'cellphone.required'                  => 'El :attribute es requerido.',
            'cellphone.size'                      => 'El :attribute debe tener :size dígitos.',
            'cellphone.unique'                    => 'El :attribute ya existe.',
            'email.email'                         => 'El :attribute debe ser válido.',
            'email.max'                           => 'El :attribute no debe exceder los :max caracteres.',
            'email.unique'                        => 'El :attribute ya existe.',
            'latitude.numeric'                    => 'La :attribute debe ser numérica.',
            'latitude.between'                    => 'La :attribute debe estar entre :min y :max.',
            'longitude.numeric'                   => 'La :attribute debe ser numérica.',
            'longitude.between'                   => 'La :attribute debe estar entre :min y :max.',
            'product_quality.required'            => 'La :attribute es requerida.',
            'product_quality.in'                  => 'La :attribute debe ser: muy baja, baja, media, alta o excelente.',
            'has_sanitary_registration.required'  => 'El campo :attribute es requerido.',
            'has_sanitary_registration.boolean'   => 'El campo :attribute debe ser verdadero o falso.',
            'has_technification.required'         => 'El campo :attribute es requerido.',
            'has_technification.boolean'          => 'El campo :attribute debe ser verdadero o falso.',
            'has_production_parameters.required'  => 'El campo :attribute es requerido.',
            'has_production_parameters.boolean'   => 'El campo :attribute debe ser verdadero o falso.',
            'has_digesa_parameters.required'      => 'El campo :attribute es requerido.',
            'has_digesa_parameters.boolean'       => 'El campo :attribute debe ser verdadero o falso.',
            'has_tdd_training.required'           => 'El campo :attribute es requerido.',
            'has_tdd_training.boolean'            => 'El campo :attribute debe ser verdadero o falso.',
            'is_active.required'                  => 'El :attribute es requerido.',
            'is_active.boolean'                   => 'El :attribute debe ser verdadero o falso.',
            'company_type_id.exists'              => 'El :attribute seleccionado no existe.',
            'training_level_id.exists'            => 'El :attribute seleccionado no existe.',
            'institution_type_id.exists'          => 'El :attribute seleccionado no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'                        => 'ID',
            'ruc'                       => 'RUC',
            'name'                      => 'Nombre',
            'trade_name'                => 'Nombre comercial',
            'type'                      => 'Tipo',
            'brand'                     => 'Marca',
            'country'                   => 'País',
            'city'                      => 'Ciudad',
            'address'                   => 'Dirección',
            'cellphone'                 => 'Celular',
            'email'                     => 'Email',
            'latitude'                  => 'Latitud',
            'longitude'                 => 'Longitud',
            'product_quality'           => 'Calidad de producto',
            'has_sanitary_registration' => 'Registro sanitario',
            'has_technification'        => 'Tecnificación',
            'has_production_parameters' => 'Parámetros de producción',
            'has_digesa_parameters'     => 'Parámetros DIGESA',
            'has_tdd_training'          => 'Capacitación TDD',
            'description'               => 'Descripción',
            'is_active'                 => 'Estado',
            'company_type_id'           => 'Tipo de empresa',
            'training_level_id'         => 'Nivel de capacitación',
            'institution_type_id'       => 'Tipo de institución',
        ];
    }
}
