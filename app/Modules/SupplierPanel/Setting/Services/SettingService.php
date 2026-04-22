<?php

namespace App\Modules\SupplierPanel\Setting\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Dairy\Supplier;
use App\Modules\Auth\Services\AuthService;
use App\Modules\SupplierPanel\Setting\Repositories\SettingRepository;

class SettingService
{
    public function __construct(
        private SettingRepository $repository,
        private AuthService $authService,
    ) {}

    public function getCurrent(): Supplier
    {
        $entity = $this->authService->getMyEntity();

        if ($entity['type'] !== 'supplier') {
            throw new ApiException('La entidad autenticada no es un proveedor', 403);
        }

        $supplier = $this->repository->findById($entity['id']);
        if (!$supplier) throw new ApiException('Proveedor no encontrado', 404);

        return $supplier;
    }

    public function save(array $data): Supplier
    {
        $supplier = $this->getCurrent();
        return $this->repository->update($supplier, $data);
    }
}
