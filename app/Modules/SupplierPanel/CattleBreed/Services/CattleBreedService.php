<?php

namespace App\Modules\SupplierPanel\CattleBreed\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Dairy\SupplierCattleBreed;
use App\Modules\Auth\Services\AuthService;
use App\Modules\SupplierPanel\CattleBreed\Repositories\CattleBreedRepository;
use Illuminate\Http\Request;

class CattleBreedService
{
    public function __construct(
        private CattleBreedRepository $repository,
        private AuthService $authService,
    ) {}

    public function dataTable(Request $request)
    {
        $supplierId = $this->authService->getMySupplierId();
        return $this->repository->dataTable($request, $supplierId);
    }

    public function findById(int $id): SupplierCattleBreed
    {
        $supplierId = $this->authService->getMySupplierId();
        $record = $this->repository->findByIdForSupplier($id, $supplierId);
        if (!$record) throw new ApiException('Raza no encontrada', 404);
        return $record;
    }

    public function save(array $data): SupplierCattleBreed
    {
        $supplierId = $this->authService->getMySupplierId();
        return $this->repository->createOrUpdate($data, $supplierId);
    }

    public function delete(int $id): void
    {
        $supplierId = $this->authService->getMySupplierId();
        $this->repository->delete($id, $supplierId);
    }
}
