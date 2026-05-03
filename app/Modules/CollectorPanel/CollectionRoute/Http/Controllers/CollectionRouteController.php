<?php

namespace App\Modules\CollectorPanel\CollectionRoute\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\CollectorPanel\CollectionRoute\Http\Requests\StartRouteRequest;
use App\Modules\CollectorPanel\CollectionRoute\Http\Requests\FinalizeRouteRequest;
use App\Modules\CollectorPanel\CollectionRoute\Http\Resources\CollectionRouteResource;
use App\Modules\CollectorPanel\CollectionRoute\Services\CollectionRouteService;

class CollectionRouteController
{
    public function __construct(
        private CollectionRouteService $service,
    ) {}

    public function active(): JsonResponse
    {
        $route = $this->service->getActive();
        return ApiResponse::success(
            $route ? new CollectionRouteResource($route) : null,
            ''
        );
    }

    public function start(StartRouteRequest $request): JsonResponse
    {
        $route = $this->service->start($request->validated());
        return ApiResponse::success(new CollectionRouteResource($route), 'Recorrido iniciado correctamente');
    }

    public function finalize(FinalizeRouteRequest $request, int $routeId): JsonResponse
    {
        $route = $this->service->finalize($routeId, $request->validated());
        return ApiResponse::success(new CollectionRouteResource($route), 'Recorrido finalizado correctamente');
    }

    public function dataTable(): JsonResponse
    {
        $result = $this->service->dataTable(request());
        $result['data'] = CollectionRouteResource::collection($result['data']);
        return ApiResponse::success($result, '');
    }

    public function stats(): JsonResponse
    {
        $dateFrom = request()->query('dateFrom');
        $dateTo   = request()->query('dateTo');
        $data = $this->service->stats($dateFrom ?: null, $dateTo ?: null);
        return ApiResponse::success($data, '');
    }
}
