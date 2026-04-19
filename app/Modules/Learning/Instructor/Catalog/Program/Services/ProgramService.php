<?php

namespace App\Modules\Learning\Instructor\Catalog\Program\Services;

use Illuminate\Http\Request;
use App\Modules\Learning\Instructor\Catalog\Program\Repositories\ProgramRepository;

class ProgramService
{
    public function __construct(
        private ProgramRepository $programRepository,
    ) {}

    public function dataTable(Request $request)
    {
        return $this->programRepository->dataTable($request);
    }

    public function findById(int $id)
    {
        return $this->programRepository->findById($id);
    }

    public function save(array $data)
    {
        return $this->programRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->programRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->programRepository->getSelectItems();
    }
}
