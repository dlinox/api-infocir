<?php

namespace App\Modules\Admin\Security\Services;

use Illuminate\Http\Request;
use App\Common\Exceptions\ApiException;
use App\Models\Behavior\Role;
use App\Modules\Admin\Security\Repositories\RoleRepository;

class RoleService
{
    private const SYSTEM_ROLES = ['super_admin', 'admin'];

    public function __construct(
        private RoleRepository $roleRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->roleRepository->dataTable($request);
    }

    public function findById(int $id): Role
    {
        return $this->roleRepository->findById($id);
    }

    public function save(array $data): Role
    {
        if (isset($data['id'])) {
            $current = Role::findOrFail($data['id']);

            if (in_array($current->name, self::SYSTEM_ROLES, true)) {
                $data['name']  = $current->name;
                $data['level'] = $current->level;
            }
        }

        return $this->roleRepository->createOrUpdate($data);
    }

    public function delete(string $id): Role
    {
        $role = Role::findOrFail($id);

        if (in_array($role->name, self::SYSTEM_ROLES, true)) {
            throw new ApiException('No se puede eliminar un rol del sistema.');
        }

        if ($role->profiles()->exists()) {
            throw new ApiException('No se puede eliminar un rol asignado a uno o más usuarios.');
        }

        return $this->roleRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->roleRepository->getSelectItems();
    }
}
