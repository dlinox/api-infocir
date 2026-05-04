<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\WorkingCapitalCatalog\WorkingCapitalCatalogRequest;
use App\Modules\Admin\Setting\Http\Resources\WorkingCapitalCatalog\WorkingCapitalCatalogDataTableItemResource;
use App\Modules\Admin\Setting\Services\WorkingCapitalCatalogService;

class WorkingCapitalCatalogController
{
    public function __construct(
        private WorkingCapitalCatalogService $service
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->service->dataTable($request);
        $items['data'] = WorkingCapitalCatalogDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(WorkingCapitalCatalogRequest $request)
    {
        $this->service->save($request->validated());
        return ApiResponse::success(null, 'Insumo guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->service->delete($id);
        return ApiResponse::success(null, 'Insumo eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->service->getSelectItems();
        return ApiResponse::success(WorkingCapitalCatalogDataTableItemResource::collection($items));
    }
}
