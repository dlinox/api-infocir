<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Core\UnitMeasure;

class UnitMeasureRepository
{
    public function dataTable($request)
    {
        $query = UnitMeasure::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $unitMeasure = UnitMeasure::findOrFail($data['id']);
            $unitMeasure->update($data);
            return $unitMeasure;
        }

        return UnitMeasure::create($data);
    }

    public function delete(string $id)
    {
        $unitMeasure = UnitMeasure::findOrFail($id);
        $unitMeasure->delete();
        return $unitMeasure;
    }

    public function getSelectItems()
    {
        return UnitMeasure::where('is_active', true)->orderBy('name')->get();
    }
}
