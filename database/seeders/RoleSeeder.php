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
                'name'         => 'super_admin',
                'display_name' => 'Super Administrador',
                'redirect_to'  => '/admin/home',
                'level'        => '0',
                'scope'        => 'admin',
                'is_active'    => true,
            ],
            [
                'name'         => 'admin',
                'display_name' => 'Administrador',
                'redirect_to'  => '/admin/home',
                'level'        => '1',
                'scope'        => 'admin',
                'is_active'    => true,
            ],
            [
                'name'         => 'plant_manager',
                'display_name' => 'Administrador de planta',
                'redirect_to'  => '/m/pl',
                'level'        => '2',
                'scope'        => 'plant',
                'is_active'    => true,
            ],
            [
                'name'         => 'plant_collector',
                'display_name' => 'Acopiador de planta',
                'redirect_to'  => '/m/pl',
                'level'        => '2',
                'scope'        => 'plant',
                'is_active'    => true,
            ],
            [
                'name'         => 'supplier_manager',
                'display_name' => 'Administrador de proveedor',
                'redirect_to'  => '/m/sup',
                'level'        => '2',
                'scope'        => 'supplier',
                'is_active'    => true,
            ],
            [
                'name'         => 'supplier_delivery',
                'display_name' => 'Repartidor de proveedor',
                'redirect_to'  => '/m/sup',
                'level'        => '2',
                'scope'        => 'supplier',
                'is_active'    => true,
            ],
            [
                'name'         => 'instructor',
                'display_name' => 'Instructor',
                'redirect_to'  => '/in',
                'level'        => '3',
                'scope'        => 'instructor',
                'is_active'    => true,
            ],
            [
                'name'         => 'worker',
                'display_name' => 'Trabajador',
                'redirect_to'  => '/wo',
                'level'        => '3',
                'scope'        => 'worker',
                'is_active'    => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
