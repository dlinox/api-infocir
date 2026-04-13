<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Learning\Area;

class AreaRepository
{
    public function dataTable($request)
    {
        $query = Area::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $area = Area::findOrFail($data['id']);
            $area->update($data);
            return $area;
        }

        return Area::create($data);
    }

    public function delete(string $id)
    {
        $area = Area::findOrFail($id);
        $area->delete();
        return $area;
    }

    public function getSelectItems()
    {
        return Area::where('is_active', true)->orderBy('name')->get();
    }
}
