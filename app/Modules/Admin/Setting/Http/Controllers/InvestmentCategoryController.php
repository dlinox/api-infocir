<?php

namespace App\Modules\Admin\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Setting\Http\Requests\InvestmentCategory\InvestmentCategoryRequest;
use App\Modules\Admin\Setting\Http\Resources\InvestmentCategory\InvestmentCategoryDataTableItemResource;
use App\Modules\Admin\Setting\Services\InvestmentCategoryService;

class InvestmentCategoryController
{
    public function __construct(
        private InvestmentCategoryService $investmentCategoryService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->investmentCategoryService->dataTable($request);
        $items['data'] = InvestmentCategoryDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(InvestmentCategoryRequest $request)
    {
        $data = $request->validated();
        $this->investmentCategoryService->save($data);
        return ApiResponse::success(null, 'Categoría de inversión guardada correctamente');
    }

    public function delete(string $id)
    {
        $this->investmentCategoryService->delete($id);
        return ApiResponse::success(null, 'Categoría de inversión eliminada correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->investmentCategoryService->getSelectItems();
        return ApiResponse::success(InvestmentCategoryDataTableItemResource::collection($items));
    }
}
