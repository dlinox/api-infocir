<?php

namespace App\Modules\SupplierPanel\Dashboard\Repositories;

use App\Models\Dairy\MilkCollection;
use App\Models\Dairy\Supplier;
use App\Models\Dairy\SupplierCattleBreed;
use App\Models\Dairy\SupplierMilkRegistration;
use App\Models\Dairy\SupplierPayment;

class DashboardRepository
{
    public function getSummary(int $supplierId): array
    {
        $today = now()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        $todayRegistered = SupplierMilkRegistration::where('supplier_id', $supplierId)
            ->whereDate('registration_date', $today)
            ->sum('quantity_liters');

        $todayDelivered = MilkCollection::where('supplier_id', $supplierId)
            ->whereDate('collection_date', $today)
            ->sum('quantity_liters');

        $monthRegistered = SupplierMilkRegistration::where('supplier_id', $supplierId)
            ->whereBetween('registration_date', [$monthStart, $monthEnd])
            ->sum('quantity_liters');

        $monthDelivered = MilkCollection::where('supplier_id', $supplierId)
            ->whereBetween('collection_date', [$monthStart, $monthEnd])
            ->sum('quantity_liters');

        $monthEarnings = SupplierPayment::where('supplier_id', $supplierId)
            ->where('status', 'paid')
            ->where(function ($q) use ($monthStart, $monthEnd) {
                $q->whereBetween('period_start', [$monthStart, $monthEnd])
                  ->orWhereBetween('period_end', [$monthStart, $monthEnd]);
            })
            ->sum('net_amount');

        $supplier = Supplier::find($supplierId, ['total_cows', 'cows_in_production', 'dry_cows', 'reference_price_per_liter']);

        $cattleBreeds = SupplierCattleBreed::where('supplier_id', $supplierId)
            ->where('is_active', true)
            ->count();

        $weeklyData = SupplierMilkRegistration::where('supplier_id', $supplierId)
            ->whereBetween('registration_date', [now()->subDays(6)->toDateString(), $today])
            ->selectRaw('registration_date, SUM(quantity_liters) as liters')
            ->groupBy('registration_date')
            ->orderBy('registration_date')
            ->get()
            ->keyBy(fn($r) => $r->registration_date->toDateString());

        $weeklyRegistrations = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dayName = now()->subDays($i)->locale('es')->isoFormat('ddd');
            $weeklyRegistrations[] = [
                'date' => $date,
                'dayName' => ucfirst($dayName),
                'liters' => (float) ($weeklyData[$date]->liters ?? 0),
            ];
        }

        $monthlyDeliveries = MilkCollection::where('supplier_id', $supplierId)
            ->where('collection_date', '>=', now()->subMonths(5)->startOfMonth()->toDateString())
            ->selectRaw("DATE_FORMAT(collection_date, '%Y-%m') as month_key, DATE_FORMAT(collection_date, '%b') as month_name, SUM(quantity_liters) as liters, SUM(total_amount) as amount")
            ->groupBy('month_key', 'month_name')
            ->orderBy('month_key')
            ->get()
            ->map(fn($r) => [
                'month' => ucfirst($r->month_name),
                'liters' => (float) $r->liters,
                'amount' => (float) $r->amount,
            ])
            ->toArray();

        $recentRegistrations = SupplierMilkRegistration::where('supplier_id', $supplierId)
            ->orderBy('registration_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get(['id', 'registration_date', 'quantity_liters', 'shift', 'number_of_cows'])
            ->map(fn($r) => [
                'id' => $r->id,
                'date' => $r->registration_date->toDateString(),
                'liters' => (float) $r->quantity_liters,
                'shift' => $r->shift,
                'numberOfCows' => $r->number_of_cows,
            ])
            ->toArray();

        return [
            'today' => [
                'milkRegistered' => (float) $todayRegistered,
                'milkDelivered' => (float) $todayDelivered,
            ],
            'month' => [
                'milkRegistered' => (float) $monthRegistered,
                'milkDelivered' => (float) $monthDelivered,
                'earnings' => (float) $monthEarnings,
            ],
            'cattle' => [
                'totalCows' => (int) ($supplier?->total_cows ?? 0),
                'cowsInProduction' => (int) ($supplier?->cows_in_production ?? 0),
                'dryCows' => (int) ($supplier?->dry_cows ?? 0),
                'referencePricePerLiter' => (float) ($supplier?->reference_price_per_liter ?? 0),
                'cattleBreeds' => (int) $cattleBreeds,
            ],
            'weeklyRegistrations' => $weeklyRegistrations,
            'monthlyDeliveries' => $monthlyDeliveries,
            'recentRegistrations' => $recentRegistrations,
        ];
    }
}
