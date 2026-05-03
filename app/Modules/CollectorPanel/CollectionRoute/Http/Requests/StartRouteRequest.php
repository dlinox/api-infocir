<?php

namespace App\Modules\CollectorPanel\CollectionRoute\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class StartRouteRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'start_latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'start_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'initial_mileage' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'numeric'  => 'El campo :attribute debe ser un número.',
            'between'  => 'El campo :attribute debe estar entre :min y :max.',
            'min'      => 'El campo :attribute no puede ser menor a :min.',
        ];
    }

    public function attributes(): array
    {
        return [
            'start_latitude'  => 'latitud de inicio',
            'start_longitude' => 'longitud de inicio',
            'initial_mileage' => 'kilometraje inicial',
        ];
    }
}
