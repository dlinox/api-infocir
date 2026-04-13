<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Http\Requests\Course;

use App\Common\Http\Requests\ApiFormRequest;

class CourseRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? 'NULL';

        return [
            'id'             => 'nullable|integer',
            'name'           => 'required|string|max:150|unique:learning_courses,name,' . $id . ',id',
            'description'    => 'nullable|string',
            'area_id'        => 'nullable|integer|exists:learning_areas,id',
            'duration_min'   => 'nullable|numeric|min:0',
            'status'         => 'required|in:draft,published,archived',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'El :attribute es requerido.',
            'name.string'      => 'El :attribute debe ser una cadena de texto.',
            'name.max'         => 'El :attribute no debe exceder los :max caracteres.',
            'name.unique'      => 'El :attribute ya existe.',
            'area_id.integer'  => 'El :attribute debe ser un número entero.',
            'area_id.exists'   => 'El :attribute seleccionado no existe.',
            'duration_min.numeric' => 'El :attribute debe ser un número.',
            'duration_min.min'     => 'El :attribute no puede ser negativo.',
            'status.required'  => 'El :attribute es requerido.',
            'status.in'        => 'El :attribute debe ser draft, published o archived.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'             => 'ID',
            'name'           => 'Nombre',
            'description'    => 'Descripción',
            'area_id'        => 'Área',
            'duration_min'  => 'Duración (minutos)',
            'status'         => 'Estado',
        ];
    }
}
