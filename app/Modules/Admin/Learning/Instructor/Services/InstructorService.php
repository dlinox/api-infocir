<?php

namespace App\Modules\Admin\Learning\Instructor\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Learning\Instructor\Repositories\InstructorRepository;
use App\Modules\Admin\Learning\Instructor\Repositories\Actions\CreateOrUpdateInstructorAction;

class InstructorService
{
    public function __construct(
        private InstructorRepository $instructorRepository,
        private CreateOrUpdateInstructorAction $createOrUpdateAction,
    ) {}

    public function dataTable(Request $request)
    {
        return $this->instructorRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->createOrUpdateAction->execute($data);
    }

    public function findByPersonId(int $personId)
    {
        return $this->instructorRepository->findByPersonId($personId);
    }

    public function delete(int $personId)
    {
        return $this->instructorRepository->delete($personId);
    }
}
