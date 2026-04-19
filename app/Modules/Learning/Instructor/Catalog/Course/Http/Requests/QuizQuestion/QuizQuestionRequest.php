<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Http\Requests\QuizQuestion;

use App\Common\Http\Requests\ApiFormRequest;

class QuizQuestionRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'        => 'nullable|integer|exists:learning_quiz_questions,id',
            'lesson_id' => 'required|integer|exists:learning_lessons,id',
            'question'  => 'required|string',
            'hint'      => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'lesson_id.required' => 'La lección es requerida.',
            'lesson_id.exists'   => 'La lección no existe.',
            'question.required'  => 'La pregunta es requerida.',
            'hint.max'           => 'La pista no puede superar los 255 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'lesson_id' => 'lección',
            'question'  => 'pregunta',
            'hint'      => 'pista',
        ];
    }
}
