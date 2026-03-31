<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Core\InstructionDegree;

class InstructionDegreeRepository
{
    public function dataTable($request)
    {
        $query = InstructionDegree::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $instructionDegree = InstructionDegree::findOrFail($data['id']);
            $instructionDegree->update($data);
            return $instructionDegree;
        }

        return InstructionDegree::create($data);
    }

    public function delete(string $id)
    {
        $instructionDegree = InstructionDegree::findOrFail($id);
        $instructionDegree->delete();
        return $instructionDegree;
    }

    public function getSelectItems()
    {
        return InstructionDegree::where('is_active', true)->orderBy('name')->get();
    }
}
