<?php

namespace App\Modules\Admin\Learning\Instructor\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Learning\Instructor\Http\Requests\Instructor\InstructorRequest;
use App\Modules\Admin\Learning\Instructor\Http\Resources\Instructor\InstructorDataTableItemResource;
use App\Modules\Admin\Learning\Instructor\Http\Resources\Instructor\InstructorFormResource;
use App\Modules\Admin\Learning\Instructor\Services\InstructorService;

class InstructorController
{
    public function __construct(
        private InstructorService $instructorService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->instructorService->dataTable($request);
        $items['data'] = InstructorDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $instructor = $this->instructorService->findByPersonId((int) $id);
        return ApiResponse::success(new InstructorFormResource($instructor));
    }

    public function save(InstructorRequest $request)
    {
        $data = $request->validated();
        $this->instructorService->save($data);
        return ApiResponse::success(null, 'Instructor guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->instructorService->delete((int) $id);
        return ApiResponse::success(null, 'Instructor eliminado correctamente');
    }
}
