<?php

namespace App\Modules\SupplierPanel\CashFlow\Repositories;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SupplierCashFlowRepository
{
    /**
     * Ingreso por cobros de leche (liquidaciones pagadas por las plantas) en el rango.
     */
    public function cobrosTotal(int $supplierId, string $from, string $to): float
    {
        return (float) DB::table('dairy_supplier_payments')
            ->where('supplier_id', $supplierId)
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->sum('net_amount');
    }

    /**
     * Egreso por gastos de operación (capital de trabajo) del proveedor en el rango.
     */
    public function expensesTotal(int $entityId, string $from, string $to): float
    {
        return (float) DB::table('dairy_investment_items as i')
            ->join('dairy_investment_plans as p', 'p.id', '=', 'i.plan_id')
            ->where('p.entity_id', $entityId)
            ->where('p.plan_type', 'working_capital')
            ->whereBetween(DB::raw("DATE_FORMAT(CONCAT(p.period_year, '-', LPAD(COALESCE(p.period_month, 1), 2, '0'), '-01'), '%Y-%m-%d')"), [
                Carbon::parse($from)->startOfMonth()->toDateString(),
                Carbon::parse($to)->endOfMonth()->toDateString(),
            ])
            ->sum('i.total');
    }

    /**
     * Serie mensual (ingresos vs egresos) hasta $end.
     * @return array<string,array{ingresos:float,egresos:float}>  clave = 'Y-m'
     */
    public function monthlySeries(int $supplierId, int $entityId, Carbon $start, Carbon $end): array
    {
        $startStr = $start->copy()->startOfMonth()->toDateTimeString();
        $endStr = $end->copy()->endOfMonth()->toDateTimeString();

        $cobros = DB::table('dairy_supplier_payments')
            ->where('supplier_id', $supplierId)
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$startStr, $endStr])
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as ym, SUM(net_amount) as amount")
            ->groupBy('ym')->pluck('amount', 'ym');

        $gastos = DB::table('dairy_investment_items as i')
            ->join('dairy_investment_plans as p', 'p.id', '=', 'i.plan_id')
            ->where('p.entity_id', $entityId)
            ->where('p.plan_type', 'working_capital')
            ->whereBetween('p.period_year', [$start->copy()->startOfMonth()->year, $end->year])
            ->selectRaw("CONCAT(p.period_year, '-', LPAD(COALESCE(p.period_month, 1), 2, '0')) as ym, SUM(i.total) as amount")
            ->groupBy('ym')->pluck('amount', 'ym');

        $series = [];
        $cursor = $start->copy()->startOfMonth();
        while ($cursor <= $end) {
            $ym = $cursor->format('Y-m');
            $series[$ym] = [
                'ingresos' => (float) ($cobros[$ym] ?? 0),
                'egresos'  => (float) ($gastos[$ym] ?? 0),
            ];
            $cursor->addMonth();
        }
        return $series;
    }

    /**
     * Movimientos recientes unificados (ingresos + egresos) en el rango.
     */
    public function recentMovements(int $supplierId, int $entityId, string $from, string $to, int $limit = 25): array
    {
        $fromTs = $from . ' 00:00:00';
        $toTs = $to . ' 23:59:59';

        $cobros = DB::table('dairy_supplier_payments as sp')
            ->leftJoin('dairy_plants as pl', 'pl.id', '=', 'sp.plant_id')
            ->where('sp.supplier_id', $supplierId)
            ->where('sp.status', 'paid')
            ->whereBetween('sp.paid_at', [$fromTs, $toTs])
            ->selectRaw("sp.paid_at as date, CONCAT('Cobro de leche - ', COALESCE(pl.trade_name, pl.name, 'Planta')) as concept, 'Cobros de leche' as category, 'in' as type, sp.net_amount as amount")
            ->get();

        $gastos = DB::table('dairy_investment_items as i')
            ->join('dairy_investment_plans as p', 'p.id', '=', 'i.plan_id')
            ->where('p.entity_id', $entityId)
            ->where('p.plan_type', 'working_capital')
            ->whereBetween('i.created_at', [$fromTs, $toTs])
            ->selectRaw("i.created_at as date, i.name as concept, 'Gastos de operación' as category, 'out' as type, i.total as amount")
            ->get();

        return $cobros->concat($gastos)
            ->sortByDesc('date')
            ->take($limit)
            ->map(fn ($movement) => [
                'date'     => (string) $movement->date,
                'concept'  => $movement->concept,
                'category' => $movement->category,
                'type'     => $movement->type,
                'amount'   => (float) $movement->amount,
            ])
            ->values()
            ->all();
    }
}
