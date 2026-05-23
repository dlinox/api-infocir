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
        $units = [

            // Unidades de conteo y empaque
            ['name' => 'Unidad', 'abbreviation' => 'und'],
            ['name' => 'Docena', 'abbreviation' => 'doc'],
            ['name' => 'Caja', 'abbreviation' => 'cja'],
            ['name' => 'Paquete', 'abbreviation' => 'pqt'],

            // Volumen (líquidos y capacidad)
            ['name' => 'Litro', 'abbreviation' => 'L'],
            ['name' => 'Mililitro', 'abbreviation' => 'mL'],

            // Masa (ingredientes y productos)
            ['name' => 'Kilogramo', 'abbreviation' => 'kg'],
            ['name' => 'Gramo', 'abbreviation' => 'g'],

        ];

        foreach ($units as $unit) {
            UnitMeasure::create([...$unit, 'is_system' => true]);
        }
    }
}
