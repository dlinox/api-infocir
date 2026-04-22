<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\AssetCatalog\AssetCatalogRequest;
use App\Modules\Admin\Setting\Http\Resources\AssetCatalog\AssetCatalogDataTableItemResource;
use App\Modules\Admin\Setting\Services\AssetCatalogService;

class AssetCatalogController
{
    public function __construct(
        private AssetCatalogService $assetCatalogService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->assetCatalogService->dataTable($request);
        $items['data'] = AssetCatalogDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(AssetCatalogRequest $request)
    {
        $data = $request->validated();
        $this->assetCatalogService->save($data);
        return ApiResponse::success(null, 'Activo del catálogo guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->assetCatalogService->delete($id);
        return ApiResponse::success(null, 'Activo del catálogo eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->assetCatalogService->getSelectItems();
        return ApiResponse::success(AssetCatalogDataTableItemResource::collection($items));
    }
}
