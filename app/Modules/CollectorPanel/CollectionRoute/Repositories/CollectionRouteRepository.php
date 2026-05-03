<?php

namespace App\Modules\CollectorPanel\CollectionRoute\Repositories;

use App\Models\Dairy\CollectionRoute;

class CollectionRouteRepository
{
    public function getActive(int $plantId, int $collectorId): ?CollectionRoute
    {
        return CollectionRoute::where('plant_id', $plantId)
            ->where('collector_id', $collectorId)
            ->where('status', 'active')
            ->latest('started_at')
            ->first();
    }

    public function create(array $data): CollectionRoute
    {
        return CollectionRoute::create($data);
    }

    public function finalize(CollectionRoute $route, array $data): CollectionRoute
    {
        $route->update(array_merge($data, [
            'ended_at' => now(),
            'status'   => 'completed',
        ]));
        return $route;
    }

    public function findByIdAndPlant(int $id, int $plantId): ?CollectionRoute
    {
        return CollectionRoute::where('id', $id)
            ->where('plant_id', $plantId)
            ->first();
    }

    public function dataTable($request, int $plantId, int $collectorId)
    {
        $dateFrom = $request->input('dateFrom');
        $dateTo   = $request->input('dateTo');

        $query = CollectionRoute::where('plant_id', $plantId)
            ->where('collector_id', $collectorId)
            ->when($dateFrom, fn($q) => $q->whereDate('started_at', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('started_at', '<=', $dateTo))
            ->selectRaw('dairy_collection_routes.*,
                (SELECT COUNT(*) FROM dairy_milk_collections WHERE dairy_milk_collections.collection_route_id = dairy_collection_routes.id) AS collections_count,
                (SELECT COALESCE(SUM(quantity_liters), 0) FROM dairy_milk_collections WHERE dairy_milk_collections.collection_route_id = dairy_collection_routes.id) AS total_liters')
            ->orderBy('started_at', 'desc');

        return $query->dataTable($request);
    }

    public function stats(int $plantId, int $collectorId, ?string $dateFrom, ?string $dateTo): array
    {
        $base = CollectionRoute::where('plant_id', $plantId)
            ->where('collector_id', $collectorId)
            ->when($dateFrom, fn($q) => $q->whereDate('started_at', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->whereDate('started_at', '<=', $dateTo));

        $totalRoutes     = (clone $base)->count();
        $completedRoutes = (clone $base)->where('status', 'completed')->count();

        $agg = (clone $base)
            ->where('status', 'completed')
            ->selectRaw("
                COALESCE(AVG(TIMESTAMPDIFF(MINUTE, started_at, ended_at)), 0)      AS avg_duration,
                COALESCE(SUM((SELECT COALESCE(SUM(quantity_liters),0) FROM dairy_milk_collections mc WHERE mc.collection_route_id = dairy_collection_routes.id)), 0) AS total_liters,
                COALESCE(AVG((SELECT COALESCE(SUM(quantity_liters),0) FROM dairy_milk_collections mc WHERE mc.collection_route_id = dairy_collection_routes.id)), 0) AS avg_liters,
                COALESCE(SUM((SELECT COUNT(*) FROM dairy_milk_collections mc WHERE mc.collection_route_id = dairy_collection_routes.id)), 0)                        AS total_collections,
                COALESCE(AVG((SELECT COUNT(*) FROM dairy_milk_collections mc WHERE mc.collection_route_id = dairy_collection_routes.id)), 0)                        AS avg_collections
            ")
            ->first();

        return [
            'totalRoutes'            => $totalRoutes,
            'completedRoutes'        => $completedRoutes,
            'totalLiters'            => round((float) $agg->total_liters, 1),
            'avgLitersPerRoute'      => round((float) $agg->avg_liters, 1),
            'avgDurationMinutes'     => (int) round((float) $agg->avg_duration),
            'totalCollections'       => (int) $agg->total_collections,
            'avgCollectionsPerRoute' => round((float) $agg->avg_collections, 1),
        ];
    }
}
