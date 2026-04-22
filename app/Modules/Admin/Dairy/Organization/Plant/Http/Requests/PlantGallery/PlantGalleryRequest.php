<?php

namespace App\Modules\Admin\Dairy\Organization\Plant\Http\Requests\PlantGallery;

use App\Common\Http\Requests\ApiFormRequest;

class PlantGalleryRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'        => 'nullable|integer',
            'plant_id'  => 'required|integer|exists:dairy_plants,id',
            'file_id'   => 'required|integer|exists:core_files,id',
            'caption'   => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'plant_id.required' => 'La planta es requerida',
            'plant_id.exists'   => 'La planta seleccionada no existe',
            'file_id.required'  => 'La imagen es requerida',
            'file_id.exists'    => 'El archivo seleccionado no existe',
            'caption.max'       => 'El pie de foto no puede superar los 255 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'plant_id'  => 'planta',
            'file_id'   => 'imagen',
            'caption'   => 'pie de foto',
            'is_active' => 'estado',
        ];
    }
}
