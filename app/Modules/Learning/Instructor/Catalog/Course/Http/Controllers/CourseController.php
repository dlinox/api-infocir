<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Learning\Instructor\Catalog\Course\Http\Requests\Course\CourseRequest;
use App\Modules\Learning\Instructor\Catalog\Course\Http\Resources\Course\CourseDataTableItemResource;
use App\Modules\Learning\Instructor\Catalog\Course\Http\Resources\Course\CourseDetailResource;
use App\Modules\Learning\Instructor\Catalog\Course\Http\Resources\Course\CourseSelectItemResource;
use App\Modules\Learning\Instructor\Catalog\Course\Services\CourseService;

class CourseController
{
    public function __construct(
        private CourseService $courseService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->courseService->dataTable($request);
        $items['data'] = CourseDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $course = $this->courseService->findById((int) $id);
        return ApiResponse::success(new CourseDetailResource($course));
    }

    public function save(CourseRequest $request)
    {
        $data = $request->validated();
        $this->courseService->save($data);
        return ApiResponse::success(null, 'Curso guardado correctamente');
    }

    public function uploadCover(Request $request, int $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $this->courseService->uploadCover(
            $id,
            $request->file('file'),
            $request->input('caption'),
        );

        return ApiResponse::success(null, 'Portada actualizada correctamente');
    }

    public function delete(string $id)
    {
        $this->courseService->delete((int) $id);
        return ApiResponse::success(null, 'Curso eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->courseService->getSelectItems();
        return ApiResponse::success(CourseSelectItemResource::collection($items));
    }
}
