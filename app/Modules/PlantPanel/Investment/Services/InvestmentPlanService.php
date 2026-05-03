<?php

namespace App\Modules\PlantPanel\Investment\Services;

use App\Models\Dairy\FixedAsset;
use App\Models\Dairy\InvestmentPlan;
use App\Models\Dairy\PreOperativeExpense;
use App\Models\Dairy\Worker;
use App\Modules\Auth\Services\AuthService;
use App\Modules\PlantPanel\Investment\Repositories\InvestmentPlanRepository;
use App\Modules\PlantPanel\Investment\Support\DepreciationCalculator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvestmentPlanService
{
    public function __construct(
        private InvestmentPlanRepository $repository,
        private AuthService $authService,
    ) {}

    public function getWorkingCapital(int $year, int $month): InvestmentPlan
    {
        $entityId = $this->authService->getMyEntityId();
        return $this->repository->getOrCreateWorkingCapital($entityId, $year, $month);
    }

    public function save(array $data): InvestmentPlan
    {
        $entityId = $this->authService->getMyEntityId();
        return $this->repository->save($entityId, $data);
    }

    public function copyPreviousMonth(int $year, int $month): InvestmentPlan
    {
        $entityId = $this->authService->getMyEntityId();
        $current  = $this->repository->getOrCreateWorkingCapital($entityId, $year, $month);
        $previous = $this->repository->findPreviousWorkingCapital($entityId, $year, $month);

        if ($previous && $previous->items->count() > 0) {
            $items = $previous->items->map(fn ($it) => [
                'investment_category_id' => $it->investment_category_id,
                'name'                   => $it->name,
                'recurrence_type'        => $it->recurrence_type ?? 'monthly',
                'unit_value'             => (float) $it->unit_value,
                'quantity'               => (float) $it->quantity,
            ])->toArray();

            return $this->repository->save($entityId, [
                'id'           => $current->id,
                'period_year'  => $year,
                'period_month' => $month,
                'notes'        => $current->notes,
                'items'        => $items,
            ]);
        }

        return $current;
    }

    public function getWorkingCapitalWorkers(): array
    {
        $entityId = $this->authService->getMyEntityId();

        return Worker::join('core_persons', 'core_persons.id', '=', 'dairy_workers.person_id')
            ->join('dairy_positions', 'dairy_positions.id', '=', 'dairy_workers.position_id')
            ->join('dairy_investment_categories', 'dairy_investment_categories.id', '=', 'dairy_positions.investment_category_id')
            ->where('dairy_workers.entity_id', $entityId)
            ->where('dairy_workers.is_active', true)
            ->where('dairy_investment_categories.group', 'working_capital')
            ->select(
                'dairy_workers.person_id',
                'core_persons.name as person_name',
                'core_persons.paternal_surname as person_paternal_surname',
                'core_persons.maternal_surname as person_maternal_surname',
                'dairy_positions.name as position_name',
                'dairy_positions.investment_category_id',
                'dairy_investment_categories.name as category_name',
                'dairy_workers.monthly_salary',
            )
            ->orderBy('dairy_investment_categories.sort_order')
            ->orderBy('core_persons.paternal_surname')
            ->get()
            ->map(fn ($w) => [
                'personId'      => $w->person_id,
                'fullName'      => collect([$w->person_name, $w->person_paternal_surname, $w->person_maternal_surname])->filter()->implode(' '),
                'positionName'  => $w->position_name,
                'categoryId'    => $w->investment_category_id,
                'categoryName'  => $w->category_name,
                'monthlySalary' => (float) $w->monthly_salary,
            ])
            ->values()
            ->toArray();
    }

    public function getSummary(int $year): array
    {
        $entityId = $this->authService->getMyEntityId();
        $today    = Carbon::today();

        // Activo Fijo: total acumulado (no por año) y depreciación mensual
        $assets = FixedAsset::where('entity_id', $entityId)
            ->where('status', '!=', 'disposed')
            ->get();

        $assetsTotal           = (float) $assets->sum('purchase_cost');
        $monthlyDepreciation   = 0.0;
        $bookValueTotal        = 0.0;

        foreach ($assets as $a) {
            $depr = DepreciationCalculator::compute(
                purchaseCost:    (float) $a->purchase_cost,
                residualValue:   (float) ($a->residual_value ?? 0),
                usefulLifeYears: $a->useful_life_years,
                purchaseDate:    $a->purchase_date?->toDateString(),
                method:          $a->depreciation_method ?? 'straight_line',
                asOf:            $today,
            );
            $monthlyDepreciation += $depr['monthlyDepreciation'];
            $bookValueTotal      += $depr['bookValue'];
        }

        // Pre-operativos: total pagado acumulado y próximos a vencer
        $preOpTotal = (float) PreOperativeExpense::where('entity_id', $entityId)->sum('amount');
        $expiringSoon = PreOperativeExpense::where('entity_id', $entityId)
            ->whereNotNull('expiration_date')
            ->whereBetween('expiration_date', [$today, $today->copy()->addDays(60)])
            ->count();
        $expired = PreOperativeExpense::where('entity_id', $entityId)
            ->whereNotNull('expiration_date')
            ->where('expiration_date', '<', $today)
            ->count();

        // Capital de trabajo del mes actual + total año
        $currentMonth = (int) $today->month;
        $wcCurrent = (float) InvestmentPlan::where('entity_id', $entityId)
            ->where('plan_type', 'working_capital')
            ->where('period_year', $year)
            ->where('period_month', $currentMonth)
            ->sum('total_amount');
        $wcYear = (float) InvestmentPlan::where('entity_id', $entityId)
            ->where('plan_type', 'working_capital')
            ->where('period_year', $year)
            ->sum('total_amount');

        return [
            'year'                       => $year,
            'currentMonth'               => $currentMonth,
            'fixedAssetsTotal'           => round($assetsTotal, 2),
            'fixedAssetsBookValue'       => round($bookValueTotal, 2),
            'fixedAssetsMonthlyDepr'     => round($monthlyDepreciation, 2),
            'preOperativeTotal'          => round($preOpTotal, 2),
            'preOperativeExpiringSoon'   => $expiringSoon,
            'preOperativeExpired'        => $expired,
            'workingCapitalCurrentMonth' => round($wcCurrent, 2),
            'workingCapitalYearTotal'    => round($wcYear, 2),
            'totalInvested'              => round($assetsTotal + $preOpTotal + $wcYear, 2),
        ];
    }
}