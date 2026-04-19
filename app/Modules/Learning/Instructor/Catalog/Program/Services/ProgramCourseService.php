<?php

namespace App\Modules\Learning\Instructor\Catalog\Program\Services;

use App\Modules\Learning\Instructor\Catalog\Program\Repositories\ProgramCourseRepository;

class ProgramCourseService
{
    public function __construct(
        private ProgramCourseRepository $programCourseRepository
    ) {}

    public function save(array $data)
    {
        return $this->programCourseRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->programCourseRepository->delete($id);
    }
}
