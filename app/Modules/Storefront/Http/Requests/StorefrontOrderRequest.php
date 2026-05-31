<?php

namespace App\Modules\Storefront\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class StorefrontOrderRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'customer_name'           => 'required|string|max:150',
            'customer_phone'          => 'required|string|max:30',
            'customer_email'          => 'nullable|email|max:150',
            'customer_document'       => 'nullable|string|max:20',
            'address'                 => 'nullable|string|max:255',
            'district'                => 'nullable|string|max:100',
            'city'                    => 'nullable|string|max:100',
            'reference'               => 'nullable|string|max:255',
            'inquiry'                 => 'nullable|string|max:1000',
            'items'                   => 'required|array|min:1',
            'items.*.presentation_id' => 'required|integer|exists:dairy_product_presentations,id',
            'items.*.quantity'        => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required'           => 'El :attribute es requerido.',
            'customer_phone.required'          => 'El :attribute es requerido.',
            'customer_email.email'             => 'El :attribute no es válido.',
            'items.required'                   => 'Debe incluir al menos un :attribute.',
            'items.min'                        => 'Debe incluir al menos un :attribute.',
            'items.*.presentation_id.required' => 'La presentación es requerida.',
            'items.*.presentation_id.exists'   => 'La presentación seleccionada no existe.',
            'items.*.quantity.required'        => 'La cantidad es requerida.',
            'items.*.quantity.min'             => 'La cantidad debe ser al menos 1.',
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_name'  => 'Nombre',
            'customer_phone' => 'Teléfono',
            'customer_email' => 'Correo',
            'items'          => 'producto',
        ];
    }
}
