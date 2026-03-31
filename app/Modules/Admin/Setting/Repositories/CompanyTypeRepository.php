<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Dairy\CompanyType;

class CompanyTypeRepository
{
    public function dataTable($request)
    {
        $query = CompanyType::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $companyType = CompanyType::findOrFail($data['id']);
            $companyType->update($data);
            return $companyType;
        }

        return CompanyType::create($data);
    }

    public function delete(string $id)
    {
        $companyType = CompanyType::findOrFail($id);
        $companyType->delete();
        return $companyType;
    }

    public function getSelectItems()
    {
        return CompanyType::where('is_active', true)->orderBy('name')->get();
    }
}
