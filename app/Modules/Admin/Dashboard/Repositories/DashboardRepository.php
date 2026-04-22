<?php

namespace App\Modules\Admin\Dashboard\Repositories;

use App\Models\Dairy\MilkCollection;
use App\Models\Dairy\Plant;
use App\Models\Dairy\ProductionBatch;
use App\Models\Dairy\Supplier;

class DashboardRepository
{
    public function getSummary(): array
    {
        $today = now()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        $activePlants = Plant::where('is_active', true)->count();
        $activeSuppliers = Supplier::where('is_active', true)->count();

        $todayLiters = MilkCollection::whereDate('collection_date', $today)
            ->sum('quantity_liters');

        $monthLiters = MilkCollection::whereBetween('collection_date', [$monthStart, $monthEnd])
            ->sum('quantity_liters');

        $monthKg = ProductionBatch::whereBetween('production_date', [$monthStart, $monthEnd])
            ->sum('quantity_kg');

        $monthlyCollections = MilkCollection::where('collection_date', '>=', now()->subMonths(5)->startOfMonth()->toDateString())
            ->selectRaw("DATE_FORMAT(collection_date, '%Y-%m') as month_key, DATE_FORMAT(collection_date, '%b') as month_name, SUM(quantity_liters) as liters")
            ->groupBy('month_key', 'month_name')
            ->orderBy('month_key')
            ->get()
            ->map(fn($r) => [
                'month' => ucfirst($r->month_name),
                'liters' => (float) $r->liters,
            ])
            ->toArray();

        return [
            'activePlants' => $activePlants,
            'activeSuppliers' => $activeSuppliers,
            'todayLiters' => (float) $todayLiters,
            'monthLiters' => (float) $monthLiters,
            'monthKg' => (float) $monthKg,
            'monthlyCollections' => $monthlyCollections,
        ];
    }

    public function getMapData(): array
    {
        $plants = Plant::where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'name', 'trade_name', 'city', 'latitude', 'longitude', 'capacity_liters'])
            ->map(function ($p) {
                $stats = MilkCollection::where('plant_id', $p->id)
                    ->whereDate('collection_date', now()->toDateString())
                    ->selectRaw('COALESCE(SUM(quantity_liters),0) as liters_today, COUNT(DISTINCT supplier_id) as supplier_count')
                    ->first();

                return [
                    'id' => $p->id,
                    'name' => $p->trade_name ?: $p->name,
                    'type' => 'plant',
                    'lat' => (float) $p->latitude,
                    'lng' => (float) $p->longitude,
                    'city' => $p->city,
                    'capacityLiters' => (float) $p->capacity_liters,
                    'litersToday' => (float) $stats->liters_today,
                    'supplierCount' => (int) $stats->supplier_count,
                ];
            })
            ->toArray();

        $suppliers = Supplier::where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'name', 'trade_name', 'community', 'latitude', 'longitude', 'total_cows', 'cows_in_production', 'dry_cows'])
            ->map(function ($s) {
                $litersToday = MilkCollection::where('supplier_id', $s->id)
                    ->whereDate('collection_date', now()->toDateString())
                    ->sum('quantity_liters');

                return [
                    'id' => $s->id,
                    'name' => $s->trade_name ?: $s->name,
                    'type' => 'supplier',
                    'lat' => (float) $s->latitude,
                    'lng' => (float) $s->longitude,
                    'community' => $s->community,
                    'totalCows' => (int) $s->total_cows,
                    'cowsInProduction' => (int) $s->cows_in_production,
                    'dryCows' => (int) $s->dry_cows,
                    'litersToday' => (float) $litersToday,
                ];
            })
            ->toArray();

        return [
            'plants' => $plants,
            'suppliers' => $suppliers,
        ];
    }
}
