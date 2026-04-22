<?php

namespace App\Modules\Admin\Dairy\Catalog\Product\Http\Requests\ProductGallery;

use App\Common\Http\Requests\ApiFormRequest;

class ProductGalleryRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'              => 'nullable|integer',
            'product_id'      => 'required|integer|exists:dairy_products,id',
            'presentation_id' => 'nullable|integer|exists:dairy_product_presentations,id',
            'file_id'         => 'required|integer|exists:core_files,id',
            'caption'         => 'nullable|string|max:255',
            'is_active'       => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required'    => 'El producto es requerido',
            'product_id.exists'      => 'El producto seleccionado no existe',
            'presentation_id.exists' => 'La presentación seleccionada no existe',
            'file_id.required'       => 'La imagen es requerida',
            'file_id.exists'         => 'El archivo seleccionado no existe',
            'caption.max'            => 'El pie de foto no puede superar los 255 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'product_id'      => 'producto',
            'presentation_id' => 'presentación',
            'file_id'         => 'imagen',
            'caption'         => 'pie de foto',
            'is_active'       => 'estado',
        ];
    }
}
