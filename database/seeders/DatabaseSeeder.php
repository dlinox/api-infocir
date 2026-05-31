<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            CoreSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            SuperAdminSeeder::class,
            DairySeeder::class,
            PlantSeeder::class,
            WorkerSeeder::class,
            StorefrontSeeder::class,
            FinanceDemoSeeder::class,
            // SupplierSeeder::class,
            // LearningSeeder::class,
            // LearningDataSeeder::class,
        ]);
    }
}
