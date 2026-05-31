<?php

namespace App\Modules\Admin\Dashboard\Services;

use App\Models\Dairy\BusinessPlan;
use App\Modules\Admin\Dairy\Finance\BusinessPlan\Services\BusinessPlanService;
use App\Modules\Admin\Dashboard\Repositories\DashboardRepository;

class DashboardService
{
    public function __construct(
        private DashboardRepository $dashboardRepository,
        private BusinessPlanService $businessPlanService
    ) {}

    public function getSummary(): array
    {
        return $this->dashboardRepository->getSummary();
    }

    public function getMapData(): array
    {
        return $this->dashboardRepository->getMapData();
    }

    public function getOverview(): array
    {
        $overview = $this->dashboardRepository->getOverview();
        $finanzas = $this->buildFinanzas();

        $overview['kpis']['van'] = $finanzas['van'];
        $overview['kpis']['tir'] = $finanzas['tir'];
        $overview['kpis']['initialInvestment'] = $finanzas['inversionInicial'];
        $overview['kpis']['paybackMonths'] = $finanzas['paybackMeses'];
        $overview['finanzas'] = $finanzas;

        return $overview;
    }

    /**
     * Finanzas del dashboard = agregado de los business plans GUARDADOS de las plantas.
     * Sin planes guardados → todo en 0/vacío (no hay valores fijos).
     */
    private function buildFinanzas(): array
    {
        $plantIds = BusinessPlan::pluck('plant_id')->all();

        $months = 12;
        $flujoNeto = array_fill(0, $months, 0.0);
        $ventasMensual = array_fill(0, $months, 0.0);
        $costosMensual = array_fill(0, $months, 0.0);
        $inversionTotal = 0.0;
        $vanTotal = 0.0;
        $estructura = []; // grupo => subtotal

        foreach ($plantIds as $plantId) {
            $computed = $this->businessPlanService->getForPlant($plantId)['computed'];

            $inversionTotal += (float) ($computed['inversiones']['total'] ?? 0);
            foreach (($computed['inversiones']['grupos'] ?? []) as $grupo) {
                $estructura[$grupo['nombre']] = ($estructura[$grupo['nombre']] ?? 0) + (float) $grupo['subtotal'];
            }

            $this->accumulate($flujoNeto, $computed['flujoCaja']['flujoNeto']['meses'] ?? []);
            $this->accumulate($ventasMensual, $computed['ventas']['montos']['totalesMensual'] ?? []);
            $this->accumulate($costosMensual, $computed['demanda']['costos']['totales']['mensual'] ?? []);

            foreach (($computed['vanTir']['indicadores'] ?? []) as $ind) {
                if (str_starts_with($ind['nombre'], 'VAN (') && is_numeric($ind['valor'])) {
                    $vanTotal += (float) $ind['valor'];
                }
            }
        }

        $ventasTotal = array_sum($ventasMensual);
        $costosTotal = array_sum($costosMensual);
        $utilidad = $ventasTotal - $costosTotal;

        $wacc = 0.1;
        $tir = $this->irr($inversionTotal, $flujoNeto);
        $payback = $this->payback($inversionTotal, $flujoNeto);
        $bc = $inversionTotal > 0 ? round(($vanTotal + $inversionTotal) / $inversionTotal, 2) : 0;

        // Estructura de costos (inversión por grupo + costos de producción)
        $estructuraLabels = array_keys($estructura);
        $estructuraSeries = array_map(fn ($v) => round($v, 2), array_values($estructura));
        if ($costosTotal > 0) {
            $estructuraLabels[] = 'Costos de producción';
            $estructuraSeries[] = round($costosTotal, 2);
        }

        return [
            'van' => round($vanTotal, 0),
            'tir' => $tir === null ? null : round($tir * 100, 2),
            'inversionInicial' => round($inversionTotal, 0),
            'paybackMeses' => $payback,
            'bc' => $bc,
            'wacc' => round($wacc * 100, 1),
            'utilidadNeta' => round($utilidad, 0),
            'ingresosTotales' => round($ventasTotal, 0),
            'costosTotales' => round($costosTotal, 0),
            'flujoCaja' => $this->flujoCajaChart($inversionTotal, $flujoNeto),
            'costosIngresos' => [
                'categories' => array_map(fn ($i) => 'Mes ' . ($i + 1), range(0, $months - 1)),
                'ingresos' => array_map(fn ($v) => round($v, 0), $ventasMensual),
                'costos' => array_map(fn ($v) => round($v, 0), $costosMensual),
            ],
            'puntoEquilibrio' => $this->puntoEquilibrio($ventasTotal, $costosTotal, $inversionTotal, $this->totalUnidades($plantIds)),
            'estructuraCostos' => ['labels' => $estructuraLabels, 'series' => $estructuraSeries],
            'retorno' => [
                ['label' => 'Inversión Inicial', 'value' => round($inversionTotal, 0), 'color' => ''],
                ['label' => 'Ingresos (12m)', 'value' => round($ventasTotal, 0), 'color' => 'text-green'],
                ['label' => 'Costos (12m)', 'value' => round($costosTotal, 0), 'color' => 'text-red'],
                ['label' => 'Utilidad Neta', 'value' => round($utilidad, 0), 'color' => 'text-green'],
                ['label' => 'VAN', 'value' => round($vanTotal, 0), 'color' => 'text-green'],
                ['label' => 'TIR (%)', 'value' => $tir === null ? 0 : round($tir * 100, 2), 'color' => 'text-green'],
                ['label' => 'Payback (meses)', 'value' => $payback ?? 0, 'color' => ''],
                ['label' => 'B/C', 'value' => $bc, 'color' => 'text-green'],
            ],
        ];
    }

    private function totalUnidades(array $plantIds): float
    {
        $total = 0.0;
        foreach ($plantIds as $plantId) {
            $computed = $this->businessPlanService->getForPlant($plantId)['computed'];
            $total += (float) ($computed['ventas']['unidades']['total'] ?? 0);
        }
        return $total;
    }

    private function accumulate(array &$acc, array $values): void
    {
        foreach ($acc as $i => $_) {
            $acc[$i] += (float) ($values[$i] ?? 0);
        }
    }

    private function flujoCajaChart(float $inversion, array $flujoNeto): array
    {
        $categories = ['Mes 0'];
        $data = [round(-$inversion, 0)];
        foreach ($flujoNeto as $i => $v) {
            $categories[] = 'Mes ' . ($i + 1);
            $data[] = round($v, 0);
        }
        return ['categories' => $categories, 'data' => $data];
    }

    private function puntoEquilibrio(float $ventasTotal, float $costosTotal, float $costosFijos, float $unidades): array
    {
        $precioProm = $unidades > 0 ? $ventasTotal / $unidades : 0;
        $costoVarUnit = $unidades > 0 ? $costosTotal / $unidades : 0;
        $margen = $precioProm - $costoVarUnit;
        $qEquilibrio = $margen > 0 ? $costosFijos / $margen : 0;

        $maxQ = $qEquilibrio > 0 ? $qEquilibrio * 2 : ($unidades > 0 ? $unidades : 0);
        $step = $maxQ > 0 ? $maxQ / 8 : 0;

        $categories = [];
        $ingresos = [];
        $costosTotales = [];
        $costosFijosLine = [];
        for ($i = 0; $i <= 8; $i++) {
            $q = round($step * $i);
            $categories[] = (string) $q;
            $ingresos[] = round($precioProm * $q, 0);
            $costosTotales[] = round($costosFijos + $costoVarUnit * $q, 0);
            $costosFijosLine[] = round($costosFijos, 0);
        }

        return [
            'categories' => $categories,
            'ingresos' => $ingresos,
            'costosTotales' => $costosTotales,
            'costosFijos' => $costosFijosLine,
            'puntoEquilibrioUnidades' => round($qEquilibrio, 0),
        ];
    }

    private function irr(float $inversion, array $bna): ?float
    {
        if ($inversion <= 0) {
            return null;
        }
        $npv = function (float $r) use ($inversion, $bna): float {
            $v = -$inversion;
            foreach ($bna as $t => $cf) {
                $v += $cf / pow(1 + $r, $t + 1);
            }
            return $v;
        };
        $lo = -0.9;
        $hi = 1.0;
        $flo = $npv($lo);
        $fhi = $npv($hi);
        if ($flo * $fhi > 0) {
            return null;
        }
        for ($i = 0; $i < 100; $i++) {
            $mid = ($lo + $hi) / 2;
            $fm = $npv($mid);
            if (abs($fm) < 0.01) {
                return $mid;
            }
            if ($flo * $fm < 0) {
                $hi = $mid;
            } else {
                $lo = $mid;
                $flo = $fm;
            }
        }
        return ($lo + $hi) / 2;
    }

    private function payback(float $inversion, array $flujoNeto): ?int
    {
        if ($inversion <= 0) {
            return null;
        }
        $run = -$inversion;
        foreach ($flujoNeto as $i => $v) {
            $run += $v;
            if ($run >= 0) {
                return $i + 1;
            }
        }
        return null;
    }
}
