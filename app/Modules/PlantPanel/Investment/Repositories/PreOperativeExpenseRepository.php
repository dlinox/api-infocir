<?php

namespace App\Modules\PlantPanel\Investment\Repositories;

use App\Models\Dairy\PreOperativeExpense;
use Carbon\Carbon;

class PreOperativeExpenseRepository
{
    public function dataTable(int $entityId, $request)
    {
        $query = PreOperativeExpense::query()
            ->leftJoin('dairy_investment_categories', 'dairy_investment_categories.id', '=', 'dairy_pre_operative_expenses.investment_category_id')
            ->where('dairy_pre_operative_expenses.entity_id', $entityId)
            ->select(
                'dairy_pre_operative_expenses.*',
                'dairy_investment_categories.name as category_name',
            );

        if (empty($request->sortBy)) {
            $query->orderBy('dairy_pre_operative_expenses.payment_date', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findForEntity(int $entityId, int $id): PreOperativeExpense
    {
        return PreOperativeExpense::where('id', $id)->where('entity_id', $entityId)->firstOrFail();
    }

    public function createOrUpdate(int $entityId, array $data): PreOperativeExpense
    {
        // Auto-calcular expiration_date si recurrencia=periodic + validity_years + payment_date
        if (!empty($data['recurrence_type']) && $data['recurrence_type'] === 'periodic'
            && !empty($data['validity_years']) && !empty($data['payment_date'])) {
            $data['expiration_date'] = Carbon::parse($data['payment_date'])
                ->addYears((int) $data['validity_years'])
                ->toDateString();
        } elseif (($data['recurrence_type'] ?? null) === 'one_time') {
            $data['expiration_date'] = null;
            $data['validity_years']  = null;
        }

        if (!empty($data['id'])) {
            $expense = $this->findForEntity($entityId, (int) $data['id']);
            $expense->update($data);
            return $expense->fresh(['category']);
        }
        $data['entity_id'] = $entityId;
        return PreOperativeExpense::create($data)->fresh(['category']);
    }

    public function delete(int $entityId, int $id): void
    {
        $this->findForEntity($entityId, $id)->delete();
    }

    public function totalForEntity(int $entityId): float
    {
        return (float) PreOperativeExpense::where('entity_id', $entityId)->sum('amount');
    }
}
