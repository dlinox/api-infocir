<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\TrainingLevelRepository;

class TrainingLevelService
{
    public function __construct(
        private TrainingLevelRepository $trainingLevelRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->trainingLevelRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->trainingLevelRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->trainingLevelRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->trainingLevelRepository->getSelectItems();
    }
}
