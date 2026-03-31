<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Dairy\InstitutionType;

class InstitutionTypeRepository
{
    public function dataTable($request)
    {
        $query = InstitutionType::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $institutionType = InstitutionType::findOrFail($data['id']);
            $institutionType->update($data);
            return $institutionType;
        }

        return InstitutionType::create($data);
    }

    public function delete(string $id)
    {
        $institutionType = InstitutionType::findOrFail($id);
        $institutionType->delete();
        return $institutionType;
    }

    public function getSelectItems()
    {
        return InstitutionType::where('is_active', true)->orderBy('name')->get();
    }
}
