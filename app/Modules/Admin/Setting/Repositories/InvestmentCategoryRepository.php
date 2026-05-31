<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Dairy\InvestmentCategory;

class InvestmentCategoryRepository
{
    public function dataTable($request)
    {
        $query = InvestmentCategory::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $category = InvestmentCategory::findOrFail($data['id']);
            $category->update($data);
            return $category;
        }

        return InvestmentCategory::create($data);
    }

    public function delete(string $id)
    {
        $category = InvestmentCategory::findOrFail($id);
        $category->delete();
        return $category;
    }

    public function getSelectItems(?string $group = null)
    {
        $query = InvestmentCategory::where('is_active', true);

        if ($group !== null) {
            $query->where('group', $group);
        }

        return $query->orderBy('sort_order')->orderBy('name')->get();
    }
}
