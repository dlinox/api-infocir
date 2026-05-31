<?php

namespace App\Modules\Admin\Security\Repositories;

use App\Models\Behavior\Role;

class RoleRepository
{
    public function dataTable($request)
    {
        $query = Role::query()->withCount('permissions');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('level')->orderBy('display_name');
        }

        return $query->dataTable($request);
    }

    public function findById(int $id): Role
    {
        return Role::with('permissions:id')->findOrFail($id);
    }

    public function createOrUpdate(array $data): Role
    {
        $permissionIds = $data['permission_ids'] ?? null;
        unset($data['permission_ids']);

        if (isset($data['id'])) {
            $role = Role::findOrFail($data['id']);
            $role->update($data);
        } else {
            $role = Role::create($data);
        }

        if (is_array($permissionIds)) {
            $role->permissions()->sync($permissionIds);
        }

        return $role;
    }

    public function delete(string $id): Role
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return $role;
    }

    public function getSelectItems()
    {
        return Role::where('is_active', true)->orderBy('display_name')->get();
    }
}
