<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Learning\Instructor\Catalog\Course\Http\Requests\QuizOption\QuizOptionRequest;
use App\Modules\Learning\Instructor\Catalog\Course\Services\QuizOptionService;

class QuizOptionController
{
    public function __construct(
        private readonly QuizOptionService $service
    ) {}

    public function save(QuizOptionRequest $request)
    {
        $option = $this->service->save($request->validated());
        return ApiResponse::success($option, 'Opción guardada correctamente');
    }

    public function delete(int $id)
    {
        $this->service->delete($id);
        return ApiResponse::success(null, 'Opción eliminada correctamente');
    }
}
