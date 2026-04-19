<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Repositories;

use App\Models\Learning\Course;

class CourseRepository
{
    public function dataTable($request)
    {
        $query = Course::query()->with('area');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findById(int $id): Course
    {
        return Course::with(['area', 'coverImageFile', 'modules.lessons.resources.file', 'modules.lessons.quizQuestions.options'])->findOrFail($id);
    }

    public function createOrUpdate(array $data): Course
    {
        if (isset($data['id'])) {
            $course = Course::findOrFail($data['id']);
            $course->update($data);
            return $course;
        }

        return Course::create($data);
    }

    public function delete(int $id): Course
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return $course;
    }

    public function getSelectItems()
    {
        return Course::where('status', 'published')
            ->orderBy('name')
            ->get();
    }
}
