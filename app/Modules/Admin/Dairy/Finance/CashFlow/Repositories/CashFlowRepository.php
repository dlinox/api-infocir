<?php

namespace App\Modules\Admin\Dairy\Finance\CashFlow\Repositories;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CashFlowRepository
{
    /**
     * Ingreso por ventas (pedidos cerrados) en el rango.
     */
    public function salesTotal(?int $plantId, string $from, string $to): float
    {
        return (float) DB::table('dairy_orders')
            ->where('status', 'closed')
            ->when($plantId, fn ($q) => $q->where('plant_id', $plantId))
            ->whereBetween('closed_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->sum('total');
    }

    /**
     * Egreso por pagos a proveedores (compra de leche) pagados en el rango.
     */
    public function supplierPaymentsTotal(?int $plantId, string $from, string $to): float
    {
        return (float) DB::table('dairy_supplier_payments')
            ->where('status', 'paid')
            ->when($plantId, fn ($q) => $q->where('plant_id', $plantId))
            ->whereBetween('paid_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->sum('net_amount');
    }

    /**
     * Egreso por planilla (pagos a trabajadores) pagados en el rango.
     */
    public function workerPaymentsTotal(?int $plantId, string $from, string $to): float
    {
        return (float) DB::table('dairy_worker_payments')
            ->where('status', 'paid')
            ->when($plantId, fn ($q) => $q->where('plant_id', $plantId))
            ->whereBetween('paid_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->sum('net_amount');
    }

    /**
     * Egreso por gastos de las rutas de acopio en el rango.
     */
    public function routeExpensesTotal(?int $plantId, string $from, string $to): float
    {
        return (float) DB::table('dairy_collection_route_expenses as e')
            ->join('dairy_collection_routes as r', 'r.id', '=', 'e.collection_route_id')
            ->when($plantId, fn ($q) => $q->where('r.plant_id', $plantId))
            ->whereBetween('e.created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->sum('e.amount');
    }

    /**
     * Serie mensual (ingresos vs egresos) para los últimos $months meses hasta $end.
     * @return array<string,array{ingresos:float,egresos:float}>  clave = 'Y-m'
     */
    public function monthlySeries(?int $plantId, Carbon $start, Carbon $end): array
    {
        $startStr = $start->copy()->startOfMonth()->toDateTimeString();
        $endStr = $end->copy()->endOfMonth()->toDateTimeString();

        $ingresos = DB::table('dairy_orders')
            ->where('status', 'closed')
            ->when($plantId, fn ($q) => $q->where('plant_id', $plantId))
            ->whereBetween('closed_at', [$startStr, $endStr])
            ->selectRaw("DATE_FORMAT(closed_at, '%Y-%m') as ym, SUM(total) as amount")
            ->groupBy('ym')->pluck('amount', 'ym');

        $supplier = DB::table('dairy_supplier_payments')
            ->where('status', 'paid')
            ->when($plantId, fn ($q) => $q->where('plant_id', $plantId))
            ->whereBetween('paid_at', [$startStr, $endStr])
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as ym, SUM(net_amount) as amount")
            ->groupBy('ym')->pluck('amount', 'ym');

        $worker = DB::table('dairy_worker_payments')
            ->where('status', 'paid')
            ->when($plantId, fn ($q) => $q->where('plant_id', $plantId))
            ->whereBetween('paid_at', [$startStr, $endStr])
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as ym, SUM(net_amount) as amount")
            ->groupBy('ym')->pluck('amount', 'ym');

        $routes = DB::table('dairy_collection_route_expenses as e')
            ->join('dairy_collection_routes as r', 'r.id', '=', 'e.collection_route_id')
            ->when($plantId, fn ($q) => $q->where('r.plant_id', $plantId))
            ->whereBetween('e.created_at', [$startStr, $endStr])
            ->selectRaw("DATE_FORMAT(e.created_at, '%Y-%m') as ym, SUM(e.amount) as amount")
            ->groupBy('ym')->pluck('amount', 'ym');

        $series = [];
        $cursor = $start->copy()->startOfMonth();
        while ($cursor <= $end) {
            $ym = $cursor->format('Y-m');
            $series[$ym] = [
                'ingresos' => (float) ($ingresos[$ym] ?? 0),
                'egresos'  => (float) (($supplier[$ym] ?? 0) + ($worker[$ym] ?? 0) + ($routes[$ym] ?? 0)),
            ];
            $cursor->addMonth();
        }
        return $series;
    }

    /**
     * Movimientos recientes unificados (ingresos + egresos) en el rango.
     */
    public function recentMovements(?int $plantId, string $from, string $to, int $limit = 20): array
    {
        $fromTs = $from . ' 00:00:00';
        $toTs = $to . ' 23:59:59';

        $sales = DB::table('dairy_orders')
            ->where('status', 'closed')
            ->when($plantId, fn ($q) => $q->where('plant_id', $plantId))
            ->whereBetween('closed_at', [$fromTs, $toTs])
            ->selectRaw("closed_at as date, CONCAT('Venta ', code) as concept, 'Ventas' as category, 'in' as type, total as amount")
            ->get();

        $supplier = DB::table('dairy_supplier_payments')
            ->where('status', 'paid')
            ->when($plantId, fn ($q) => $q->where('plant_id', $plantId))
            ->whereBetween('paid_at', [$fromTs, $toTs])
            ->selectRaw("paid_at as date, 'Pago a proveedor' as concept, 'Compra de leche' as category, 'out' as type, net_amount as amount")
            ->get();

        $worker = DB::table('dairy_worker_payments')
            ->where('status', 'paid')
            ->when($plantId, fn ($q) => $q->where('plant_id', $plantId))
            ->whereBetween('paid_at', [$fromTs, $toTs])
            ->selectRaw("paid_at as date, 'Pago a trabajador' as concept, 'Planilla' as category, 'out' as type, net_amount as amount")
            ->get();

        $routes = DB::table('dairy_collection_route_expenses as e')
            ->join('dairy_collection_routes as r', 'r.id', '=', 'e.collection_route_id')
            ->when($plantId, fn ($q) => $q->where('r.plant_id', $plantId))
            ->whereBetween('e.created_at', [$fromTs, $toTs])
            ->selectRaw("e.created_at as date, 'Gasto de acopio' as concept, 'Gastos de acopio' as category, 'out' as type, e.amount as amount")
            ->get();

        return $sales->concat($supplier)->concat($worker)->concat($routes)
            ->sortByDesc('date')
            ->take($limit)
            ->map(fn ($m) => [
                'date'     => (string) $m->date,
                'concept'  => $m->concept,
                'category' => $m->category,
                'type'     => $m->type,
                'amount'   => (float) $m->amount,
            ])
            ->values()
            ->all();
    }
}
