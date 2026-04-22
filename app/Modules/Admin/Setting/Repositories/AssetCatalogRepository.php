<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Dairy\AssetCatalog;

class AssetCatalogRepository
{
    public function dataTable($request)
    {
        $query = AssetCatalog::with('investmentCategory');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('name');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $catalog = AssetCatalog::findOrFail($data['id']);
            $catalog->update($data);
            return $catalog->load('investmentCategory');
        }

        $catalog = AssetCatalog::create($data);
        return $catalog->load('investmentCategory');
    }

    public function delete(string $id)
    {
        $catalog = AssetCatalog::findOrFail($id);
        $catalog->delete();
        return $catalog;
    }

    public function getSelectItems()
    {
        return AssetCatalog::with('investmentCategory')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
