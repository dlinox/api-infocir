<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Core\InstructionDegree;
use App\Models\Core\Profession;
use App\Models\Dairy\CompanyType;
use App\Models\Dairy\TrainingLevel;
use App\Models\Dairy\InstitutionType;
use App\Models\Dairy\Position;
use App\Models\Dairy\ProductType;
use App\Models\Dairy\Supply;

class DairySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->seedInstructionDegrees();
        $this->seedProfessions();
        $this->seedCompanyTypes();
        $this->seedTrainingLevels();
        $this->seedInstitutionTypes();
        $this->seedPositions();
        $this->seedProductTypes();
        $this->seedSupplies();
    }

    private function seedInstructionDegrees(): void
    {
        $items = [
            ['name' => 'Sin instrucción'],
            ['name' => 'Primaria incompleta'],
            ['name' => 'Primaria completa'],
            ['name' => 'Secundaria incompleta'],
            ['name' => 'Secundaria completa'],
            ['name' => 'Técnico incompleto'],
            ['name' => 'Técnico completo'],
            ['name' => 'Universitario incompleto'],
            ['name' => 'Universitario completo'],
            ['name' => 'Maestría'],
            ['name' => 'Doctorado'],
        ];

        foreach ($items as $item) {
            InstructionDegree::create($item);
        }
    }

    private function seedProfessions(): void
    {
        $items = [
            ['name' => 'Ingeniero en Industrias Alimentarias'],
            ['name' => 'Médico Veterinario'],
            ['name' => 'Ingeniero Zootecnista'],
            ['name' => 'Ingeniero Agroindustrial'],
            ['name' => 'Técnico en Producción Agropecuaria'],
            ['name' => 'Técnico en Industrias Alimentarias'],
            ['name' => 'Biólogo'],
            ['name' => 'Químico'],
            ['name' => 'Ingeniero Agrónomo'],
            ['name' => 'Administrador de Empresas'],
            ['name' => 'Contador Público'],
            ['name' => 'Otro'],
        ];

        foreach ($items as $item) {
            Profession::create($item);
        }
    }

    private function seedCompanyTypes(): void
    {
        $items = [
            ['name' => 'Asociación de productores'],
            ['name' => 'Cooperativa agraria'],
            ['name' => 'Empresa individual'],
            ['name' => 'Sociedad anónima cerrada (S.A.C.)'],
            ['name' => 'Sociedad de responsabilidad limitada (S.R.L.)'],
            ['name' => 'Comunidad campesina'],
            ['name' => 'Empresa comunal'],
        ];

        foreach ($items as $item) {
            CompanyType::create($item);
        }
    }

    private function seedTrainingLevels(): void
    {
        $items = [
            ['name' => 'Sin capacitación'],
            ['name' => 'Capacitación básica'],
            ['name' => 'Capacitación intermedia'],
            ['name' => 'Capacitación avanzada'],
            ['name' => 'Capacitación especializada'],
            ['name' => 'Certificación técnica'],
        ];

        foreach ($items as $item) {
            TrainingLevel::create($item);
        }
    }

    private function seedInstitutionTypes(): void
    {
        $items = [
            ['name' => 'SENASA', 'nature' => 'public', 'level' => 'national'],
            ['name' => 'DIGESA', 'nature' => 'public', 'level' => 'national'],
            ['name' => 'INIA', 'nature' => 'public', 'level' => 'national'],
            ['name' => 'Sierra y Selva Exportadora', 'nature' => 'public', 'level' => 'national'],
            ['name' => 'Gobierno Regional', 'nature' => 'public', 'level' => 'regional'],
            ['name' => 'Municipalidad Provincial', 'nature' => 'public', 'level' => 'provincial'],
            ['name' => 'Municipalidad Distrital', 'nature' => 'public', 'level' => 'district'],
            ['name' => 'ONG de desarrollo rural', 'nature' => 'private', 'level' => 'national'],
            ['name' => 'Universidad pública', 'nature' => 'public', 'level' => 'regional'],
            ['name' => 'Universidad privada', 'nature' => 'private', 'level' => 'regional'],
        ];

        foreach ($items as $item) {
            InstitutionType::create($item);
        }
    }

    private function seedPositions(): void
    {
        $items = [
            ['name' => 'Jefe de planta', 'description' => 'Responsable general de la operación de la planta'],
            ['name' => 'Maestro quesero', 'description' => 'Encargado de la elaboración de quesos y derivados lácteos'],
            ['name' => 'Técnico de laboratorio', 'description' => 'Encargado del control de calidad y análisis de muestras'],
            ['name' => 'Operario de producción', 'description' => 'Ejecuta los procesos de transformación de la leche'],
            ['name' => 'Encargado de acopio', 'description' => 'Gestiona la recepción y almacenamiento de leche fresca'],
            ['name' => 'Encargado de ventas', 'description' => 'Gestiona la comercialización de productos lácteos'],
            ['name' => 'Almacenero', 'description' => 'Control de inventarios, insumos y productos terminados'],
            ['name' => 'Asistente administrativo', 'description' => 'Apoyo en gestión administrativa y documentaria'],
        ];

        foreach ($items as $item) {
            Position::create($item);
        }
    }

    private function seedProductTypes(): void
    {
        $items = [
            ['name' => 'Queso fresco', 'description' => 'Queso sin maduración elaborado con leche pasteurizada'],
            ['name' => 'Queso andino', 'description' => 'Queso semi-madurado típico de la sierra peruana'],
            ['name' => 'Queso paria', 'description' => 'Queso semi-duro tradicional de la región altiplánica'],
            ['name' => 'Queso mozzarella', 'description' => 'Queso de pasta hilada para uso gastronómico'],
            ['name' => 'Yogurt natural', 'description' => 'Leche fermentada sin saborizantes artificiales'],
            ['name' => 'Yogurt frutado', 'description' => 'Yogurt con pulpa o sabor de frutas naturales'],
            ['name' => 'Mantequilla', 'description' => 'Producto obtenido del batido de la crema de leche'],
            ['name' => 'Manjar blanco', 'description' => 'Dulce de leche concentrado y azucarado'],
            ['name' => 'Leche pasteurizada', 'description' => 'Leche sometida a tratamiento térmico de pasteurización'],
            ['name' => 'Crema de leche', 'description' => 'Nata obtenida del descremado de la leche'],
        ];

        foreach ($items as $item) {
            ProductType::create($item);
        }
    }

    private function seedSupplies(): void
    {
        $items = [
            ['name' => 'Leche fresca', 'description' => 'Leche cruda sin procesar'],
            ['name' => 'Cuajo líquido', 'description' => 'Enzima para la coagulación de la leche'],
            ['name' => 'Sal industrial', 'description' => 'Cloruro de sodio para salado de quesos'],
            ['name' => 'Cultivo láctico', 'description' => 'Bacterias para fermentación de productos lácteos'],
            ['name' => 'Cloruro de calcio', 'description' => 'Aditivo para mejorar la coagulación'],
            ['name' => 'Azúcar blanca', 'description' => 'Endulzante para yogurt y manjar blanco'],
            ['name' => 'Sorbato de potasio', 'description' => 'Conservante para productos lácteos'],
            ['name' => 'Colorante achiote', 'description' => 'Colorante natural para quesos'],
            ['name' => 'Pulpa de fruta', 'description' => 'Fruta procesada para yogurt frutado'],
            ['name' => 'Leche en polvo', 'description' => 'Leche deshidratada para enriquecimiento'],
        ];

        foreach ($items as $item) {
            Supply::create($item);
        }
    }
}
