<?php

namespace App\Modules\PlantPanel\Worker\Services;

use App\Modules\Auth\Services\AuthService;
use App\Modules\Admin\Dairy\Organization\Worker\Repositories\WorkerRepository;
use App\Modules\Admin\Dairy\Organization\Worker\Repositories\Actions\CreateOrUpdateWorkerAction;
use Illuminate\Http\Request;

class PlantWorkerService
{
    public function __construct(
        private WorkerRepository $workerRepository,
        private CreateOrUpdateWorkerAction $createOrUpdateAction,
        private AuthService $authService,
    ) {}

    public function dataTable(Request $request)
    {
        $entityId = $this->authService->getMyEntityId();
        return $this->workerRepository->dataTableForEntity($request, $entityId);
    }

    public function save(array $data)
    {
        $data['entity_id'] = $this->authService->getMyEntityId();
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
