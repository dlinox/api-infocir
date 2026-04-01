<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Core\Gender;

class GenderRepository
{
    public function dataTable($request)
    {
        $query = Gender::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('code', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        return Gender::updateOrCreate(['code' => $data['code']], $data);
    }

    public function delete(string $code)
    {
        $gender = Gender::where('code', $code)->firstOrFail();
        $gender->delete();
        return $gender;
    }

    public function getSelectItems()
    {
        return Gender::where('is_active', true)->orderBy('name')->get();
    }
}
