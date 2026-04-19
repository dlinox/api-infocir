<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Learning\Instructor\Catalog\Course\Http\Requests\CourseModule\CourseModuleRequest;
use App\Modules\Learning\Instructor\Catalog\Course\Services\CourseModuleService;

class CourseModuleController
{
    public function __construct(
        private CourseModuleService $courseModuleService
    ) {}

    public function save(CourseModuleRequest $request)
    {
        $data = $request->validated();
        $this->courseModuleService->save($data);
        return ApiResponse::success(null, 'Módulo guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->courseModuleService->delete((int) $id);
        return ApiResponse::success(null, 'Módulo eliminado correctamente');
    }
}
