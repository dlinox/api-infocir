<?php

namespace App\Modules\CollectorPanel\CollectionRoute\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class FinalizeRouteRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'end_latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'end_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'final_mileage' => ['nullable', 'numeric', 'min:0'],
            'observations'  => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'numeric' => 'El campo :attribute debe ser un número.',
            'between' => 'El campo :attribute debe estar entre :min y :max.',
            'min'     => 'El campo :attribute no puede ser menor a :min.',
            'max'     => 'El campo :attribute no puede superar :max caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'end_latitude'  => 'latitud de fin',
            'end_longitude' => 'longitud de fin',
            'final_mileage' => 'kilometraje final',
            'observations'  => 'observaciones',
        ];
    }
}
