<?php

namespace App\Modules\Admin\Security\Services;

use Illuminate\Http\Request;
use App\Common\Exceptions\ApiException;
use App\Models\Behavior\Permission;
use App\Modules\Admin\Security\Repositories\PermissionRepository;

class PermissionService
{
    public function __construct(
        private PermissionRepository $permissionRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->permissionRepository->dataTable($request);
    }

    public function findById(int $id): Permission
    {
        return $this->permissionRepository->findById($id);
    }

    public function save(array $data): Permission
    {
        if (isset($data['id']) && (int) ($data['parent_id'] ?? 0) === (int) $data['id']) {
            throw new ApiException('Un permiso no puede ser su propio padre.');
        }

        return $this->permissionRepository->createOrUpdate($data);
    }

    public function delete(string $id): Permission
    {
        $permission = Permission::findOrFail($id);

        if ($permission->children()->exists()) {
            throw new ApiException('No se puede eliminar un permiso que tiene permisos hijos.');
        }

        return $this->permissionRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->permissionRepository->getSelectItems();
    }
}
