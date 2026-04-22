<?php

namespace App\Modules\PlantPanel\Dashboard\Repositories;

use App\Models\Dairy\MilkCollection;
use App\Models\Dairy\ProductionBatch;
use App\Models\Dairy\SupplierPayment;

class DashboardRepository
{
    public function getSummary(int $plantId): array
    {
        $today = now()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        $todayStats = MilkCollection::where('plant_id', $plantId)
            ->whereDate('collection_date', $today)
            ->selectRaw('COALESCE(SUM(quantity_liters), 0) as liters, COUNT(*) as count, COUNT(DISTINCT supplier_id) as supplier_count')
            ->first();

        $monthStats = MilkCollection::where('plant_id', $plantId)
            ->whereBetween('collection_date', [$monthStart, $monthEnd])
            ->selectRaw('COALESCE(SUM(quantity_liters), 0) as liters, COUNT(DISTINCT supplier_id) as supplier_count, COALESCE(SUM(total_amount), 0) as total_amount')
            ->first();

        $pendingPayments = SupplierPayment::where('plant_id', $plantId)
            ->whereIn('status', ['pending', 'approved'])
            ->selectRaw('COUNT(*) as count, COALESCE(SUM(net_amount), 0) as amount')
            ->first();

        $activeBatches = ProductionBatch::where('plant_id', $plantId)
            ->whereIn('status', ['in_production', 'maturing'])
            ->count();

        $monthlyKg = ProductionBatch::where('plant_id', $plantId)
            ->whereBetween('production_date', [$monthStart, $monthEnd])
            ->selectRaw('COALESCE(SUM(quantity_kg), 0) as kg')
            ->value('kg');

        $activeSuppliers = MilkCollection::where('plant_id', $plantId)
            ->distinct('supplier_id')
            ->count('supplier_id');

        $weeklyData = MilkCollection::where('plant_id', $plantId)
            ->whereBetween('collection_date', [now()->subDays(6)->toDateString(), $today])
            ->selectRaw('collection_date, SUM(quantity_liters) as liters')
            ->groupBy('collection_date')
            ->orderBy('collection_date')
            ->get()
            ->keyBy(fn($r) => $r->collection_date->toDateString());

        $weeklyCollections = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dayName = now()->subDays($i)->locale('es')->isoFormat('ddd');
            $weeklyCollections[] = [
                'date' => $date,
                'dayName' => ucfirst($dayName),
                'liters' => (float) ($weeklyData[$date]->liters ?? 0),
            ];
        }

        $monthlyCollections = MilkCollection::where('plant_id', $plantId)
            ->where('collection_date', '>=', now()->subMonths(5)->startOfMonth()->toDateString())
            ->selectRaw("DATE_FORMAT(collection_date, '%Y-%m') as month_key, DATE_FORMAT(collection_date, '%b') as month_name, SUM(quantity_liters) as liters")
            ->groupBy('month_key', 'month_name')
            ->orderBy('month_key')
            ->get()
            ->map(fn($r) => [
                'month' => ucfirst($r->month_name),
                'liters' => (float) $r->liters,
            ])
            ->toArray();

        $recentCollections = MilkCollection::where('dairy_milk_collections.plant_id', $plantId)
            ->leftJoin('dairy_suppliers', 'dairy_suppliers.id', '=', 'dairy_milk_collections.supplier_id')
            ->orderBy('dairy_milk_collections.collection_date', 'desc')
            ->orderBy('dairy_milk_collections.id', 'desc')
            ->limit(5)
            ->select([
                'dairy_milk_collections.id',
                'dairy_milk_collections.collection_date',
                'dairy_milk_collections.quantity_liters',
                'dairy_milk_collections.payment_status',
                'dairy_suppliers.name as supplier_name',
                'dairy_suppliers.trade_name as supplier_trade_name',
            ])
            ->get()
            ->map(fn($r) => [
                'id' => $r->id,
                'date' => $r->collection_date->toDateString(),
                'liters' => (float) $r->quantity_liters,
                'paymentStatus' => $r->payment_status,
                'supplierName' => $r->supplier_trade_name ?: $r->supplier_name,
            ])
            ->toArray();

        return [
            'today' => [
                'liters' => (float) $todayStats->liters,
                'collectionsCount' => (int) $todayStats->count,
                'supplierCount' => (int) $todayStats->supplier_count,
            ],
            'month' => [
                'liters' => (float) $monthStats->liters,
                'supplierCount' => (int) $monthStats->supplier_count,
                'totalAmount' => (float) $monthStats->total_amount,
            ],
            'production' => [
                'activeBatches' => (int) $activeBatches,
                'kgThisMonth' => (float) $monthlyKg,
            ],
            'payments' => [
                'pendingCount' => (int) $pendingPayments->count,
                'pendingAmount' => (float) $pendingPayments->amount,
            ],
            'activeSuppliers' => (int) $activeSuppliers,
            'weeklyCollections' => $weeklyCollections,
            'monthlyCollections' => $monthlyCollections,
            'recentCollections' => $recentCollections,
        ];
    }
}
