<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Dairy\Position;

class PositionRepository
{
    public function dataTable($request)
    {
        $query = Position::with(['role', 'investmentCategory']);

        // entity_type is JSON — use whereJsonContains instead of plain where
        $filters = $request->filters ?? [];
        if (!empty($filters['entity_type'])) {
            $query->whereJsonContains('entity_type', $filters['entity_type']);
            $request->merge(['filters' => collect($filters)->except('entity_type')->toArray()]);
        }

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
        return Position::with('role')->where('is_active', true)->orderBy('name')->get();
    }
}
