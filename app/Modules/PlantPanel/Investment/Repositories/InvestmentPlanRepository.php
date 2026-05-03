<?php

namespace App\Modules\PlantPanel\Investment\Repositories;

use App\Models\Dairy\InvestmentItem;
use App\Models\Dairy\InvestmentPlan;
use Illuminate\Support\Facades\DB;

class InvestmentPlanRepository
{
    public function getOrCreateWorkingCapital(int $entityId, int $year, int $month): InvestmentPlan
    {
        $plan = InvestmentPlan::where('entity_id', $entityId)
            ->where('plan_type', 'working_capital')
            ->where('period_year', $year)
            ->where('period_month', $month)
            ->with(['items.category'])
            ->orderBy('id', 'desc')
            ->first();

        if (!$plan) {
            $plan = InvestmentPlan::create([
                'entity_id'    => $entityId,
                'plan_type'    => 'working_capital',
                'period_year'  => $year,
                'period_month' => $month,
                'status'       => 'draft',
                'total_amount' => 0,
            ]);
            $plan->setRelation('items', collect());
        }

        return $plan;
    }

    public function save(int $entityId, array $data): InvestmentPlan
    {
        return DB::transaction(function () use ($entityId, $data) {
            $plan = !empty($data['id'])
                ? InvestmentPlan::where('id', $data['id'])->where('entity_id', $entityId)->firstOrFail()
                : new InvestmentPlan(['entity_id' => $entityId]);

            $plan->fill([
                'entity_id'    => $entityId,
                'plan_type'    => 'working_capital',
                'period_year'  => $data['period_year'],
                'period_month' => $data['period_month'] ?? null,
                'notes'        => $data['notes'] ?? null,
                'status'       => 'draft',
            ]);

            $items = $data['items'] ?? [];
            $total = 0.0;
            foreach ($items as $item) {
                $total += round((float) $item['unit_value'] * (float) $item['quantity'], 2);
            }
            $plan->total_amount = $total;
            $plan->save();

            InvestmentItem::where('plan_id', $plan->id)->delete();

            $sort = 0;
            foreach ($items as $item) {
                InvestmentItem::create([
                    'plan_id'                => $plan->id,
                    'investment_category_id' => $item['investment_category_id'],
                    'name'                   => $item['name'],
                    'recurrence_type'        => $item['recurrence_type'] ?? 'one_time',
                    'unit_value'             => $item['unit_value'],
                    'quantity'               => $item['quantity'],
                    'total'                  => round((float) $item['unit_value'] * (float) $item['quantity'], 2),
                    'sort_order'             => $sort++,
                ]);
            }

            return $plan->fresh(['items.category']);
        });
    }

    /**
     * Devuelve el plan inmediatamente anterior al (year,month) dado.
     */
    public function findPreviousWorkingCapital(int $entityId, int $year, int $month): ?InvestmentPlan
    {
        return InvestmentPlan::where('entity_id', $entityId)
            ->where('plan_type', 'working_capital')
            ->where(function ($q) use ($year, $month) {
                $q->where('period_year', '<', $year)
                  ->orWhere(function ($q2) use ($year, $month) {
                      $q2->where('period_year', $year)
                         ->where('period_month', '<', $month);
                  });
            })
            ->with(['items'])
            ->orderBy('period_year', 'desc')
            ->orderBy('period_month', 'desc')
            ->first();
    }
}