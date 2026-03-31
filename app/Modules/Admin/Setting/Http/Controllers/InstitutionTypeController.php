<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\InstitutionType\InstitutionTypeRequest;
use App\Modules\Admin\Setting\Http\Resources\InstitutionType\InstitutionTypeDataTableItemResource;
use App\Modules\Admin\Setting\Http\Resources\InstitutionType\InstitutionTypeSelectItemResource;
use App\Modules\Admin\Setting\Services\InstitutionTypeService;

class InstitutionTypeController
{
    public function __construct(
        private InstitutionTypeService $institutionTypeService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->institutionTypeService->dataTable($request);
        $items['data'] = InstitutionTypeDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(InstitutionTypeRequest $request)
    {
        $data = $request->validated();
        $this->institutionTypeService->save($data);
        return ApiResponse::success($data, 'Tipo de institución guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->institutionTypeService->delete($id);
        return ApiResponse::success(null, 'Tipo de institución eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->institutionTypeService->getSelectItems();
        return ApiResponse::success(InstitutionTypeSelectItemResource::collection($items));
    }
}
