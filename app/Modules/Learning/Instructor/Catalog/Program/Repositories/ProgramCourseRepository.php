<?php

namespace App\Modules\Learning\Instructor\Catalog\Program\Repositories;

use App\Models\Learning\ProgramCourse;

class ProgramCourseRepository
{
    public function createOrUpdate(array $data): ProgramCourse
    {
        if (isset($data['id'])) {
            $programCourse = ProgramCourse::findOrFail($data['id']);
            $programCourse->update($data);
            return $programCourse;
        }

        $data['order'] = ProgramCourse::where('program_id', $data['program_id'])->max('order') + 1;

        return ProgramCourse::create($data);
    }

    public function delete(int $id): ProgramCourse
    {
        $programCourse = ProgramCourse::findOrFail($id);
        $programCourse->delete();
        return $programCourse;
    }
}
