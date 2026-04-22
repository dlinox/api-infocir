<?php

namespace App\Modules\Admin\Dairy\Organization\Supplier\Http\Requests\SupplierGallery;

use App\Common\Http\Requests\ApiFormRequest;

class SupplierGalleryRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'          => 'nullable|integer',
            'supplier_id' => 'required|integer|exists:dairy_suppliers,id',
            'file_id'     => 'required|integer|exists:core_files,id',
            'caption'     => 'nullable|string|max:255',
            'is_active'   => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.required' => 'El proveedor es requerido',
            'supplier_id.exists'   => 'El proveedor seleccionado no existe',
            'file_id.required'     => 'La imagen es requerida',
            'file_id.exists'       => 'El archivo seleccionado no existe',
            'caption.max'          => 'El pie de foto no puede superar los 255 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'supplier_id' => 'proveedor',
            'file_id'     => 'imagen',
            'caption'     => 'pie de foto',
            'is_active'   => 'estado',
        ];
    }
}
