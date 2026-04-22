<?php

namespace App\Modules\PlantPanel\ProductionBatch\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Dairy\ProductionBatch;
use App\Models\Dairy\Supplier;
use App\Modules\Auth\Services\AuthService;
use App\Modules\PlantPanel\ProductionBatch\Repositories\ProductionBatchRepository;
use Illuminate\Http\Request;

class ProductionBatchService
{
    public function __construct(
        private ProductionBatchRepository $repository,
        private AuthService $authService,
    ) {}

    public function dataTable(Request $request)
    {
        $plantId = $this->authService->getMyPlantId();
        return $this->repository->dataTable($request, $plantId);
    }

    public function findById(int $id): ProductionBatch
    {
        $plantId = $this->authService->getMyPlantId();
        $batch = $this->repository->findByIdForPlant($id, $plantId);
        if (!$batch) throw new ApiException('Lote no encontrado', 404);
        return $batch;
    }

    public function save(array $data): ProductionBatch
    {
        $plantId = $this->authService->getMyPlantId();
        return $this->repository->createOrUpdate($data, $plantId);
    }

    public function delete(int $id): void
    {
        $plantId = $this->authService->getMyPlantId();
        $this->repository->delete($id, $plantId);
    }

    public function cancel(int $id): void
    {
        $plantId = $this->authService->getMyPlantId();
        $batch = $this->repository->findByIdForPlant($id, $plantId);
        if (!$batch) throw new ApiException('Lote no encontrado', 404);
        $batch->update(['status' => 'rejected']);
    }

    public function markReady(int $id): void
    {
        $plantId = $this->authService->getMyPlantId();
        $this->repository->markReady($id, $plantId);
    }

    public function getSupplierSelectItems(): array
    {
        return Supplier::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'trade_name'])
            ->map(fn ($s) => [
                'value' => $s->id,
                'title' => $s->trade_name ?: $s->name,
            ])
            ->toArray();
    }
}
