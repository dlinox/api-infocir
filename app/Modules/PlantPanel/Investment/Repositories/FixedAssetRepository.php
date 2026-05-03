<?php

namespace App\Modules\PlantPanel\Investment\Repositories;

use App\Models\Dairy\FixedAsset;

class FixedAssetRepository
{
    public function dataTable(int $entityId, $request)
    {
        $query = FixedAsset::query()
            ->leftJoin('dairy_investment_categories', 'dairy_investment_categories.id', '=', 'dairy_fixed_assets.investment_category_id')
            ->where('dairy_fixed_assets.entity_id', $entityId)
            ->select(
                'dairy_fixed_assets.*',
                'dairy_investment_categories.name as category_name',
            );

        if (empty($request->sortBy)) {
            $query->orderBy('dairy_fixed_assets.purchase_date', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findForEntity(int $entityId, int $id): FixedAsset
    {
        return FixedAsset::where('id', $id)->where('entity_id', $entityId)->firstOrFail();
    }

    public function createOrUpdate(int $entityId, array $data): FixedAsset
    {
        if (!empty($data['id'])) {
            $asset = $this->findForEntity($entityId, (int) $data['id']);
            $asset->update($data);
            return $asset->fresh(['category']);
        }
        $data['entity_id'] = $entityId;
        return FixedAsset::create($data)->fresh(['category']);
    }

    public function delete(int $entityId, int $id): void
    {
        $this->findForEntity($entityId, $id)->delete();
    }

    public function getAllForEntity(int $entityId)
    {
        return FixedAsset::with('category')
            ->where('entity_id', $entityId)
            ->orderBy('purchase_date', 'desc')
            ->get();
    }

    public function totalForEntity(int $entityId): float
    {
        return (float) FixedAsset::where('entity_id', $entityId)
            ->where('status', '!=', 'disposed')
            ->sum('purchase_cost');
    }
}
