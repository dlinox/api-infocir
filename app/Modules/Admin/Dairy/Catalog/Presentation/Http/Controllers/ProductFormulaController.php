<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Catalog\Presentation\Http\Requests\ProductFormula\SaveFormulaItemRequest;
use App\Modules\Admin\Dairy\Catalog\Presentation\Http\Resources\ProductFormula\ProductFormulaItemResource;
use App\Modules\Admin\Dairy\Catalog\Presentation\Services\ProductFormulaService;

class ProductFormulaController
{
    public function __construct(
        private ProductFormulaService $productFormulaService
    ) {}

    public function getByPresentation(string $presentationId, Request $request)
    {
        $version = $request->query('version') ? (int) $request->query('version') : null;
        $data = $this->productFormulaService->getByPresentation((int) $presentationId, $version);
        $data['items'] = ProductFormulaItemResource::collection($data['items']);
        return ApiResponse::success($data);
    }

    public function getVersions(string $presentationId)
    {
        $versions = $this->productFormulaService->getVersions((int) $presentationId);
        return ApiResponse::success($versions);
    }

    public function save(SaveFormulaItemRequest $request)
    {
        $data = $request->validated();
        $this->productFormulaService->saveItem($data);
        return ApiResponse::success(null, 'Item de fórmula guardado correctamente');
    }

    public function createVersion(string $presentationId)
    {
        $newVersion = $this->productFormulaService->createVersion((int) $presentationId);
        return ApiResponse::success(['version' => $newVersion], 'Nueva versión creada correctamente');
    }

    public function delete(string $id)
    {
        $this->productFormulaService->deleteItem((int) $id);
        return ApiResponse::success(null, 'Item de fórmula eliminado correctamente');
    }
}
