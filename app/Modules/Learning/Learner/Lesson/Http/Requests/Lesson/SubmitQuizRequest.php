<?php

namespace App\Modules\Learning\Learner\Lesson\Http\Requests\Lesson;

use App\Common\Http\Requests\ApiFormRequest;

class SubmitQuizRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'enrollment_id'         => 'required|integer|exists:learning_enrollments,id',
            'lesson_id'             => 'required|integer|exists:learning_lessons,id',
            'answers'               => 'required|array|min:1',
            'answers.*.question_id' => 'required|integer|exists:learning_quiz_questions,id',
            'answers.*.option_id'   => 'required|integer|exists:learning_quiz_options,id',
        ];
    }

    public function messages(): array
    {
        return [
            'enrollment_id.required'       => 'La inscripción es obligatoria.',
            'enrollment_id.exists'         => 'La inscripción no existe.',
            'lesson_id.required'           => 'La lección es obligatoria.',
            'lesson_id.exists'             => 'La lección no existe.',
            'answers.required'             => 'Debes responder las preguntas antes de enviar.',
            'answers.array'                => 'El formato de respuestas no es válido.',
            'answers.min'                  => 'Debes responder al menos una pregunta.',
            'answers.*.question_id.required' => 'Falta el identificador de una pregunta.',
            'answers.*.question_id.exists' => 'Una de las preguntas no existe.',
            'answers.*.option_id.required' => 'Debes seleccionar una respuesta en cada pregunta.',
            'answers.*.option_id.exists'   => 'Una de las respuestas seleccionadas no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'enrollment_id'         => 'inscripción',
            'lesson_id'             => 'lección',
            'answers'               => 'respuestas',
            'answers.*.question_id' => 'pregunta',
            'answers.*.option_id'   => 'respuesta',
        ];
    }
}
