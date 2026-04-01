<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Behavior\Role;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrador',
                'redirect_to' => '/admin/home',
                'level' => '0',
                'is_active' => true,
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrador',
                'redirect_to' => '/admin/home',
                'level' => '1',
                'is_active' => true,
            ],
            [
                'name' => 'plant_worker',
                'display_name' => 'Colaborador de planta',
                'redirect_to' => '/m/wo',
                'level' => '2',
                'is_active' => true,
            ],
            [
                'name' => 'plant_manager',
                'display_name' => 'Administrador de planta',
                'redirect_to' => '/m/pl',
                'level' => '2',
                'is_active' => true,
            ],
            [
                'name' => 'supplier_manager',
                'display_name' => 'Proveedor',
                'redirect_to' => '/m/sl',
                'level' => '2',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
