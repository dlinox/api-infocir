<?php

namespace App\Modules\Admin\Dairy\Finance\CashFlow\Services;

use App\Modules\Admin\Dairy\Finance\CashFlow\Repositories\CashFlowRepository;
use Illuminate\Support\Carbon;

class CashFlowService
{
    private const MESES = ['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

    public function __construct(
        private CashFlowRepository $cashFlowRepository
    ) {}

    public function overview(?int $plantId, ?string $from, ?string $to): array
    {
        $end = $to ? Carbon::parse($to) : Carbon::now();
        $start = $from ? Carbon::parse($from) : $end->copy()->startOfMonth();

        $fromStr = $start->toDateString();
        $toStr = $end->toDateString();

        $ventas   = $this->cashFlowRepository->salesTotal($plantId, $fromStr, $toStr);
        $leche    = $this->cashFlowRepository->supplierPaymentsTotal($plantId, $fromStr, $toStr);
        $planilla = $this->cashFlowRepository->workerPaymentsTotal($plantId, $fromStr, $toStr);
        $acopio   = $this->cashFlowRepository->routeExpensesTotal($plantId, $fromStr, $toStr);

        $ingresos = $ventas;
        $egresos = $leche + $planilla + $acopio;

        // Serie de los últimos 6 meses hasta el fin del rango
        $monthlyRaw = $this->cashFlowRepository->monthlySeries(
            $plantId,
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
                ['category' => 'Ventas',           'type' => 'in',  'amount' => round($ventas, 2)],
                ['category' => 'Compra de leche',  'type' => 'out', 'amount' => round($leche, 2)],
                ['category' => 'Planilla',         'type' => 'out', 'amount' => round($planilla, 2)],
                ['category' => 'Gastos de acopio', 'type' => 'out', 'amount' => round($acopio, 2)],
            ],
            'monthly' => $monthly,
            'movements' => $this->cashFlowRepository->recentMovements($plantId, $fromStr, $toStr, 25),
        ];
    }
}
