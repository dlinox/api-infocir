<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\Gender\GenderRequest;
use App\Modules\Admin\Setting\Http\Resources\Gender\GenderDataTableItemResource;
use App\Modules\Admin\Setting\Http\Resources\Gender\GenderSelectItemResource;
use App\Modules\Admin\Setting\Services\GenderService;

class GenderController
{
    public function __construct(
        private GenderService $genderService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->genderService->dataTable($request);
        $items['data'] = GenderDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(GenderRequest $request)
    {
        $data = $request->validated();
        $this->genderService->save($data);
        return ApiResponse::success($data, 'Género guardado correctamente');
    }

    public function delete(string $code)
    {
        $this->genderService->delete($code);
        return ApiResponse::success(null, 'Género eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->genderService->getSelectItems();
        return ApiResponse::success(GenderSelectItemResource::collection($items));
    }
}
