<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Repositories;

use App\Models\Learning\CourseModule;

class CourseModuleRepository
{
    public function createOrUpdate(array $data): CourseModule
    {
        if (isset($data['id'])) {
            $module = CourseModule::findOrFail($data['id']);
            $module->update($data);
            return $module;
        }

        $data['order'] = CourseModule::where('course_id', $data['course_id'])->max('order') + 1;

        return CourseModule::create($data);
    }

    public function delete(int $id): CourseModule
    {
        $module = CourseModule::findOrFail($id);
        $module->delete();
        return $module;
    }
}
