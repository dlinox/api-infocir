<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Learning\Instructor\Catalog\Course\Http\Requests\QuizQuestion\QuizQuestionRequest;
use App\Modules\Learning\Instructor\Catalog\Course\Services\QuizQuestionService;

class QuizQuestionController
{
    public function __construct(
        private readonly QuizQuestionService $service
    ) {}

    public function save(QuizQuestionRequest $request)
    {
        $question = $this->service->save($request->validated());
        return ApiResponse::success($question, 'Pregunta guardada correctamente');
    }

    public function delete(int $id)
    {
        $this->service->delete($id);
        return ApiResponse::success(null, 'Pregunta eliminada correctamente');
    }
}
