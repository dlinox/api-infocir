<?php

namespace App\Modules\SupplierPanel\CashFlow\Services;

use App\Modules\SupplierPanel\CashFlow\Repositories\SupplierCashFlowRepository;
use Illuminate\Support\Carbon;

class SupplierCashFlowService
{
    private const MESES = ['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

    public function __construct(
        private SupplierCashFlowRepository $supplierCashFlowRepository
    ) {}

    public function overview(int $supplierId, int $entityId, ?string $from, ?string $to): array
    {
        $end = $to ? Carbon::parse($to) : Carbon::now();
        $start = $from ? Carbon::parse($from) : $end->copy()->startOfMonth();

        $fromStr = $start->toDateString();
        $toStr = $end->toDateString();

        $cobros = $this->supplierCashFlowRepository->cobrosTotal($supplierId, $fromStr, $toStr);
        $gastos = $this->supplierCashFlowRepository->expensesTotal($entityId, $fromStr, $toStr);

        $ingresos = $cobros;
        $egresos = $gastos;

        $monthlyRaw = $this->supplierCashFlowRepository->monthlySeries(
            $supplierId,
            $entityId,
            $end->copy()->subMonths(5),
            $end,
        );
        $monthly = [];
        foreach ($monthlyRaw as $ym => $vals) {
            [$y, $m] = explode('-', $ym);
            $monthly[] = [
                'month'    => $ym,
                'label'    => self::MESES[(int) $m] . ' ' . substr($y, 2),
                'ingresos' => round($vals['ingresos'], 2),
                'egresos'  => round($vals['egresos'], 2),
                'saldo'    => round($vals['ingresos'] - $vals['egresos'], 2),
            ];
        }

        return [
            'range' => ['from' => $fromStr, 'to' => $toStr],
            'summary' => [
                'ingresos' => round($ingresos, 2),
                'egresos'  => round($egresos, 2),
                'saldo'    => round($ingresos - $egresos, 2),
            ],
            'byCategory' => [
                ['category' => 'Cobros de leche',     'type' => 'in',  'amount' => round($cobros, 2)],
                ['category' => 'Gastos de operación', 'type' => 'out', 'amount' => round($gastos, 2)],
            ],
            'monthly' => $monthly,
            'movements' => $this->supplierCashFlowRepository->recentMovements($supplierId, $entityId, $fromStr, $toStr, 25),
        ];
    }
}
