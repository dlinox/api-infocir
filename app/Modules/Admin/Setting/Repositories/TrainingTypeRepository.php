<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Learning\TrainingType;

class TrainingTypeRepository
{
    public function dataTable($request)
    {
        $query = TrainingType::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $trainingType = TrainingType::findOrFail($data['id']);
            $trainingType->update($data);
            return $trainingType;
        }

        return TrainingType::create($data);
    }

    public function delete(string $id)
    {
        $trainingType = TrainingType::findOrFail($id);
        $trainingType->delete();
        return $trainingType;
    }

    public function getSelectItems()
    {
        return TrainingType::where('is_active', true)->orderBy('name')->get();
    }
}
