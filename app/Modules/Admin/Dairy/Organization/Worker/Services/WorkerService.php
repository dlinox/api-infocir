<?php

namespace App\Modules\Admin\Dairy\Organization\Worker\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Dairy\Organization\Worker\Repositories\WorkerRepository;
use App\Modules\Admin\Dairy\Organization\Worker\Repositories\Actions\CreateOrUpdateWorkerAction;

class WorkerService
{
    public function __construct(
        private WorkerRepository $workerRepository,
        private CreateOrUpdateWorkerAction $createOrUpdateAction,
    ) {}

    public function dataTable(Request $request)
    {
        return $this->workerRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->createOrUpdateAction->execute($data);
    }

    public function findByPersonId(int $personId)
    {
        return $this->workerRepository->findByPersonId($personId);
    }

    public function delete(int $personId)
    {
        return $this->workerRepository->delete($personId);
    }
}
