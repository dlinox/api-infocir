<?php

namespace App\Modules\Admin\Setting\Http\Requests\AssetCatalog;

use App\Common\Http\Requests\ApiFormRequest;

class AssetCatalogRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? 'NULL';
        return [
            'id'                       => 'nullable|integer',
            'investment_category_id'   => 'required|integer|exists:dairy_investment_categories,id',
            'name'                     => 'required|string|max:100|unique:dairy_asset_catalog,name,' . $id . ',id',
            'brand'                    => 'nullable|string|max:100',
            'model'                    => 'nullable|string|max:100',
            'useful_life_years'        => 'nullable|integer|min:1|max:99',
            'depreciation_method'      => 'nullable|in:straight_line,declining_balance',
            'is_active'                => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'investment_category_id.required' => 'La :attribute es requerida.',
            'investment_category_id.exists'   => 'La :attribute seleccionada no existe.',
            'name.required'                   => 'El :attribute es requerido.',
            'name.string'                     => 'El :attribute debe ser una cadena de texto.',
            'name.max'                        => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'                     => 'El :attribute ya existe.',
            'brand.max'                       => 'La :attribute no debe exceder los :max caracteres.',
            'model.max'                       => 'El :attribute no debe exceder los :max caracteres.',
            'useful_life_years.integer'       => 'Los :attribute deben ser un número entero.',
            'useful_life_years.min'           => 'Los :attribute deben ser al menos :min.',
            'useful_life_years.max'           => 'Los :attribute no deben exceder :max.',
            'depreciation_method.in'          => 'El :attribute no es válido.',
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
            'brand'                  => 'Marca',
            'model'                  => 'Modelo',
            'useful_life_years'      => 'Años de vida útil',
            'depreciation_method'    => 'Método de depreciación',
            'is_active'              => 'Estado',
        ];
    }
}
