<?php

namespace App\Modules\Learning\Instructor\Catalog\Program\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Learning\Instructor\Catalog\Program\Http\Requests\ProgramCourse\ProgramCourseRequest;
use App\Modules\Learning\Instructor\Catalog\Program\Services\ProgramCourseService;

class ProgramCourseController
{
    public function __construct(
        private ProgramCourseService $programCourseService
    ) {}

    public function save(ProgramCourseRequest $request)
    {
        $data = $request->validated();
        $this->programCourseService->save($data);
        return ApiResponse::success(null, 'Curso del programa guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->programCourseService->delete((int) $id);
        return ApiResponse::success(null, 'Curso del programa eliminado correctamente');
    }
}
