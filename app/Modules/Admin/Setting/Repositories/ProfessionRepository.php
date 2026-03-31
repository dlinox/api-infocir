<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Core\Profession;

class ProfessionRepository
{
    public function dataTable($request)
    {
        $query = Profession::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $profession = Profession::findOrFail($data['id']);
            $profession->update($data);
            return $profession;
        }

        return Profession::create($data);
    }

    public function delete(string $id)
    {
        $profession = Profession::findOrFail($id);
        $profession->delete();
        return $profession;
    }

    public function getSelectItems()
    {
        return Profession::where('is_active', true)->orderBy('name')->get();
    }
}
