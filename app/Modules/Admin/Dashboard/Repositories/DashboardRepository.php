<?php

namespace App\Modules\Admin\Dashboard\Repositories;

use App\Models\Dairy\MilkCollection;
use App\Models\Dairy\Plant;
use App\Models\Dairy\ProductionBatch;
use App\Models\Dairy\ProductPrice;
use App\Models\Dairy\Supplier;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    private const MESES = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

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
            ->sum('quantity_units');

        $monthlyCollections = MilkCollection::where('collection_date', '>=', now()->subMonths(5)->startOfMonth()->toDateString())
            ->selectRaw("DATE_FORMAT(collection_date, '%Y-%m') as month_key, DATE_FORMAT(collection_date, '%b') as month_name, SUM(quantity_liters) as liters")
            ->groupBy('month_key', 'month_name')
            ->orderBy('month_key')
            ->get()
            ->map(fn ($r) => [
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

    /**
     * Payload completo del dashboard, todo desde datos reales.
     * Lo no presente en BD sale en 0/vacío (no hay valores fijos).
     */
    public function getOverview(): array
    {
        $today = now()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->endOfMonth()->toDateString();

        $plants = Plant::where('is_active', true)->orderBy('id')->get();
        $window = $this->monthWindow(12); // [['key'=>'2025-06','label'=>'Jun'], ...]

        $cheesePriceAvg = (float) ProductPrice::whereDate('effective_from', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('effective_until')->orWhereDate('effective_until', '>=', $today);
            })
            ->avg('price');

        // Acopio mensual por planta (12 meses)
        $collByPlantMonth = MilkCollection::where('collection_date', '>=', $window[0]['key'] . '-01')
            ->selectRaw("plant_id, DATE_FORMAT(collection_date, '%Y-%m') as ym, SUM(quantity_liters) as liters")
            ->groupBy('plant_id', 'ym')
            ->get()
            ->groupBy('plant_id');

        // Producción (queso) mensual por planta (12 meses)
        $batchByPlantMonth = ProductionBatch::where('production_date', '>=', $window[0]['key'] . '-01')
            ->selectRaw("plant_id, DATE_FORMAT(production_date, '%Y-%m') as ym, SUM(quantity_units) as units")
            ->groupBy('plant_id', 'ym')
            ->get()
            ->groupBy('plant_id');

        // Vacas en producción por planta (a través de proveedores asignados)
        $cowsByPlant = $this->cowsByPlant();

        $acopioDiarioSeries = [];
        $produccionQuesoSeries = [];
        $produccionVacaSeries = [];
        $acopioDistribucionLabels = [];
        $acopioDistribucionData = [];
        $rendimientoData = [];
        $resumenPlantas = [];
        $plantDetails = [];

        $totalAcopioDia = 0.0;
        $totalProveedores = 0;
        $totalQuesoMes = 0.0;

        foreach ($plants as $plant) {
            $name = $plant->trade_name ?: $plant->name;

            $litrosMes = $this->fillSeries($collByPlantMonth->get($plant->id), $window, 'liters');
            $quesoMes = $this->fillSeries($batchByPlantMonth->get($plant->id), $window, 'units');

            // Acopio promedio diario por mes (litros del mes / 30)
            $acopioDiarioSeries[] = ['name' => $name, 'data' => array_map(fn ($v) => round($v / 30, 1), $litrosMes)];
            $produccionQuesoSeries[] = ['name' => $name, 'data' => $quesoMes];

            // lt/vaca/día por mes
            $cows = $cowsByPlant[$plant->id] ?? 0;
            $produccionVacaSeries[] = [
                'name' => $name,
                'data' => array_map(fn ($v) => $cows > 0 ? round($v / 30 / $cows, 1) : 0, $litrosMes),
            ];

            // Distribución de acopio: litros del mes actual
            $litersMonth = MilkCollection::where('plant_id', $plant->id)
                ->whereBetween('collection_date', [$monthStart, $monthEnd])
                ->sum('quantity_liters');
            $acopioDistribucionLabels[] = $name;
            $acopioDistribucionData[] = round((float) $litersMonth, 0);

            // Rendimiento leche->queso del mes (lt/kg)
            $kgMonth = ProductionBatch::where('plant_id', $plant->id)
                ->whereBetween('production_date', [$monthStart, $monthEnd])
                ->sum('quantity_units');
            $rendimientoData[] = $kgMonth > 0 ? round($litersMonth / $kgMonth, 1) : 0;

            // Resumen por planta
            $litersToday = (float) MilkCollection::where('plant_id', $plant->id)
                ->whereDate('collection_date', $today)
                ->sum('quantity_liters');
            $supplierCount = DB::table('dairy_plant_suppliers')->where('plant_id', $plant->id)->where('is_active', true)->count();

            $resumenPlantas[] = [
                'nombre' => $name,
                'acopioDia' => round($litersToday, 0),
                'proveedores' => $supplierCount,
                'quesoMes' => round((float) $kgMonth, 0),
            ];
            $totalAcopioDia += $litersToday;
            $totalProveedores += $supplierCount;
            $totalQuesoMes += (float) $kgMonth;

            $plantDetails[(string) $plant->id] = $this->plantDetail($plant, $litersToday, (float) $litersMonth, (float) $kgMonth, $supplierCount, $cheesePriceAvg);
        }

        // Top 10 proveedores por acopio del mes
        $topProveedores = $this->topSuppliers($monthStart, $monthEnd);

        // Precio de leche al productor (promedio mensual, 12 meses)
        $precioLeche = $this->milkPriceSeries($window);

        $map = $this->buildMap($plants, $cowsByPlant);

        return [
            'kpis' => [
                'activePlants' => $plants->count(),
                'activeSuppliers' => Supplier::where('is_active', true)->count(),
                'todayLiters' => round($totalAcopioDia, 0),
                'monthLiters' => round((float) MilkCollection::whereBetween('collection_date', [$monthStart, $monthEnd])->sum('quantity_liters'), 0),
                'cowsInProduction' => (int) Supplier::where('is_active', true)->sum('cows_in_production'),
                'avgLitersPerCowDay' => $this->avgLitersPerCowDay($today),
            ],
            'map' => $map,
            'charts' => [
                'acopioDiario' => ['categories' => array_column($window, 'label'), 'series' => $acopioDiarioSeries],
                'acopioDistribucion' => [
                    'labels' => $acopioDistribucionLabels,
                    'series' => $acopioDistribucionData,
                    'total' => round(array_sum($acopioDistribucionData), 0),
                ],
                'produccionVaca' => ['categories' => array_column($window, 'label'), 'series' => $produccionVacaSeries],
                'topProveedores' => $topProveedores,
                'produccionQueso' => ['categories' => array_column($window, 'label'), 'series' => $produccionQuesoSeries],
                'precioLeche' => $precioLeche,
                'rendimiento' => ['categories' => $acopioDistribucionLabels, 'data' => $rendimientoData],
            ],
            'resumenPlantas' => $resumenPlantas,
            'resumenTotales' => [
                'acopioDia' => round($totalAcopioDia, 0),
                'proveedores' => $totalProveedores,
                'quesoMes' => round($totalQuesoMes, 0),
            ],
            'plantDetails' => $plantDetails,
            'supplierDetails' => $map['supplierDetails'],
        ];
    }

    /* ── Helpers ─────────────────────────────────────────────── */

    private function monthWindow(int $count): array
    {
        $window = [];
        for ($i = $count - 1; $i >= 0; $i--) {
            $date = now()->startOfMonth()->subMonthsNoOverflow($i);
            $window[] = [
                'key' => $date->format('Y-m'),
                'label' => self::MESES[$date->month - 1],
            ];
        }
        return $window;
    }

    private function fillSeries($rows, array $window, string $field): array
    {
        $byKey = [];
        foreach (($rows ?? collect()) as $row) {
            $byKey[$row->ym] = (float) $row->{$field};
        }
        return array_map(fn ($m) => round($byKey[$m['key']] ?? 0, 1), $window);
    }

    private function cowsByPlant(): array
    {
        return DB::table('dairy_plant_suppliers')
            ->where('dairy_plant_suppliers.is_active', true)
            ->join('dairy_suppliers', 'dairy_suppliers.id', '=', 'dairy_plant_suppliers.supplier_id')
            ->where('dairy_suppliers.is_active', true)
            ->selectRaw('dairy_plant_suppliers.plant_id, SUM(dairy_suppliers.cows_in_production) as cows')
            ->groupBy('dairy_plant_suppliers.plant_id')
            ->pluck('cows', 'plant_id')
            ->map(fn ($v) => (int) $v)
            ->toArray();
    }

    private function avgLitersPerCowDay(string $today): float
    {
        $cows = (int) Supplier::where('is_active', true)->sum('cows_in_production');
        if ($cows <= 0) {
            return 0;
        }
        $liters = (float) MilkCollection::whereDate('collection_date', $today)->sum('quantity_liters');
        return round($liters / $cows, 1);
    }

    private function topSuppliers(string $monthStart, string $monthEnd): array
    {
        $rows = MilkCollection::whereBetween('collection_date', [$monthStart, $monthEnd])
            ->join('dairy_suppliers', 'dairy_suppliers.id', '=', 'dairy_milk_collections.supplier_id')
            ->selectRaw('dairy_suppliers.trade_name, dairy_suppliers.name, SUM(dairy_milk_collections.quantity_liters) as liters')
            ->groupBy('dairy_suppliers.id', 'dairy_suppliers.trade_name', 'dairy_suppliers.name')
            ->orderByDesc('liters')
            ->limit(10)
            ->get();

        return [
            'categories' => $rows->map(fn ($r) => $r->trade_name ?: $r->name)->toArray(),
            'data' => $rows->map(fn ($r) => round((float) $r->liters, 0))->toArray(),
        ];
    }

    private function milkPriceSeries(array $window): array
    {
        $rows = MilkCollection::where('collection_date', '>=', $window[0]['key'] . '-01')
            ->selectRaw("DATE_FORMAT(collection_date, '%Y-%m') as ym, AVG(price_per_liter) as price")
            ->groupBy('ym')
            ->get();
        $byKey = [];
        foreach ($rows as $row) {
            $byKey[$row->ym] = round((float) $row->price, 2);
        }
        return [
            'categories' => array_column($window, 'label'),
            'series' => [
                ['name' => 'Precio pagado', 'data' => array_map(fn ($m) => $byKey[$m['key']] ?? 0, $window)],
            ],
        ];
    }

    private function plantDetail(Plant $plant, float $litersToday, float $litersMonth, float $kgMonth, int $supplierCount, float $cheesePriceAvg): array
    {
        $stockReady = (int) ProductionBatch::where('plant_id', $plant->id)->where('status', 'ready')->sum('quantity_units');
        $aging = (int) ProductionBatch::where('plant_id', $plant->id)->where('status', 'maturing')->sum('quantity_units');
        $soldMonth = (int) ProductionBatch::where('plant_id', $plant->id)
            ->where('status', 'sold')
            ->whereBetween('production_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
            ->sum('quantity_units');

        $capacity = (float) $plant->capacity_liters;
        $avgPrice = (float) MilkCollection::where('plant_id', $plant->id)
            ->whereDate('collection_date', now()->toDateString())
            ->avg('price_per_liter');

        $lots = ProductionBatch::where('plant_id', $plant->id)
            ->orderByDesc('production_date')
            ->limit(5)
            ->get(['batch_code', 'production_date', 'quantity_units', 'status'])
            ->map(fn ($b) => [
                'code' => $b->batch_code,
                'date' => optional($b->production_date)->format('Y-m-d'),
                'kg' => (int) $b->quantity_units,
                'status' => $this->batchStatusLabel($b->status),
            ])
            ->toArray();

        return [
            'name' => $plant->trade_name ?: $plant->name,
            'location' => $plant->city,
            'manager' => $plant->owner_name,
            'litersToday' => round($litersToday, 0),
            'avgLitersDay' => round($litersMonth / 30, 0),
            'litersMonth' => round($litersMonth, 0),
            'cheeseMonth' => round($kgMonth, 0),
            'yieldRatio' => $kgMonth > 0 ? round($litersMonth / $kgMonth, 1) : 0,
            'cheeseStock' => $stockReady,
            'cheeseAging' => $aging,
            'cheeseSoldMonth' => $soldMonth,
            'avgCheesePrice' => round($cheesePriceAvg, 2),
            'totalSuppliers' => $supplierCount,
            'capacityLiters' => round($capacity, 0),
            'capacityUsage' => $capacity > 0 ? round(($litersToday / $capacity) * 100, 0) : 0,
            'avgPricePerLiter' => round($avgPrice, 2),
            'isActive' => (bool) $plant->is_active,
            'lots' => $lots,
        ];
    }

    private function batchStatusLabel(string $status): string
    {
        return match ($status) {
            'ready' => 'listo',
            'maturing' => 'maduración',
            'sold' => 'vendido',
            'in_production' => 'en producción',
            'rejected' => 'rechazado',
            default => $status,
        };
    }

    private function buildMap(\Illuminate\Support\Collection $plants, array $cowsByPlant): array
    {
        $today = now()->toDateString();

        $plantMarkers = [];
        $supplierMarkers = [];
        $supplierDetails = [];
        $areas = [];

        $suppliersByPlant = DB::table('dairy_plant_suppliers')
            ->where('dairy_plant_suppliers.is_active', true)
            ->join('dairy_suppliers', 'dairy_suppliers.id', '=', 'dairy_plant_suppliers.supplier_id')
            ->where('dairy_suppliers.is_active', true)
            ->whereNotNull('dairy_suppliers.latitude')
            ->whereNotNull('dairy_suppliers.longitude')
            ->get([
                'dairy_plant_suppliers.plant_id',
                'dairy_suppliers.id',
                'dairy_suppliers.name',
                'dairy_suppliers.trade_name',
                'dairy_suppliers.community',
                'dairy_suppliers.latitude',
                'dairy_suppliers.longitude',
                'dairy_suppliers.total_cows',
                'dairy_suppliers.cows_in_production',
                'dairy_suppliers.dry_cows',
                'dairy_suppliers.reference_price_per_liter',
            ])
            ->groupBy('plant_id');

        foreach ($plants as $plant) {
            if ($plant->latitude === null || $plant->longitude === null) {
                continue;
            }
            $litersToday = (float) MilkCollection::where('plant_id', $plant->id)
                ->whereDate('collection_date', $today)
                ->sum('quantity_liters');
            $plantMarkers[] = [
                'id' => $plant->id,
                'name' => $plant->trade_name ?: $plant->name,
                'lat' => (float) $plant->latitude,
                'lng' => (float) $plant->longitude,
                'litersToday' => round($litersToday, 0),
                'cheeseStock' => (int) ProductionBatch::where('plant_id', $plant->id)->where('status', 'ready')->sum('quantity_units'),
            ];

            $plantSuppliers = $suppliersByPlant->get($plant->id) ?? collect();
            $coords = [];
            foreach ($plantSuppliers as $s) {
                $litersTodayS = (float) MilkCollection::where('supplier_id', $s->id)
                    ->whereDate('collection_date', $today)
                    ->sum('quantity_liters');
                $supplierMarkers[] = [
                    'id' => $s->id,
                    'name' => $s->trade_name ?: $s->name,
                    'lat' => (float) $s->latitude,
                    'lng' => (float) $s->longitude,
                    'plantId' => $plant->id,
                    'plantName' => $plant->trade_name ?: $plant->name,
                    'litersToday' => round($litersTodayS, 0),
                    'totalCows' => (int) $s->total_cows,
                ];
                $supplierDetails[(string) $s->id] = [
                    'name' => $s->trade_name ?: $s->name,
                    'plant' => $plant->trade_name ?: $plant->name,
                    'community' => $s->community,
                    'totalCows' => (int) $s->total_cows,
                    'cowsInProduction' => (int) $s->cows_in_production,
                    'dryCows' => (int) $s->dry_cows,
                    'litersToday' => round($litersTodayS, 0),
                    'avgLitersDay' => round((float) MilkCollection::where('supplier_id', $s->id)
                        ->whereBetween('collection_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                        ->avg('quantity_liters'), 0),
                    'litersMonth' => round((float) MilkCollection::where('supplier_id', $s->id)
                        ->whereBetween('collection_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])
                        ->sum('quantity_liters'), 0),
                    'litersPerCow' => $s->cows_in_production > 0
                        ? round(((float) MilkCollection::where('supplier_id', $s->id)->whereDate('collection_date', $today)->sum('quantity_liters')) / $s->cows_in_production, 1)
                        : 0,
                    'pricePerLiter' => round((float) $s->reference_price_per_liter, 2),
                    'isActive' => true,
                ];
                $coords[] = [(float) $s->latitude, (float) $s->longitude];
            }

            // Área de acopio = círculo que cubre a los proveedores de la planta
            $area = $this->collectionArea((float) $plant->latitude, (float) $plant->longitude, $coords);
            if ($area !== null) {
                $areas[] = ['plantId' => $plant->id] + $area;
            }
        }

        return [
            'plants' => $plantMarkers,
            'suppliers' => $supplierMarkers,
            'areas' => $areas,
            'supplierDetails' => $supplierDetails,
        ];
    }

    /**
     * Centro y radio (km) que cubre a los proveedores de una planta.
     * Sin proveedores con coordenadas → no hay área.
     */
    private function collectionArea(float $plantLat, float $plantLng, array $coords): ?array
    {
        if (count($coords) === 0) {
            return null;
        }
        $maxKm = 0.0;
        foreach ($coords as [$lat, $lng]) {
            $maxKm = max($maxKm, $this->haversineKm($plantLat, $plantLng, $lat, $lng));
        }
        return [
            'lat' => $plantLat,
            'lng' => $plantLng,
            'radiusKm' => round(max($maxKm, 0.3), 2),
        ];
    }

    private function haversineKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earth = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return $earth * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    public function getMapData(): array
    {
        $map = $this->buildMap(Plant::where('is_active', true)->orderBy('id')->get(), $this->cowsByPlant());
        return ['plants' => $map['plants'], 'suppliers' => $map['suppliers']];
    }
}
