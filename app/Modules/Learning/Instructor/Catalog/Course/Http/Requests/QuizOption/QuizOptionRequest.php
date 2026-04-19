<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Http\Requests\QuizOption;

use App\Common\Http\Requests\ApiFormRequest;

class QuizOptionRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'          => 'nullable|integer|exists:learning_quiz_options,id',
            'question_id' => 'required|integer|exists:learning_quiz_questions,id',
            'text'        => 'required|string|max:255',
            'is_correct'  => 'boolean',
            'explanation' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'question_id.required' => 'La pregunta es requerida.',
            'question_id.exists'   => 'La pregunta no existe.',
            'text.required'        => 'El texto de la opción es requerido.',
            'text.max'             => 'El texto no puede superar los 255 caracteres.',
            'explanation.max'      => 'La explicación no puede superar los 255 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'question_id' => 'pregunta',
            'text'        => 'texto',
            'is_correct'  => 'respuesta correcta',
            'explanation' => 'explicación',
        ];
    }
}
