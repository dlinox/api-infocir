<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Dairy\PreOperativeCatalog;

class PreOperativeCatalogRepository
{
    public function dataTable($request)
    {
        $query = PreOperativeCatalog::with('investmentCategory');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $catalog = PreOperativeCatalog::findOrFail($data['id']);
            $catalog->update($data);
            return $catalog->load('investmentCategory');
        }

        $catalog = PreOperativeCatalog::create($data);
        return $catalog->load('investmentCategory');
    }

    public function delete(string $id)
    {
        $catalog = PreOperativeCatalog::findOrFail($id);
        $catalog->delete();
        return $catalog;
    }

    public function getSelectItems()
    {
        return PreOperativeCatalog::with('investmentCategory')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
