<?php

namespace App\Modules\PlantPanel\Investment\Repositories;

use App\Models\Dairy\InvestmentItem;
use App\Models\Dairy\InvestmentPlan;
use Illuminate\Support\Facades\DB;

class InvestmentPlanRepository
{
    public function findCurrentForEntity(int $entityId): ?InvestmentPlan
    {
        return InvestmentPlan::where('entity_id', $entityId)
            ->orderByRaw("CASE WHEN status = 'draft' THEN 0 ELSE 1 END")
            ->orderBy('period_year', 'desc')
            ->orderBy('id', 'desc')
            ->with(['items.category'])
            ->first();
    }

    public function createForEntity(int $entityId, int $year): InvestmentPlan
    {
        return InvestmentPlan::create([
            'entity_id'    => $entityId,
            'name'         => "Plan de Inversión {$year}",
            'period_year'  => $year,
            'status'       => 'draft',
            'total_amount' => 0,
        ]);
    }

    public function save(int $entityId, array $data): InvestmentPlan
    {
        return DB::transaction(function () use ($entityId, $data) {
            $plan = !empty($data['id'])
                ? InvestmentPlan::where('id', $data['id'])->where('entity_id', $entityId)->firstOrFail()
                : new InvestmentPlan(['entity_id' => $entityId]);

            if ($plan->status === 'approved') {
                throw new \App\Common\Exceptions\ApiException('El plan ya fue aprobado y no puede modificarse', 422);
            }

            $plan->fill([
                'entity_id'   => $entityId,
                'name'        => $data['name'],
                'period_year' => $data['period_year'],
                'notes'       => $data['notes'] ?? null,
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
                    'unit_value'             => $item['unit_value'],
                    'quantity'               => $item['quantity'],
                    'total'                  => round((float) $item['unit_value'] * (float) $item['quantity'], 2),
                    'sort_order'             => $sort++,
                ]);
            }

            return $plan->fresh(['items.category']);
        });
    }

    public function approve(int $entityId, int $planId): InvestmentPlan
    {
        $plan = InvestmentPlan::where('id', $planId)->where('entity_id', $entityId)->firstOrFail();
        $plan->update(['status' => 'approved']);
        return $plan->fresh(['items.category']);
    }
}
