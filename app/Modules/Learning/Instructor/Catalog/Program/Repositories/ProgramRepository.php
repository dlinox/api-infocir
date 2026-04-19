<?php

namespace App\Modules\Learning\Instructor\Catalog\Program\Repositories;

use App\Models\Learning\Program;

class ProgramRepository
{
    public function dataTable($request)
    {
        $query = Program::query()->with('certificateTemplate');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findById(int $id): Program
    {
        return Program::with(['certificateTemplate', 'programCourses.course.area'])->findOrFail($id);
    }

    public function createOrUpdate(array $data): Program
    {
        if (isset($data['id'])) {
            $program = Program::findOrFail($data['id']);
            $program->update($data);
            return $program;
        }

        return Program::create($data);
    }

    public function delete(int $id): Program
    {
        $program = Program::findOrFail($id);
        $program->delete();
        return $program;
    }

    public function getSelectItems()
    {
        return Program::where('status', 'published')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
