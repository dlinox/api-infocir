<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Core\Country;
use App\Models\Core\DocumentType;
use App\Models\Core\Gender;
use App\Models\Core\UnitMeasure;

class CoreSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->seedCountries();
        $this->seedDocumentTypes();
        $this->seedGenders();
        $this->seedUnitMeasures();
    }

    private function seedCountries(): void
    {
        Country::create([
            'code' => 'PE',
            'name' => 'Peru',
        ]);
    }

    private function seedDocumentTypes(): void
    {
        $documentTypes = [
            ['code' => '0', 'name' => 'Otros'],
            ['code' => '1', 'name' => 'DNI'],
            ['code' => '4', 'name' => 'Carnet de Extranjeria'],
            ['code' => '6', 'name' => 'RUC'],
            ['code' => '7', 'name' => 'Pasaporte'],
        ];

        foreach ($documentTypes as $docType) {
            DocumentType::create([...$docType, 'is_system' => true]);
        }
    }

    private function seedGenders(): void
    {
        $genders = [
            ['code' => '1', 'name' => 'Masculino'],
            ['code' => '2', 'name' => 'Femenino'],
            ['code' => '3', 'name' => 'No binario'],
            ['code' => '9', 'name' => 'Prefiero no decirlo'],
        ];

        foreach ($genders as $gender) {
            Gender::create([...$gender, 'is_system' => true]);
        }
    }

    private function seedUnitMeasures(): void
    {
        $gramo     = UnitMeasure::create(['name' => 'Gramo',    'abbreviation' => 'g',  'is_system' => true]);
        $mililitro = UnitMeasure::create(['name' => 'Mililitro','abbreviation' => 'mL', 'is_system' => true]);

        UnitMeasure::create([
            'name'              => 'Kilogramo',
            'abbreviation'      => 'kg',
            'base_unit_id'      => $gramo->id,
            'conversion_factor' => 1000,
            'is_system'         => true,
        ]);
        UnitMeasure::create([
            'name'              => 'Litro',
            'abbreviation'      => 'L',
            'base_unit_id'      => $mililitro->id,
            'conversion_factor' => 1000,
            'is_system'         => true,
        ]);
    }
}
