<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\TrainingTypeRepository;

class TrainingTypeService
{
    public function __construct(
        private TrainingTypeRepository $trainingTypeRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->trainingTypeRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->trainingTypeRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->trainingTypeRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->trainingTypeRepository->getSelectItems();
    }
}
