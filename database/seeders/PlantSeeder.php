<?php

namespace Database\Seeders;

use App\Models\Dairy\Plant;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    public function run(): void
    {
        $plants = [
            ['name' => 'Agroindustrias San Francisco Sinty Mayo EIRL', 'ruc' => '20448625819', 'cellphone' => '999000001'],
            ['name' => 'Agroindustrias Chrysef',                       'ruc' => '10021686609', 'cellphone' => '999000002'],
            ['name' => 'Grupo Yaguno S.A.C',                           'ruc' => '20448661025', 'cellphone' => '999000003'],
            ['name' => 'Cooperativa de Servicios de San Santiago',      'ruc' => '20448766471', 'cellphone' => '999000004'],
            ['name' => 'Ecolacteos Huata',                             'ruc' => '20172856960', 'cellphone' => '999000005'],
            ['name' => 'Cooperativa de Servicios Copagro Cabana Ltda', 'ruc' => '20448791310', 'cellphone' => '999000006'],
            ['name' => 'Planta Quesera Florentina',                    'ruc' => '10000000000', 'cellphone' => '999000007'],
        ];

        foreach ($plants as $plant) {
            Plant::create($plant);
        }
    }
}