<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\PreOperativeCatalog\PreOperativeCatalogRequest;
use App\Modules\Admin\Setting\Http\Resources\PreOperativeCatalog\PreOperativeCatalogDataTableItemResource;
use App\Modules\Admin\Setting\Services\PreOperativeCatalogService;

class PreOperativeCatalogController
{
    public function __construct(
        private PreOperativeCatalogService $preOperativeCatalogService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->preOperativeCatalogService->dataTable($request);
        $items['data'] = PreOperativeCatalogDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(PreOperativeCatalogRequest $request)
    {
        $this->preOperativeCatalogService->save($request->validated());
        return ApiResponse::success(null, 'Catálogo pre-operativo guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->preOperativeCatalogService->delete($id);
        return ApiResponse::success(null, 'Catálogo pre-operativo eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->preOperativeCatalogService->getSelectItems();
        return ApiResponse::success(PreOperativeCatalogDataTableItemResource::collection($items));
    }
}
