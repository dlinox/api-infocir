<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Behavior\Permission;
use App\Models\Behavior\Role;

class PermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Catálogo jerárquico inicial: module -> menu/view -> action.
     * Estructura: [name, display_name, type, level, children[]]
     */
    private array $catalog = [
        [
            'name' => 'security', 'display_name' => 'Seguridad', 'type' => 'module', 'level' => 1,
            'children' => [
                ['name' => 'security.users', 'display_name' => 'Usuarios', 'type' => 'view', 'level' => 2, 'children' => [
                    ['name' => 'security.users.view', 'display_name' => 'Ver usuarios', 'type' => 'action', 'level' => 3],
                    ['name' => 'security.users.manage', 'display_name' => 'Gestionar usuarios', 'type' => 'action', 'level' => 3],
                ]],
                ['name' => 'security.roles', 'display_name' => 'Roles', 'type' => 'view', 'level' => 2, 'children' => [
                    ['name' => 'security.roles.view', 'display_name' => 'Ver roles', 'type' => 'action', 'level' => 3],
                    ['name' => 'security.roles.manage', 'display_name' => 'Gestionar roles', 'type' => 'action', 'level' => 3],
                ]],
                ['name' => 'security.permissions', 'display_name' => 'Permisos', 'type' => 'view', 'level' => 2, 'children' => [
                    ['name' => 'security.permissions.view', 'display_name' => 'Ver permisos', 'type' => 'action', 'level' => 3],
                    ['name' => 'security.permissions.manage', 'display_name' => 'Gestionar permisos', 'type' => 'action', 'level' => 3],
                ]],
                ['name' => 'security.sessions', 'display_name' => 'Sesiones', 'type' => 'view', 'level' => 2, 'children' => [
                    ['name' => 'security.sessions.view', 'display_name' => 'Ver sesiones', 'type' => 'action', 'level' => 3],
                    ['name' => 'security.sessions.revoke', 'display_name' => 'Revocar sesiones', 'type' => 'action', 'level' => 3],
                ]],
            ],
        ],
        [
            'name' => 'organization', 'display_name' => 'Organización', 'type' => 'module', 'level' => 1,
            'children' => [
                ['name' => 'organization.plants', 'display_name' => 'Plantas', 'type' => 'view', 'level' => 2],
                ['name' => 'organization.suppliers', 'display_name' => 'Proveedores', 'type' => 'view', 'level' => 2],
                ['name' => 'organization.workers', 'display_name' => 'Trabajadores', 'type' => 'view', 'level' => 2],
            ],
        ],
        [
            'name' => 'catalog', 'display_name' => 'Catálogos', 'type' => 'module', 'level' => 1,
            'children' => [
                ['name' => 'catalog.view', 'display_name' => 'Ver catálogos', 'type' => 'action', 'level' => 2],
                ['name' => 'catalog.manage', 'display_name' => 'Gestionar catálogos', 'type' => 'action', 'level' => 2],
            ],
        ],
        [
            'name' => 'settings', 'display_name' => 'Ajustes', 'type' => 'module', 'level' => 1,
            'children' => [
                ['name' => 'settings.view', 'display_name' => 'Ver ajustes', 'type' => 'action', 'level' => 2],
                ['name' => 'settings.manage', 'display_name' => 'Gestionar ajustes', 'type' => 'action', 'level' => 2],
            ],
        ],
    ];

    public function run(): void
    {
        foreach ($this->catalog as $node) {
            $this->upsertNode($node, null);
        }

        $allPermissionIds = Permission::pluck('id')->all();

        foreach (['super_admin', 'admin'] as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->permissions()->sync($allPermissionIds);
            }
        }
    }

    private function upsertNode(array $node, ?int $parentId): void
    {
        $permission = Permission::updateOrCreate(
            ['name' => $node['name']],
            [
                'display_name' => $node['display_name'],
                'type'         => $node['type'],
                'parent_id'    => $parentId,
                'level'        => $node['level'],
            ]
        );

        foreach ($node['children'] ?? [] as $child) {
            $this->upsertNode($child, $permission->id);
        }
    }
}
