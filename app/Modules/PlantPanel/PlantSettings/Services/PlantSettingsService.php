<?php

namespace App\Modules\PlantPanel\PlantSettings\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Dairy\Plant;
use App\Modules\Auth\Services\AuthService;
use App\Modules\PlantPanel\PlantSettings\Repositories\PlantSettingsRepository;

class PlantSettingsService
{
    public function __construct(
        private PlantSettingsRepository $repository,
        private AuthService $authService,
    ) {}

    public function get(): Plant
    {
        $plantId = $this->authService->getMyPlantId();
        $plant   = $this->repository->findById($plantId);

        if (!$plant) throw new ApiException('Planta no encontrada', 404);

        return $plant;
    }

    public function update(array $data): Plant
    {
        $plant = $this->get();
        $plant->update($data);
        return $plant->fresh();
    }
}
