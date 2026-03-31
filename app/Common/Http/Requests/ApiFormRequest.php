<?php

namespace App\Common\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Common\Http\Responses\ApiResponse;
use Illuminate\Support\Str;

class ApiFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator): never
    {
        $allErrors = $this->transformKeysToCamel($validator->errors()->messages());
        $attributes = $this->attributes();

        $message = 'Los datos proporcionados no son válidos.';

        if (!empty($attributes)) {
            // Obtener los nombres personalizados de los campos con errores
            $failedFields = collect(array_keys($validator->errors()->messages()))
                ->map(function ($field) use ($attributes) {
                    $baseField = last(explode('.', $field));
                    return $attributes[$field] ?? $attributes[$baseField] ?? null;
                })
                ->filter() // Eliminar nulls (campos sin attribute definido)
                ->unique()
                ->implode(', ');

            if ($failedFields) {
                $message = "Los datos proporcionados no son válidos ({$failedFields})";
            }
        }

        throw new HttpResponseException(
            ApiResponse::error(
                $message,
                $allErrors,
                422,
            )
        );
    }

    protected function prepareForValidation(): void
    {
        $this->replace($this->transformToSnake($this->all()));
    }

    private function transformToSnake(array $data): array
    {
        return collect($data)->mapWithKeys(function ($value, $key) {
            $snakeKey = Str::snake($key);
            return [$snakeKey => is_array($value) ? $this->transformToSnake($value) : $value];
        })->all();
    }

    private function transformKeysToCamel(array $data): array
    {
        return collect($data)->mapWithKeys(function ($value, $key) {
            $camelKey = Str::camel($key);
            return [$camelKey => is_array($value) ? $this->transformKeysToCamel($value) : $value];
        })->all();
    }
}
