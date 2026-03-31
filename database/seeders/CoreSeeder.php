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
            // Volumen
            ['name' => 'Litro', 'abbreviation' => 'L'],
            ['name' => 'Mililitro', 'abbreviation' => 'mL'],
            ['name' => 'Metro cúbico', 'abbreviation' => 'm³'],
            ['name' => 'Decilitro', 'abbreviation' => 'dL'],
            ['name' => 'Centilitro', 'abbreviation' => 'cL'],
            ['name' => 'Galón', 'abbreviation' => 'gal'],

            // Longitud
            ['name' => 'Metro', 'abbreviation' => 'm'],
            ['name' => 'Centímetro', 'abbreviation' => 'cm'],
            ['name' => 'Milímetro', 'abbreviation' => 'mm'],
            ['name' => 'Kilómetro', 'abbreviation' => 'km'],
            ['name' => 'Pulgada', 'abbreviation' => 'in'],
            ['name' => 'Pie', 'abbreviation' => 'ft'],

            // Área
            ['name' => 'Metro cuadrado', 'abbreviation' => 'm²'],
            ['name' => 'Hectárea', 'abbreviation' => 'ha'],
            ['name' => 'Centímetro cuadrado', 'abbreviation' => 'cm²'],
            ['name' => 'Kilómetro cuadrado', 'abbreviation' => 'km²'],

            // Tiempo
            ['name' => 'Segundo', 'abbreviation' => 's'],
            ['name' => 'Minuto', 'abbreviation' => 'min'],
            ['name' => 'Hora', 'abbreviation' => 'h'],
            ['name' => 'Día', 'abbreviation' => 'd'],
            ['name' => 'Mes', 'abbreviation' => 'mes'],
            ['name' => 'Año', 'abbreviation' => 'año'],

            // Temperatura
            ['name' => 'Grado Celsius', 'abbreviation' => '°C'],
            ['name' => 'Grado Fahrenheit', 'abbreviation' => '°F'],
            ['name' => 'Kelvin', 'abbreviation' => 'K'],

            // Unidades específicas lácteas
            ['name' => 'Unidad', 'abbreviation' => 'und'],
            ['name' => 'Docena', 'abbreviation' => 'doc'],
            ['name' => 'Caja', 'abbreviation' => 'cja'],
            ['name' => 'Paquete', 'abbreviation' => 'pqt'],
            ['name' => 'Bolsa', 'abbreviation' => 'bls'],
            ['name' => 'Bidón', 'abbreviation' => 'bid'],
            ['name' => 'Tarro', 'abbreviation' => 'tar'],
            ['name' => 'Poronguito', 'abbreviation' => 'por'],

            // Otras unidades
            ['name' => 'Porcentaje', 'abbreviation' => '%'],
            ['name' => 'Partes por millón', 'abbreviation' => 'ppm'],
            ['name' => 'Grado alcohólico', 'abbreviation' => '°'],

            // Masa
            ['name' => 'Kilogramo', 'abbreviation' => 'kg'],
            ['name' => 'Gramo', 'abbreviation' => 'g'],
            ['name' => 'Miligramo', 'abbreviation' => 'mg'],
            ['name' => 'Tonelada métrica', 'abbreviation' => 't'],
            ['name' => 'Libra', 'abbreviation' => 'lb'],
            ['name' => 'Onza', 'abbreviation' => 'oz'],
            ['name' => 'Arroba', 'abbreviation' => '@'],
            ['name' => 'Quintal', 'abbreviation' => 'qq'],
        ];

        foreach ($units as $unit) {
            UnitMeasure::create($unit);
        }
    }
}
