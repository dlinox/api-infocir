<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\InstructionDegree\InstructionDegreeRequest;
use App\Modules\Admin\Setting\Http\Resources\InstructionDegree\InstructionDegreeDataTableItemResource;
use App\Modules\Admin\Setting\Http\Resources\InstructionDegree\InstructionDegreeSelectItemResource;
use App\Modules\Admin\Setting\Services\InstructionDegreeService;

class InstructionDegreeController
{
    public function __construct(
        private InstructionDegreeService $instructionDegreeService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->instructionDegreeService->dataTable($request);
        $items['data'] = InstructionDegreeDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(InstructionDegreeRequest $request)
    {
        $data = $request->validated();
        $this->instructionDegreeService->save($data);
        return ApiResponse::success($data, 'Grado de instrucción guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->instructionDegreeService->delete($id);
        return ApiResponse::success(null, 'Grado de instrucción eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->instructionDegreeService->getSelectItems();
        return ApiResponse::success(InstructionDegreeSelectItemResource::collection($items));
    }
}
