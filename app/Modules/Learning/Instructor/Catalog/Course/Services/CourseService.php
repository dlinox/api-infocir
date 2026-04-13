<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Services;

use Illuminate\Http\Request;
use App\Modules\Learning\Instructor\Catalog\Course\Repositories\CourseRepository;

class CourseService
{
    public function __construct(
        private CourseRepository $courseRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->courseRepository->dataTable($request);
    }

    public function findById(int $id)
    {
        return $this->courseRepository->findById($id);
    }

    public function save(array $data)
    {
        return $this->courseRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->courseRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->courseRepository->getSelectItems();
    }
}
