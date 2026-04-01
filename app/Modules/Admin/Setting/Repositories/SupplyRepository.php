<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Dairy\Supply;

class SupplyRepository
{
    public function dataTable($request)
    {
        $query = Supply::query()->with(['unitMeasure']);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $supply = Supply::findOrFail($data['id']);
            $supply->update($data);
            return $supply;
        }

        return Supply::create($data);
    }

    public function delete(string $id)
    {
        $supply = Supply::findOrFail($id);
        $supply->delete();
        return $supply;
    }

    public function getSelectItems()
    {
        return Supply::where('is_active', true)->orderBy('name')->get();
    }
}
