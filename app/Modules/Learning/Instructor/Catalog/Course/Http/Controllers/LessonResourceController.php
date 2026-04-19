<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Learning\Instructor\Catalog\Course\Http\Requests\LessonResource\LessonResourceRequest;
use App\Modules\Learning\Instructor\Catalog\Course\Services\LessonResourceService;

class LessonResourceController
{
    public function __construct(
        private readonly LessonResourceService $service
    ) {}

    public function save(LessonResourceRequest $request)
    {
        $resource = $this->service->save($request->validated());
        return ApiResponse::success($resource, 'Recurso guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->service->delete($id);
        return ApiResponse::success(null, 'Recurso eliminado correctamente');
    }
}
