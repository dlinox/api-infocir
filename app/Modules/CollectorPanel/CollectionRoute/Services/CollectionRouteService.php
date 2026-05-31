<?php

namespace App\Modules\CollectorPanel\CollectionRoute\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Dairy\CollectionRoute;
use App\Modules\Auth\Services\AuthService;
use App\Modules\CollectorPanel\CollectionRoute\Repositories\CollectionRouteRepository;
use Illuminate\Support\Facades\DB;

class CollectionRouteService
{
    public function __construct(
        private CollectionRouteRepository $repository,
        private AuthService $authService,
    ) {}

    public function getActive(): ?CollectionRoute
    {
        $plantId      = $this->authService->getMyPlantId();
        $collectorId  = $this->getCollectorId();
        return $this->repository->getActive($plantId, $collectorId);
    }

    public function start(array $data): CollectionRoute
    {
        $plantId     = $this->authService->getMyPlantId();
        $collectorId = $this->getCollectorId();

        $existing = $this->repository->getActive($plantId, $collectorId);
        if ($existing) {
            throw new ApiException('Ya tienes un recorrido activo. Finalízalo antes de iniciar uno nuevo.', 422);
        }

        return $this->repository->create(array_merge($data, [
            'plant_id'     => $plantId,
            'collector_id' => $collectorId,
            'started_at'   => now(),
            'status'       => 'active',
        ]));
    }

    public function finalize(int $routeId, array $data): CollectionRoute
    {
        $plantId = $this->authService->getMyPlantId();
        $route   = $this->repository->findByIdAndPlant($routeId, $plantId);

        if (!$route) throw new ApiException('Recorrido no encontrado.', 404);
        if ($route->status === 'completed') throw new ApiException('Este recorrido ya fue finalizado.', 422);

        $expenses = $data['expenses'] ?? [];
        unset($data['expenses']);

        return DB::transaction(fn () => $this->repository->finalize($route, $data, $expenses));
    }

    public function getRouteExpenseItems(): mixed
    {
        return $this->repository->getRouteExpenseItems();
    }

    public function dataTable($request): mixed
    {
        $plantId     = $this->authService->getMyPlantId();
        $collectorId = $this->getCollectorId();
        return $this->repository->dataTable($request, $plantId, $collectorId);
    }

    public function stats(?string $dateFrom, ?string $dateTo): array
    {
        $plantId     = $this->authService->getMyPlantId();
        $collectorId = $this->getCollectorId();
        return $this->repository->stats($plantId, $collectorId, $dateFrom, $dateTo);
    }

    private function getCollectorId(): int
    {
        $profileId = $this->authService->getProfileIdFromToken();
        if (!$profileId) throw new ApiException('No se encontró el perfil.', 401);

        $profile = \App\Models\Behavior\BehaviorProfile::find($profileId);
        if (!$profile) throw new ApiException('Perfil no encontrado.', 404);

        return $profile->user_id;
    }
}
