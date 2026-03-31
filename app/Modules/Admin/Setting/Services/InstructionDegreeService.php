<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\InstructionDegreeRepository;

class InstructionDegreeService
{
    public function __construct(
        private InstructionDegreeRepository $instructionDegreeRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->instructionDegreeRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->instructionDegreeRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->instructionDegreeRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->instructionDegreeRepository->getSelectItems();
    }
}
