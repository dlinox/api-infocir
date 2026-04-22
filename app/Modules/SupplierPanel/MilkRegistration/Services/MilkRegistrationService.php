<?php

namespace App\Modules\SupplierPanel\MilkRegistration\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Dairy\SupplierMilkRegistration;
use App\Modules\Auth\Services\AuthService;
use App\Modules\SupplierPanel\MilkRegistration\Repositories\MilkRegistrationRepository;
use Illuminate\Http\Request;

class MilkRegistrationService
{
    public function __construct(
        private MilkRegistrationRepository $repository,
        private AuthService $authService,
    ) {}

    public function dataTable(Request $request)
    {
        $supplierId = $this->authService->getMySupplierId();
        return $this->repository->dataTable($request, $supplierId);
    }

    public function findById(int $id): SupplierMilkRegistration
    {
        $supplierId = $this->authService->getMySupplierId();
        $record = $this->repository->findByIdForSupplier($id, $supplierId);
        if (!$record) throw new ApiException('Registro no encontrado', 404);
        return $record;
    }

    public function save(array $data): SupplierMilkRegistration
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
