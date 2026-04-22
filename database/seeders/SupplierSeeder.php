<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use App\Models\Behavior\BehaviorProfile;
use App\Models\Behavior\Role;
use App\Models\Core\Person;
use App\Models\Core\Profile;
use App\Models\Dairy\Supplier;
use App\Models\Dairy\Worker;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['supplier_type' => 'individual', 'document_type' => '1', 'document_number' => '45678901', 'name' => 'Juan Perez Mamani',   'trade_name' => 'Establo Perez',    'cellphone' => '951000001', 'community' => 'Cabana',      'total_cows' => 15, 'cows_in_production' => 10, 'dry_cows' => 5, 'reference_price_per_liter' => 1.80],
            ['supplier_type' => 'individual', 'document_type' => '1', 'document_number' => '45678902', 'name' => 'Maria Quispe Flores',  'trade_name' => 'Ganaderia Quispe', 'cellphone' => '951000002', 'community' => 'Lampa',       'total_cows' => 8,  'cows_in_production' => 5,  'dry_cows' => 3, 'reference_price_per_liter' => 1.75],
            ['supplier_type' => 'individual', 'document_type' => '1', 'document_number' => '45678903', 'name' => 'Pedro Huanca Condori', 'trade_name' => 'Establo Huanca',   'cellphone' => '951000003', 'community' => 'Cabanillas',  'total_cows' => 20, 'cows_in_production' => 14, 'dry_cows' => 6, 'reference_price_per_liter' => 1.85],
            ['supplier_type' => 'individual', 'document_type' => '1', 'document_number' => '45678904', 'name' => 'Rosa Apaza Chura',     'trade_name' => 'Ganaderia Apaza',  'cellphone' => '951000004', 'community' => 'Ayaviri',     'total_cows' => 12, 'cows_in_production' => 8,  'dry_cows' => 4, 'reference_price_per_liter' => 1.80],
        ];

        foreach ($suppliers as $data) {
            Supplier::create($data);
        }

        $this->seedSupplierWorkers();
    }

    private function seedSupplierWorkers(): void
    {
        $s1 = Supplier::where('document_number', '45678901')->first();
        $s2 = Supplier::where('document_number', '45678902')->first();
        $s3 = Supplier::where('document_number', '45678903')->first();
        $s4 = Supplier::where('document_number', '45678904')->first();

        $workerRole          = Role::where('name', 'worker')->first();
        $supplierManagerRole = Role::where('name', 'supplier_manager')->first();

        $workers = [
            [['document_type' => '1', 'document_number' => '72000001', 'name' => 'Juan',  'paternal_surname' => 'Perez',  'maternal_surname' => 'Mamani',  'gender' => '1', 'country' => 'PE'], $s1->entity->id],
            [['document_type' => '1', 'document_number' => '72000002', 'name' => 'Sofia', 'paternal_surname' => 'Torres', 'maternal_surname' => 'Quispe',  'gender' => '2', 'country' => 'PE'], $s1->entity->id],
            [['document_type' => '1', 'document_number' => '72000003', 'name' => 'Maria', 'paternal_surname' => 'Quispe', 'maternal_surname' => 'Flores',  'gender' => '2', 'country' => 'PE'], $s2->entity->id],
            [['document_type' => '1', 'document_number' => '72000004', 'name' => 'Luis',  'paternal_surname' => 'Calla',  'maternal_surname' => 'Mamani',  'gender' => '1', 'country' => 'PE'], $s2->entity->id],
            [['document_type' => '1', 'document_number' => '72000005', 'name' => 'Pedro', 'paternal_surname' => 'Huanca', 'maternal_surname' => 'Condori', 'gender' => '1', 'country' => 'PE'], $s3->entity->id],
            [['document_type' => '1', 'document_number' => '72000006', 'name' => 'Nadia', 'paternal_surname' => 'Puma',   'maternal_surname' => 'Coila',   'gender' => '2', 'country' => 'PE'], $s3->entity->id],
            [['document_type' => '1', 'document_number' => '72000007', 'name' => 'Rosa',  'paternal_surname' => 'Apaza',  'maternal_surname' => 'Chura',   'gender' => '2', 'country' => 'PE'], $s4->entity->id],
            [['document_type' => '1', 'document_number' => '72000008', 'name' => 'Marco', 'paternal_surname' => 'Larico', 'maternal_surname' => 'Suca',    'gender' => '1', 'country' => 'PE'], $s4->entity->id],
        ];

        foreach ($workers as [$personData, $entityId]) {
            $this->createWorkerWithUser($personData, $entityId, [$workerRole->id, $supplierManagerRole->id]);
        }
    }

    private function createWorkerWithUser(array $personData, int $entityId, array $roleIds): void
    {
        $person = Person::create($personData);

        Worker::create([
            'person_id' => $person->id,
            'entity_id' => $entityId,
            'is_active' => true,
        ]);

        $coreProfile = Profile::create([
            'person_id'        => $person->id,
            'profileable_type' => 'dairy_workers',
            'profileable_id'   => $person->id,
        ]);

        $user = User::create([
            'username'  => $person->document_number,
            'password'  => $person->document_number,
            'is_active' => true,
        ]);

        foreach ($roleIds as $roleId) {
            BehaviorProfile::create([
                'user_id'         => $user->id,
                'role_id'         => $roleId,
                'core_profile_id' => $coreProfile->id,
                'is_active'       => true,
            ]);
        }
    }
}