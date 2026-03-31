<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Auth\User;
use App\Models\Behavior\BehaviorProfile;
use App\Models\Behavior\Role;
use App\Models\Core\Admin;
use App\Models\Core\Person;
use App\Models\Core\Profile as CoreProfile;

class SuperAdminSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Crear persona
        $person = Person::create([
            'document_type' => '1',
            'document_number' => '71822317',
            'name' => 'Denis Lino',
            'paternal_surname' => 'Puma',
            'maternal_surname' => 'Ticona',
            'gender' => '1',
            'country' => 'PE',
        ]);

        // 2. Crear admin (perfil tipo)
        $admin = Admin::create([
            'person_id' => $person->id,
            'is_active' => true,
        ]);

        // 3. Crear core_profile (puente polimorfico)
        $coreProfile = CoreProfile::create([
            'person_id' => $person->id,
            'profileable_type' => 'core_admins',
            'profileable_id' => $admin->person_id,
        ]);

        // 4. Crear usuario
        $user = User::create([
            'username' => 'linox',
            'email' => 'dpumaticona@gmail.com',
            'password' => 'linox',
            'is_active' => true,
        ]);

        // 5. Crear behavior_profile (enlaza user + role + core_profile)
        $role = Role::where('name', 'super_admin')->first();

        BehaviorProfile::create([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'core_profile_id' => $coreProfile->id,
            'is_active' => true,
        ]);
    }
}
