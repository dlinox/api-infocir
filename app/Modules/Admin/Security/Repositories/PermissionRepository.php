<?php

namespace App\Modules\Admin\Security\Repositories;

use App\Models\Behavior\Permission;

class PermissionRepository
{
    public function dataTable($request)
    {
        $query = Permission::query()->with('parent:id,display_name');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('level')->orderBy('display_name');
        }

        return $query->dataTable($request);
    }

    public function findById(int $id): Permission
    {
        return Permission::with('parent:id,display_name')->findOrFail($id);
    }

    public function createOrUpdate(array $data): Permission
    {
        if (isset($data['id'])) {
            $permission = Permission::findOrFail($data['id']);
            $permission->update($data);
            return $permission;
        }

        return Permission::create($data);
    }

    public function delete(string $id): Permission
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return $permission;
    }

    public function getSelectItems()
    {
        return Permission::orderBy('display_name')->get();
    }
}
