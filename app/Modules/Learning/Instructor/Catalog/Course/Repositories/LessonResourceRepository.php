<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Repositories;

use App\Models\Learning\LessonResource;

class LessonResourceRepository
{
    public function createOrUpdate(array $data): LessonResource
    {
        if (isset($data['id'])) {
            $resource = LessonResource::findOrFail($data['id']);
            $resource->update($data);
            return $resource;
        }

        $data['order'] = LessonResource::where('lesson_id', $data['lesson_id'])->max('order') + 1;
        return LessonResource::create($data);
    }

    public function findById(int $id): LessonResource
    {
        return LessonResource::findOrFail($id);
    }

    public function delete(int $id): LessonResource
    {
        $resource = LessonResource::findOrFail($id);
        $resource->delete();
        return $resource;
    }
}
