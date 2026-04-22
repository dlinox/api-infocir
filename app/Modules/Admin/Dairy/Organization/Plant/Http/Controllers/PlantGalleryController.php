<?php

namespace App\Modules\Admin\Dairy\Organization\Plant\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Dairy\Organization\Plant\Http\Requests\PlantGallery\PlantGalleryRequest;
use App\Modules\Admin\Dairy\Organization\Plant\Http\Resources\PlantGallery\PlantGalleryItemResource;
use App\Modules\Admin\Dairy\Organization\Plant\Services\PlantGalleryService;

class PlantGalleryController
{
    public function __construct(
        private PlantGalleryService $plantGalleryService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->plantGalleryService->dataTable($request);
        $items['data'] = PlantGalleryItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(PlantGalleryRequest $request)
    {
        $data = $request->validated();
        $this->plantGalleryService->save($data);
        return ApiResponse::success(null, 'Imagen guardada correctamente');
    }

    public function delete(int $id)
    {
        $this->plantGalleryService->delete($id);
        return ApiResponse::success(null, 'Imagen eliminada correctamente');
    }
}
