<?php

namespace App\Common\Http\Requests\File;

use App\Common\Http\Requests\ApiFormRequest;

class UploadBase64FileRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'file'    => ['required', 'string'],
            'caption' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'El archivo en base64 es obligatorio.',
            'file.string'   => 'El archivo debe ser una cadena base64 válida.',
            'caption.max'   => 'El caption no debe superar los 255 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'file'    => 'archivo',
            'caption' => 'caption',
        ];
    }
}
