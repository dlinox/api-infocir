<?php

namespace App\Modules\Admin\Setting\Http\Requests\WorkingCapitalCatalog;

use App\Common\Http\Requests\ApiFormRequest;

class WorkingCapitalCatalogRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? 'NULL';
        return [
            'id'                     => 'nullable|integer',
            'investment_category_id' => 'required|integer|exists:dairy_investment_categories,id',
            'icon_file_id'           => 'nullable|integer|exists:core_files,id',
            'name'                   => 'required|string|max:100|unique:dairy_working_capital_catalog,name,' . $id . ',id',
            'description'            => 'nullable|string|max:255',
            'color'                  => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'recurrence_type'        => 'required|in:none,monthly,every_x_days',
            'recurrence_every_days'  => 'nullable|integer|min:1|max:365|required_if:recurrence_type,every_x_days',
            'is_active'              => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'investment_category_id.required' => 'La :attribute es requerida.',
            'investment_category_id.exists'   => 'La :attribute seleccionada no existe.',
            'icon_file_id.exists'             => 'El :attribute seleccionado no existe.',
            'name.required'                   => 'El :attribute es requerido.',
            'name.max'                        => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'                     => 'El :attribute ya existe.',
            'description.max'                 => 'La :attribute no debe exceder los :max caracteres.',
            'color.required'                  => 'El :attribute es requerido.',
            'color.regex'                     => 'El :attribute debe tener un formato HEX válido, por ejemplo #16a34a.',
            'recurrence_type.required'        => 'La :attribute es requerida.',
            'recurrence_type.in'              => 'La :attribute seleccionada no es válida.',
            'recurrence_every_days.required_if' => 'Los :attribute son requeridos para la recurrencia cada X días.',
            'is_active.required'              => 'El :attribute es requerido.',
            'is_active.boolean'               => 'El :attribute debe ser verdadero o falso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'                     => 'ID',
            'investment_category_id' => 'Categoría de inversión',
            'icon_file_id'           => 'icono',
            'name'                   => 'Nombre',
            'description'            => 'Descripción',
            'color'                  => 'color',
            'recurrence_type'        => 'tipo de recurrencia',
            'recurrence_every_days'  => 'días de recurrencia',
            'is_active'              => 'Estado',
        ];
    }
}
