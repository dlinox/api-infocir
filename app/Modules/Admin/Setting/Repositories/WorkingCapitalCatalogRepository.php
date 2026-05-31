<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Dairy\WorkingCapitalCatalog;

class WorkingCapitalCatalogRepository
{
    public function dataTable($request)
    {
        $query = WorkingCapitalCatalog::with(['investmentCategory', 'iconFile']);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (($data['recurrence_type'] ?? 'none') !== 'every_x_days') {
            $data['recurrence_every_days'] = null;
        }

        if (isset($data['id'])) {
            $catalog = WorkingCapitalCatalog::findOrFail($data['id']);
            $catalog->update($data);
            return $catalog->load(['investmentCategory', 'iconFile']);
        }

        $catalog = WorkingCapitalCatalog::create($data);
        return $catalog->load(['investmentCategory', 'iconFile']);
    }

    public function delete(string $id)
    {
        $catalog = WorkingCapitalCatalog::findOrFail($id);
        $catalog->delete();
        return $catalog;
    }

    public function getSelectItems()
    {
        return WorkingCapitalCatalog::with(['investmentCategory', 'iconFile'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
