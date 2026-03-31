<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\CompanyType\CompanyTypeRequest;
use App\Modules\Admin\Setting\Http\Resources\CompanyType\CompanyTypeDataTableItemResource;
use App\Modules\Admin\Setting\Http\Resources\CompanyType\CompanyTypeSelectItemResource;
use App\Modules\Admin\Setting\Services\CompanyTypeService;

class CompanyTypeController
{
    public function __construct(
        private CompanyTypeService $companyTypeService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->companyTypeService->dataTable($request);
        $items['data'] = CompanyTypeDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(CompanyTypeRequest $request)
    {
        $data = $request->validated();
        $this->companyTypeService->save($data);
        return ApiResponse::success($data, 'Tipo de empresa guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->companyTypeService->delete($id);
        return ApiResponse::success(null, 'Tipo de empresa eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->companyTypeService->getSelectItems();
        return ApiResponse::success(CompanyTypeSelectItemResource::collection($items));
    }
}
