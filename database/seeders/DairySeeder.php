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
use App\Models\Dairy\Product;
use App\Models\Dairy\ProductType;
use App\Models\Dairy\Supply;
use App\Models\Core\UnitMeasure;

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
        $this->seedProducts();
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

    private function seedProducts(): void
    {
        $quesoFresco = ProductType::where('name', 'Queso fresco')->first()?->id;
        $quesoAndino = ProductType::where('name', 'Queso andino')->first()?->id;
        $quesoParia = ProductType::where('name', 'Queso paria')->first()?->id;
        $quesoMozzarella = ProductType::where('name', 'Queso mozzarella')->first()?->id;
        $yogurtNatural = ProductType::where('name', 'Yogurt natural')->first()?->id;
        $yogurtFrutado = ProductType::where('name', 'Yogurt frutado')->first()?->id;
        $mantequilla = ProductType::where('name', 'Mantequilla')->first()?->id;
        $manjarBlanco = ProductType::where('name', 'Manjar blanco')->first()?->id;
        $lechePasteurizada = ProductType::where('name', 'Leche pasteurizada')->first()?->id;
        $cremaDeLeche = ProductType::where('name', 'Crema de leche')->first()?->id;

        $items = [
            ['name' => 'Queso Fresco Artesanal', 'description' => 'Queso fresco elaborado con leche de vaca pasteurizada', 'product_type_id' => $quesoFresco],
            ['name' => 'Queso Andino Curado', 'description' => 'Queso semi-madurado con sabor intenso de la sierra', 'product_type_id' => $quesoAndino],
            ['name' => 'Queso Paria Tradicional', 'description' => 'Queso semi-duro típico del altiplano peruano', 'product_type_id' => $quesoParia],
            ['name' => 'Mozzarella Fresca', 'description' => 'Queso de pasta hilada ideal para pizzas y ensaladas', 'product_type_id' => $quesoMozzarella],
            ['name' => 'Yogurt Natural Sin Azúcar', 'description' => 'Yogurt batido natural sin azúcar añadida', 'product_type_id' => $yogurtNatural],
            ['name' => 'Yogurt de Fresa', 'description' => 'Yogurt frutado con pulpa natural de fresa', 'product_type_id' => $yogurtFrutado],
            ['name' => 'Yogurt de Aguaymanto', 'description' => 'Yogurt frutado con pulpa de aguaymanto andino', 'product_type_id' => $yogurtFrutado],
            ['name' => 'Mantequilla con Sal', 'description' => 'Mantequilla elaborada con crema de leche fresca', 'product_type_id' => $mantequilla],
            ['name' => 'Mantequilla Sin Sal', 'description' => 'Mantequilla sin sal para uso en repostería', 'product_type_id' => $mantequilla],
            ['name' => 'Manjar Blanco Clásico', 'description' => 'Dulce de leche artesanal concentrado', 'product_type_id' => $manjarBlanco],
            ['name' => 'Leche Pasteurizada Entera', 'description' => 'Leche entera sometida a pasteurización', 'product_type_id' => $lechePasteurizada],
            ['name' => 'Crema de Leche Fresca', 'description' => 'Nata fresca para uso culinario y repostería', 'product_type_id' => $cremaDeLeche],
        ];

        foreach ($items as $item) {
            Product::create($item);
        }
    }

    private function seedSupplies(): void
    {
        $litro = UnitMeasure::where('abbreviation', 'L')->first()?->id;
        $ml = UnitMeasure::where('abbreviation', 'mL')->first()?->id;
        $kg = UnitMeasure::where('abbreviation', 'kg')->first()?->id;
        $g = UnitMeasure::where('abbreviation', 'g')->first()?->id;

        $items = [
            ['name' => 'Leche fresca', 'unit_measure_id' => $litro, 'description' => 'Leche cruda sin procesar'],
            ['name' => 'Cuajo líquido', 'unit_measure_id' => $ml, 'description' => 'Enzima para la coagulación de la leche'],
            ['name' => 'Sal industrial', 'unit_measure_id' => $kg, 'description' => 'Cloruro de sodio para salado de quesos'],
            ['name' => 'Cultivo láctico', 'unit_measure_id' => $g, 'description' => 'Bacterias para fermentación de productos lácteos'],
            ['name' => 'Cloruro de calcio', 'unit_measure_id' => $g, 'description' => 'Aditivo para mejorar la coagulación'],
            ['name' => 'Azúcar blanca', 'unit_measure_id' => $kg, 'description' => 'Endulzante para yogurt y manjar blanco'],
            ['name' => 'Sorbato de potasio', 'unit_measure_id' => $g, 'description' => 'Conservante para productos lácteos'],
            ['name' => 'Colorante achiote', 'unit_measure_id' => $ml, 'description' => 'Colorante natural para quesos'],
            ['name' => 'Pulpa de fruta', 'unit_measure_id' => $kg, 'description' => 'Fruta procesada para yogurt frutado'],
            ['name' => 'Leche en polvo', 'unit_measure_id' => $kg, 'description' => 'Leche deshidratada para enriquecimiento'],
        ];

        foreach ($items as $item) {
            Supply::create($item);
        }
    }
}
