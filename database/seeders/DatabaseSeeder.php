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
            SuperAdminSeeder::class,
            DairySeeder::class,
            PlantSeeder::class,
            SupplierSeeder::class,
            LearningSeeder::class,
            LearningDataSeeder::class,
        ]);
    }
}
