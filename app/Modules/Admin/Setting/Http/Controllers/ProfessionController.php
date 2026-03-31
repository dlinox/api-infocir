<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\Profession\ProfessionRequest;
use App\Modules\Admin\Setting\Http\Resources\Profession\ProfessionDataTableItemResource;
use App\Modules\Admin\Setting\Http\Resources\Profession\ProfessionSelectItemResource;
use App\Modules\Admin\Setting\Services\ProfessionService;

class ProfessionController
{
    public function __construct(
        private ProfessionService $professionService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->professionService->dataTable($request);
        $items['data'] = ProfessionDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(ProfessionRequest $request)
    {
        $data = $request->validated();
        $this->professionService->save($data);
        return ApiResponse::success($data, 'Profesión guardada correctamente');
    }

    public function delete(string $id)
    {
        $this->professionService->delete($id);
        return ApiResponse::success(null, 'Profesión eliminada correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->professionService->getSelectItems();
        return ApiResponse::success(ProfessionSelectItemResource::collection($items));
    }
}
