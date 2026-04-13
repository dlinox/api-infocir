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
            DocumentType::create($docType);
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
            Gender::create($gender);
        }
    }

    private function seedUnitMeasures(): void
    {
        $units = [
            // Volumen (líquidos y capacidad)
            ['name' => 'Litro', 'abbreviation' => 'L'],
            ['name' => 'Mililitro', 'abbreviation' => 'mL'],
            ['name' => 'Galón', 'abbreviation' => 'gal'],

            // Masa (ingredientes y productos)
            ['name' => 'Kilogramo', 'abbreviation' => 'kg'],
            ['name' => 'Gramo', 'abbreviation' => 'g'],
            ['name' => 'Tonelada métrica', 'abbreviation' => 't'],
            ['name' => 'Libra', 'abbreviation' => 'lb'],
            ['name' => 'Arroba', 'abbreviation' => '@'],
            ['name' => 'Quintal', 'abbreviation' => 'qq'],

            // Temperatura (control de calidad)
            ['name' => 'Grado Celsius', 'abbreviation' => '°C'],

            // Unidades de conteo y empaque
            ['name' => 'Unidad', 'abbreviation' => 'und'],
            ['name' => 'Docena', 'abbreviation' => 'doc'],
            ['name' => 'Caja', 'abbreviation' => 'cja'],
            ['name' => 'Paquete', 'abbreviation' => 'pqt'],
            ['name' => 'Bolsa', 'abbreviation' => 'bls'],
            ['name' => 'Bidón', 'abbreviation' => 'bid'],
            ['name' => 'Tarro', 'abbreviation' => 'tar'],
            ['name' => 'Poronguito', 'abbreviation' => 'por'],

            // Otras unidades técnicas
            ['name' => 'Porcentaje', 'abbreviation' => '%'],
        ];

        foreach ($units as $unit) {
            UnitMeasure::create($unit);
        }
    }
}
