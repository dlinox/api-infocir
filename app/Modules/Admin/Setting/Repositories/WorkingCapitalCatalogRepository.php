<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Dairy\WorkingCapitalCatalog;

class WorkingCapitalCatalogRepository
{
    public function dataTable($request)
    {
        $query = WorkingCapitalCatalog::with(['investmentCategory', 'unitMeasure']);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('name');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $catalog = WorkingCapitalCatalog::findOrFail($data['id']);
            $catalog->update($data);
            return $catalog->load(['investmentCategory', 'unitMeasure']);
        }

        $catalog = WorkingCapitalCatalog::create($data);
        return $catalog->load(['investmentCategory', 'unitMeasure']);
    }

    public function delete(string $id)
    {
        $catalog = WorkingCapitalCatalog::findOrFail($id);
        $catalog->delete();
        return $catalog;
    }

    public function getSelectItems()
    {
        return WorkingCapitalCatalog::with(['investmentCategory', 'unitMeasure'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
