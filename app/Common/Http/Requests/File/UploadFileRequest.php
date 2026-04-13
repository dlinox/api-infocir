<?php

namespace App\Common\Http\Requests\File;

use App\Common\Http\Requests\ApiFormRequest;

class UploadFileRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'file'    => ['required', 'file', 'max:10240'],
            'caption' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'El archivo es obligatorio.',
            'file.file'     => 'El campo debe ser un archivo válido.',
            'file.max'      => 'El archivo no debe superar los 10 MB.',
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
