<?php

namespace App\Modules\Admin\Setting\Http\Requests\PreOperativeCatalog;

use App\Common\Http\Requests\ApiFormRequest;

class PreOperativeCatalogRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? 'NULL';
        return [
            'id'                     => 'nullable|integer',
            'investment_category_id' => 'required|integer|exists:dairy_investment_categories,id',
            'name'                   => 'required|string|max:100|unique:dairy_pre_operative_catalog,name,' . $id . ',id',
            'issuing_entity'         => 'nullable|string|max:100',
            'recurrence_type'        => 'nullable|in:one_time,periodic',
            'validity_years'         => 'nullable|integer|min:1|max:100|required_if:recurrence_type,periodic',
            'is_public'              => 'boolean',
            'is_active'              => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'investment_category_id.required' => 'La :attribute es requerida.',
            'investment_category_id.exists'   => 'La :attribute seleccionada no existe.',
            'name.required'                   => 'El :attribute es requerido.',
            'name.max'                        => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'                     => 'El :attribute ya existe.',
            'issuing_entity.max'              => 'La :attribute no debe exceder los :max caracteres.',
            'recurrence_type.in'              => 'El :attribute no es válido.',
            'validity_years.integer'          => 'La :attribute debe ser un número entero.',
            'validity_years.min'              => 'La :attribute debe ser al menos :min.',
            'validity_years.max'              => 'La :attribute no debe exceder :max.',
            'validity_years.required_if'      => 'La :attribute es requerida cuando el tipo es periódico.',
            'is_active.required'              => 'El :attribute es requerido.',
            'is_active.boolean'               => 'El :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'                     => 'ID',
            'investment_category_id' => 'Categoría de inversión',
            'name'                   => 'Nombre',
            'issuing_entity'         => 'Entidad emisora',
            'recurrence_type'        => 'Tipo de pago',
            'validity_years'         => 'Vigencia (años)',
            'is_public'              => 'Información pública',
            'is_active'              => 'Estado',
        ];
    }
}
