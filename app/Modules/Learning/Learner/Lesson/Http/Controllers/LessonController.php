<?php

namespace App\Modules\Learning\Learner\Lesson\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Learning\Learner\Lesson\Http\Requests\Lesson\CompleteLessonRequest;
use App\Modules\Learning\Learner\Lesson\Http\Requests\Lesson\SubmitQuizRequest;
use App\Modules\Learning\Learner\Lesson\Services\LessonService;
use Illuminate\Http\JsonResponse;

class LessonController
{
    public function __construct(
        private LessonService $lessonService,
    ) {}

    public function complete(CompleteLessonRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = $this->lessonService->completeLesson(
            (int) $validated['enrollment_id'],
            (int) $validated['lesson_id'],
        );

        return ApiResponse::success($result, 'Lección marcada como completada.');
    }

    public function submitQuiz(SubmitQuizRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = $this->lessonService->submitQuiz(
            (int) $validated['enrollment_id'],
            (int) $validated['lesson_id'],
            $validated['answers'],
        );

        $message = $result['passed']
            ? '¡Felicidades! Aprobaste el cuestionario.'
            : 'Cuestionario enviado. Puedes volver a intentarlo.';

        return ApiResponse::success($result, $message);
    }
}
