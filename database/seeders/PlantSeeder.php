<?php

namespace Database\Seeders;

use App\Models\Core\City;
use App\Models\Dairy\Plant;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    public function run(): void
    {
        // Ubigeos (región Puno) usados por las plantas. core_cities.code = ubigeo (PK string).
        $cities = [
            ['code' => '210101', 'department' => 'Puno', 'province' => 'Puno',      'district' => 'Puno',     'country' => 'PE'],
            ['code' => '211101', 'department' => 'Puno', 'province' => 'San Román', 'district' => 'Juliaca',  'country' => 'PE'],
            ['code' => '210301', 'department' => 'Puno', 'province' => 'Azángaro',  'district' => 'Azángaro', 'country' => 'PE'],
            ['code' => '210901', 'department' => 'Puno', 'province' => 'Melgar',     'district' => 'Ayaviri',  'country' => 'PE'],
            ['code' => '210106', 'department' => 'Puno', 'province' => 'Puno',      'district' => 'Huata',    'country' => 'PE'],
            ['code' => '211103', 'department' => 'Puno', 'province' => 'San Román', 'district' => 'Cabana',   'country' => 'PE'],
            ['code' => '210701', 'department' => 'Puno', 'province' => 'El Collao', 'district' => 'Ilave',    'country' => 'PE'],
        ];
        foreach ($cities as $city) {
            City::firstOrCreate(['code' => $city['code']], $city);
        }

        // Plantas con ubicación real (lat/long) en la región Puno
        $plants = [
            ['name' => 'Agroindustrias San Francisco Sinty Mayo EIRL', 'ruc' => '20448625819', 'cellphone' => '999000001', 'city' => '210101', 'latitude' => -15.8402, 'longitude' => -70.0219, 'type' => 'A'],
            ['name' => 'Agroindustrias Chrysef',                       'ruc' => '10021686609', 'cellphone' => '999000002', 'city' => '211101', 'latitude' => -15.4997, 'longitude' => -70.1333, 'type' => 'A'],
            ['name' => 'Grupo Yaguno S.A.C',                           'ruc' => '20448661025', 'cellphone' => '999000003', 'city' => '210301', 'latitude' => -14.9117, 'longitude' => -70.1903, 'type' => 'B'],
            ['name' => 'Cooperativa de Servicios de San Santiago',      'ruc' => '20448766471', 'cellphone' => '999000004', 'city' => '210901', 'latitude' => -14.8806, 'longitude' => -70.5897, 'type' => 'B'],
            ['name' => 'Ecolacteos Huata',                             'ruc' => '20172856960', 'cellphone' => '999000005', 'city' => '210106', 'latitude' => -15.7333, 'longitude' => -69.9667, 'type' => 'B'],
            ['name' => 'Cooperativa de Servicios Copagro Cabana Ltda', 'ruc' => '20448791310', 'cellphone' => '999000006', 'city' => '211103', 'latitude' => -15.6500, 'longitude' => -70.3500, 'type' => 'B'],
            ['name' => 'Planta Quesera Florentina',                    'ruc' => '10000000000', 'cellphone' => '999000007', 'city' => '210701', 'latitude' => -16.0883, 'longitude' => -69.6406, 'type' => 'B'],
        ];

        foreach ($plants as $plant) {
            Plant::create($plant);
        }
    }
}
