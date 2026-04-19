<?php

namespace App\Modules\Learning\Instructor\Catalog\Program\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Learning\Instructor\Catalog\Program\Http\Requests\Program\ProgramRequest;
use App\Modules\Learning\Instructor\Catalog\Program\Http\Resources\Program\ProgramDataTableItemResource;
use App\Modules\Learning\Instructor\Catalog\Program\Http\Resources\Program\ProgramDetailResource;
use App\Modules\Learning\Instructor\Catalog\Program\Http\Resources\Program\ProgramSelectItemResource;
use App\Modules\Learning\Instructor\Catalog\Program\Services\ProgramService;

class ProgramController
{
    public function __construct(
        private ProgramService $programService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->programService->dataTable($request);
        $items['data'] = ProgramDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $program = $this->programService->findById((int) $id);
        return ApiResponse::success(new ProgramDetailResource($program));
    }

    public function save(ProgramRequest $request)
    {
        $data = $request->validated();
        $this->programService->save($data);
        return ApiResponse::success(null, 'Programa guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->programService->delete((int) $id);
        return ApiResponse::success(null, 'Programa eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->programService->getSelectItems();
        return ApiResponse::success(ProgramSelectItemResource::collection($items));
    }
}
