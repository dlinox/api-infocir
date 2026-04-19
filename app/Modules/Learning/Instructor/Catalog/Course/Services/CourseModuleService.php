<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Services;

use App\Modules\Learning\Instructor\Catalog\Course\Repositories\CourseModuleRepository;

class CourseModuleService
{
    public function __construct(
        private CourseModuleRepository $courseModuleRepository
    ) {}

    public function save(array $data)
    {
        return $this->courseModuleRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->courseModuleRepository->delete($id);
    }
}
