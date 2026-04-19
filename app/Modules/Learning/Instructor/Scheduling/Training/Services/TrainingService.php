<?php

namespace App\Modules\Learning\Instructor\Scheduling\Training\Services;

use Illuminate\Http\Request;
use App\Modules\Learning\Instructor\Scheduling\Training\Repositories\TrainingRepository;

class TrainingService
{
    public function __construct(
        private TrainingRepository $trainingRepository,
    ) {}

    public function dataTable(Request $request)
    {
        return $this->trainingRepository->dataTable($request);
    }

    public function findById(int $id)
    {
        return $this->trainingRepository->findById($id);
    }

    public function save(array $data)
    {
        return $this->trainingRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->trainingRepository->delete($id);
    }
}
