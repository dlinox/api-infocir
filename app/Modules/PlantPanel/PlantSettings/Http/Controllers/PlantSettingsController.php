<?php

namespace App\Modules\PlantPanel\PlantSettings\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\PlantPanel\PlantSettings\Http\Requests\PlantSettingsRequest;
use App\Modules\PlantPanel\PlantSettings\Http\Resources\PlantSettingsResource;
use App\Modules\PlantPanel\PlantSettings\Services\PlantSettingsService;

class PlantSettingsController
{
    public function __construct(
        private PlantSettingsService $service,
    ) {}

    public function get(): JsonResponse
    {
        $plant = $this->service->get();
        return ApiResponse::success(new PlantSettingsResource($plant));
    }

    public function update(PlantSettingsRequest $request): JsonResponse
    {
        $plant = $this->service->update($request->validated());
        return ApiResponse::success(new PlantSettingsResource($plant), 'Configuración guardada correctamente');
    }
}
