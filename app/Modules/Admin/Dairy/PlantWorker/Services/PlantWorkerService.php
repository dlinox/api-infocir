<?php

namespace App\Modules\Admin\Dairy\PlantWorker\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Dairy\PlantWorker\Repositories\PlantWorkerRepository;
use App\Modules\Admin\Dairy\PlantWorker\Repositories\Actions\CreateOrUpdatePlantWorkerAction;

class PlantWorkerService
{
    public function __construct(
        private PlantWorkerRepository $plantWorkerRepository,
        private CreateOrUpdatePlantWorkerAction $createOrUpdateAction,
    ) {}

    public function dataTable(Request $request)
    {
        return $this->plantWorkerRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->createOrUpdateAction->execute($data);
    }

    public function findByPersonId(int $personId)
    {
        return $this->plantWorkerRepository->findByPersonId($personId);
    }

    public function delete(int $personId)
    {
        return $this->plantWorkerRepository->delete($personId);
    }
}
