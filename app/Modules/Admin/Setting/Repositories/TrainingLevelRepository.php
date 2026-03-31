<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Dairy\TrainingLevel;

class TrainingLevelRepository
{
    public function dataTable($request)
    {
        $query = TrainingLevel::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $trainingLevel = TrainingLevel::findOrFail($data['id']);
            $trainingLevel->update($data);
            return $trainingLevel;
        }

        return TrainingLevel::create($data);
    }

    public function delete(string $id)
    {
        $trainingLevel = TrainingLevel::findOrFail($id);
        $trainingLevel->delete();
        return $trainingLevel;
    }

    public function getSelectItems()
    {
        return TrainingLevel::where('is_active', true)->orderBy('name')->get();
    }
}
