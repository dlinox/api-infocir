<?php

namespace App\Modules\Learning\Learner\Lesson\Http\Requests\Lesson;

use App\Common\Http\Requests\ApiFormRequest;

class CompleteLessonRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'enrollment_id' => 'required|integer|exists:learning_enrollments,id',
            'lesson_id'     => 'required|integer|exists:learning_lessons,id',
        ];
    }

    public function messages(): array
    {
        return [
            'enrollment_id.required' => 'La inscripción es obligatoria.',
            'enrollment_id.integer'  => 'La inscripción no es válida.',
            'enrollment_id.exists'   => 'La inscripción no existe.',
            'lesson_id.required'     => 'La lección es obligatoria.',
            'lesson_id.integer'      => 'La lección no es válida.',
            'lesson_id.exists'       => 'La lección no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'enrollment_id' => 'inscripción',
            'lesson_id'     => 'lección',
        ];
    }
}
