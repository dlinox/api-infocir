<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Dairy\Position;

class PositionRepository
{
    public function dataTable($request)
    {
        $query = Position::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $position = Position::findOrFail($data['id']);
            $position->update($data);
            return $position;
        }

        return Position::create($data);
    }

    public function delete(string $id)
    {
        $position = Position::findOrFail($id);
        $position->delete();
        return $position;
    }

    public function getSelectItems()
    {
        return Position::where('is_active', true)->orderBy('name')->get();
    }
}
