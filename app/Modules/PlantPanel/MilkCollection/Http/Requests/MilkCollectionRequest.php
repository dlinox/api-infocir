<?php

namespace App\Modules\PlantPanel\MilkCollection\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class MilkCollectionRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'              => ['nullable', 'integer'],
            'supplier_id'     => ['required', 'integer', 'exists:dairy_suppliers,id'],
            'collection_date' => ['required', 'date'],
            'shift'           => ['required', 'in:morning,afternoon'],
            'quantity_liters' => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'price_per_liter' => ['required', 'numeric', 'min:0', 'max:999999.9999'],
            'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
            'payment_status'  => ['nullable', 'in:pending,paid,cancelled'],
            'photo_base64'    => ['nullable', 'string'],
            'observations'    => ['nullable', 'string', 'max:1000'],

            'quality_test'                  => ['nullable', 'array'],
            'quality_test.fat_percentage'   => ['nullable', 'numeric', 'min:0', 'max:100'],
            'quality_test.snf_percentage'   => ['nullable', 'numeric', 'min:0', 'max:100'],
            'quality_test.density'          => ['nullable', 'numeric', 'min:0'],
            'quality_test.acidity'          => ['nullable', 'numeric', 'min:0'],
            'quality_test.temperature'      => ['nullable', 'numeric', 'min:-50', 'max:100'],
            'quality_test.quality_grade'    => ['nullable', 'in:A,B,C,D'],
            'quality_test.observations'     => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'numeric'  => 'El campo :attribute debe ser un número.',
            'integer'  => 'El campo :attribute debe ser un entero.',
            'date'     => 'El campo :attribute debe ser una fecha válida.',
            'in'       => 'El campo :attribute tiene un valor no permitido.',
            'min'      => 'El campo :attribute no puede ser menor a :min.',
            'max'      => 'El campo :attribute no puede superar :max.',
            'exists'   => 'El proveedor seleccionado no existe.',
            'between'  => 'El campo :attribute debe estar entre :min y :max.',
        ];
    }

    public function attributes(): array
    {
        return [
            'supplier_id'     => 'proveedor',
            'collection_date' => 'fecha de recolección',
            'shift'           => 'turno',
            'quantity_liters' => 'cantidad (litros)',
            'price_per_liter' => 'precio por litro',
            'payment_status'  => 'estado de pago',
            'observations'    => 'observaciones',
        ];
    }
}
