<?php

namespace App\Modules\Admin\Dairy\Plant\Repositories;

use App\Models\Dairy\Plant;

class PlantRepository
{
    public function dataTable($request)
    {
        $query = Plant::query()
            ->with(['companyType', 'trainingLevel', 'institutionType', 'country', 'city']);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $plant = Plant::findOrFail($data['id']);
            $plant->update($data);
            return $plant;
        }

        return Plant::create($data);
    }

    public function findById(string $id)
    {
        return Plant::findOrFail($id);
    }

    public function delete(string $id)
    {
        $plant = Plant::findOrFail($id);
        $plant->delete();
        return $plant;
    }
}
