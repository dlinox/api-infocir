<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Learning\Instructor\Catalog\Course\Http\Requests\Lesson\LessonRequest;
use App\Modules\Learning\Instructor\Catalog\Course\Services\LessonService;

class LessonController
{
    public function __construct(
        private LessonService $lessonService
    ) {}

    public function save(LessonRequest $request)
    {
        $data = $request->validated();
        $this->lessonService->save($data);
        return ApiResponse::success(null, 'Lección guardada correctamente');
    }

    public function updateHasQuiz(Request $request, int $id)
    {
        $this->lessonService->updateHasQuiz($id, (bool) $request->hasQuiz);
        return ApiResponse::success(null, 'Lección actualizada correctamente');
    }

    public function delete(string $id)
    {
        $this->lessonService->delete((int) $id);
        return ApiResponse::success(null, 'Lección eliminada correctamente');
    }
}
