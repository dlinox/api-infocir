<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Core\InstructionDegree;
use App\Models\Core\Profession;
use App\Models\Core\UnitMeasure;
use App\Models\Dairy\CompanyType;
use App\Models\Dairy\TrainingLevel;
use App\Models\Dairy\InstitutionType;
use App\Models\Dairy\Position;
use App\Models\Dairy\Product;
use App\Models\Dairy\ProductType;
use App\Models\Dairy\Supply;
use App\Models\Dairy\InvestmentCategory;
use App\Models\Behavior\Role;
use App\Models\Dairy\AssetCatalog;
use App\Models\Dairy\PreOperativeCatalog;
use App\Models\Dairy\WorkingCapitalCatalog;

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
        $this->seedInvestmentCategories();
        $this->seedFixedAssetCatalog();
        $this->seedPreOperativeCatalog();
        $this->seedWorkingCapitalCatalog();
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
            ['name' => 'SENASA',                    'nature' => 'public',   'level' => 'national'],
            ['name' => 'DIGESA',                    'nature' => 'public',   'level' => 'national'],
            ['name' => 'INIA',                      'nature' => 'public',   'level' => 'national'],
            ['name' => 'Sierra y Selva Exportadora','nature' => 'public',   'level' => 'national'],
            ['name' => 'Gobierno Regional',         'nature' => 'public',   'level' => 'regional'],
            ['name' => 'Municipalidad Provincial',  'nature' => 'public',   'level' => 'provincial'],
            ['name' => 'Municipalidad Distrital',   'nature' => 'public',   'level' => 'district'],
            ['name' => 'ONG de desarrollo rural',   'nature' => 'private',  'level' => 'national'],
            ['name' => 'Universidad pública',       'nature' => 'public',   'level' => 'regional'],
            ['name' => 'Universidad privada',       'nature' => 'private',  'level' => 'regional'],
        ];

        foreach ($items as $item) {
            InstitutionType::create($item);
        }
    }

    private function seedPositions(): void
    {
        // El cargo define el rol de acceso del trabajador (role_id):
        //  - "Jefe de planta"      → plant_manager (gestiona la planta en app-managers)
        //  - "Encargado de acopio" → plant_collector (registra acopio en el panel de acopiador)
        $plantManagerRoleId  = Role::where('name', 'plant_manager')->value('id');
        $plantCollectorRoleId = Role::where('name', 'plant_collector')->value('id');

        $items = [
            ['name' => 'Jefe de planta',            'role_id' => $plantManagerRoleId,   'description' => 'Responsable general de la operación de la planta'],
            ['name' => 'Maestro quesero',           'role_id' => null,                   'description' => 'Encargado de la elaboración de quesos y derivados lácteos'],
            ['name' => 'Técnico de laboratorio',    'role_id' => null,                   'description' => 'Encargado del control de calidad y análisis de muestras'],
            ['name' => 'Operario de producción',    'role_id' => null,                   'description' => 'Ejecuta los procesos de transformación de la leche'],
            ['name' => 'Encargado de acopio',       'role_id' => $plantCollectorRoleId,  'description' => 'Gestiona la recepción y almacenamiento de leche fresca'],
            ['name' => 'Encargado de ventas',       'role_id' => null,                   'description' => 'Gestiona la comercialización de productos lácteos'],
            ['name' => 'Almacenero',                'role_id' => null,                   'description' => 'Control de inventarios, insumos y productos terminados'],
            ['name' => 'Asistente administrativo',  'role_id' => null,                   'description' => 'Apoyo en gestión administrativa y documentaria'],
        ];

        foreach ($items as $item) {
            Position::create($item);
        }
    }

    private function seedProductTypes(): void
    {
        $items = [
            ['name' => 'Queso fresco',       'icon' => '🧀', 'color' => 'orange', 'description' => 'Queso sin maduración elaborado con leche pasteurizada'],
            ['name' => 'Queso andino',        'icon' => '🧀', 'color' => 'orange', 'description' => 'Queso semi-madurado típico de la sierra peruana'],
            ['name' => 'Queso paria',         'icon' => '🧀', 'color' => 'orange', 'description' => 'Queso semi-duro tradicional de la región altiplánica'],
            ['name' => 'Queso mozzarella',    'icon' => '🧀', 'color' => 'orange', 'description' => 'Queso de pasta hilada para uso gastronómico'],
            ['name' => 'Yogurt natural',      'icon' => '🫙', 'color' => 'cyan',   'description' => 'Leche fermentada sin saborizantes artificiales'],
            ['name' => 'Yogurt frutado',      'icon' => '🫙', 'color' => 'cyan',   'description' => 'Leche fermentada con pulpa o saborizante de fruta'],
            ['name' => 'Mantequilla',         'icon' => '🧈', 'color' => 'lime',   'description' => 'Grasa láctea obtenida del batido de crema de leche'],
            ['name' => 'Manjar blanco',       'icon' => '🍯', 'color' => 'yellow', 'description' => 'Dulce de leche concentrado elaborado artesanalmente'],
            ['name' => 'Leche pasteurizada',  'icon' => '🥛', 'color' => 'teal',   'description' => 'Leche entera sometida a tratamiento térmico'],
            ['name' => 'Crema de leche',      'icon' => '🧈', 'color' => 'lime',   'description' => 'Nata fresca con alto contenido graso'],
        ];

        foreach ($items as $item) {
            ProductType::create($item);
        }
    }

    private function seedProducts(): void
    {
        $quesoFresco      = ProductType::where('name', 'Queso fresco')->first()?->id;
        $quesoAndino      = ProductType::where('name', 'Queso andino')->first()?->id;
        $quesoParia       = ProductType::where('name', 'Queso paria')->first()?->id;
        $quesoMozzarella  = ProductType::where('name', 'Queso mozzarella')->first()?->id;
        $yogurtNatural    = ProductType::where('name', 'Yogurt natural')->first()?->id;
        $yogurtFrutado    = ProductType::where('name', 'Yogurt frutado')->first()?->id;
        $mantequilla      = ProductType::where('name', 'Mantequilla')->first()?->id;
        $manjarBlanco     = ProductType::where('name', 'Manjar blanco')->first()?->id;
        $lechePasteurizada = ProductType::where('name', 'Leche pasteurizada')->first()?->id;
        $cremaDeLeche     = ProductType::where('name', 'Crema de leche')->first()?->id;

        $items = [
            ['name' => 'Queso Fresco Artesanal',    'description' => 'Queso fresco elaborado con leche de vaca pasteurizada',   'product_type_id' => $quesoFresco],
            ['name' => 'Queso Andino Curado',        'description' => 'Queso semi-madurado con sabor intenso de la sierra',      'product_type_id' => $quesoAndino],
            ['name' => 'Queso Paria Tradicional',    'description' => 'Queso semi-duro típico del altiplano peruano',            'product_type_id' => $quesoParia],
            ['name' => 'Mozzarella Fresca',          'description' => 'Queso de pasta hilada ideal para pizzas y ensaladas',    'product_type_id' => $quesoMozzarella],
            ['name' => 'Yogurt Natural Sin Azúcar',  'description' => 'Yogurt batido natural sin azúcar añadida',               'product_type_id' => $yogurtNatural],
            ['name' => 'Yogurt de Fresa',            'description' => 'Yogurt frutado con pulpa natural de fresa',              'product_type_id' => $yogurtFrutado],
            ['name' => 'Yogurt de Aguaymanto',       'description' => 'Yogurt frutado con pulpa de aguaymanto andino',          'product_type_id' => $yogurtFrutado],
            ['name' => 'Mantequilla con Sal',        'description' => 'Mantequilla elaborada con crema de leche fresca',        'product_type_id' => $mantequilla],
            ['name' => 'Mantequilla Sin Sal',        'description' => 'Mantequilla sin sal para uso en repostería',             'product_type_id' => $mantequilla],
            ['name' => 'Manjar Blanco Clásico',      'description' => 'Dulce de leche artesanal concentrado',                   'product_type_id' => $manjarBlanco],
            ['name' => 'Leche Pasteurizada Entera',  'description' => 'Leche entera sometida a pasteurización',                 'product_type_id' => $lechePasteurizada],
            ['name' => 'Crema de Leche Fresca',      'description' => 'Nata fresca para uso culinario y repostería',            'product_type_id' => $cremaDeLeche],
        ];

        foreach ($items as $item) {
            Product::create($item);
        }
    }

    private function seedSupplies(): void
    {
        $litro = UnitMeasure::where('abbreviation', 'L')->first()?->id;
        $ml    = UnitMeasure::where('abbreviation', 'mL')->first()?->id;
        $kg    = UnitMeasure::where('abbreviation', 'kg')->first()?->id;
        $g     = UnitMeasure::where('abbreviation', 'g')->first()?->id;

        $items = [
            ['name' => 'Leche fresca',        'unit_measure_id' => $litro, 'description' => 'Leche cruda sin procesar'],
            ['name' => 'Cuajo líquido',        'unit_measure_id' => $ml,   'description' => 'Enzima para la coagulación de la leche'],
            ['name' => 'Sal industrial',       'unit_measure_id' => $kg,   'description' => 'Cloruro de sodio para salado de quesos'],
            ['name' => 'Cultivo láctico',      'unit_measure_id' => $g,    'description' => 'Bacterias para fermentación de productos lácteos'],
            ['name' => 'Cloruro de calcio',    'unit_measure_id' => $g,    'description' => 'Aditivo para mejorar la coagulación'],
            ['name' => 'Azúcar blanca',        'unit_measure_id' => $kg,   'description' => 'Endulzante para yogurt y manjar blanco'],
            ['name' => 'Sorbato de potasio',   'unit_measure_id' => $g,    'description' => 'Conservante para productos lácteos'],
            ['name' => 'Colorante achiote',    'unit_measure_id' => $ml,   'description' => 'Colorante natural para quesos'],
            ['name' => 'Pulpa de fruta',       'unit_measure_id' => $kg,   'description' => 'Fruta procesada para yogurt frutado'],
            ['name' => 'Leche en polvo',       'unit_measure_id' => $kg,   'description' => 'Leche deshidratada para enriquecimiento'],
        ];

        foreach ($items as $item) {
            Supply::create($item);
        }
    }

    private function seedInvestmentCategories(): void
    {
        $items = [
            // Activo Fijo
            ['name' => 'Terrenos e Infraestructura',  'group' => 'fixed_asset',     'sort_order' => 10],
            ['name' => 'Maquinaria y Equipo',          'group' => 'fixed_asset',     'sort_order' => 20],
            ['name' => 'Herramientas y Equipamiento',  'group' => 'fixed_asset',     'sort_order' => 30],
            ['name' => 'Muebles y Enseres',            'group' => 'fixed_asset',     'sort_order' => 40],
            // Gastos Pre-Operativos
            ['name' => 'Gastos Pre-Operativos',        'group' => 'pre_operative',   'sort_order' => 50],
            // Capital de Trabajo
            ['name' => 'Materia Prima / Mercadería',   'group' => 'working_capital', 'sort_order' => 60],
            ['name' => 'Mano de Obra Directa',         'group' => 'working_capital', 'sort_order' => 70],
            ['name' => 'Mano de Obra Indirecta',       'group' => 'working_capital', 'sort_order' => 80],
            ['name' => 'Costos Directos',              'group' => 'working_capital', 'sort_order' => 90],
            ['name' => 'Costos Indirectos',            'group' => 'working_capital', 'sort_order' => 100],
            ['name' => 'Gastos Administrativos',       'group' => 'working_capital', 'sort_order' => 110],
            ['name' => 'Gastos de Ventas',             'group' => 'working_capital', 'sort_order' => 120],
            ['name' => 'Impuestos',                    'group' => 'working_capital', 'sort_order' => 130],
            ['name' => 'Depreciación',                 'group' => 'working_capital', 'sort_order' => 140],
        ];

        foreach ($items as $item) {
            InvestmentCategory::create($item);
        }
    }

    private function seedFixedAssetCatalog(): void
    {
        $terrenos     = InvestmentCategory::where('name', 'Terrenos e Infraestructura')->first()?->id;
        $maquinaria   = InvestmentCategory::where('name', 'Maquinaria y Equipo')->first()?->id;
        $herramientas = InvestmentCategory::where('name', 'Herramientas y Equipamiento')->first()?->id;
        $muebles      = InvestmentCategory::where('name', 'Muebles y Enseres')->first()?->id;

        $items = [
            // Terrenos e Infraestructura
            ['investment_category_id' => $terrenos,     'name' => 'Adecuación de local industrial',         'useful_life_years' => 20, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $terrenos,     'name' => 'Instalaciones eléctricas trifásicas',    'useful_life_years' => 15, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $terrenos,     'name' => 'Instalaciones de agua y vapor',          'useful_life_years' => 15, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $terrenos,     'name' => 'Acondicionamiento frío (cámara + cuarto proceso)', 'useful_life_years' => 10, 'depreciation_method' => 'straight_line'],

            // Maquinaria y Equipo
            ['investment_category_id' => $maquinaria,   'name' => 'Pasteurizador tubular HTST',             'useful_life_years' => 10, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $maquinaria,   'name' => 'Tina quesera de doble fondo',            'useful_life_years' => 10, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $maquinaria,   'name' => 'Descremadora centrífuga',                'useful_life_years' => 10, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $maquinaria,   'name' => 'Moldes de queso inox + prensas neumáticas', 'useful_life_years' => 5, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $maquinaria,   'name' => 'Mantequilladora industrial',             'useful_life_years' => 10, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $maquinaria,   'name' => 'Incubadora / fermentadora para yogurt',  'useful_life_years' => 10, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $maquinaria,   'name' => 'Envasadora semiautomática al vacío',     'useful_life_years' => 8,  'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $maquinaria,   'name' => 'Balanza de precisión electrónica',       'useful_life_years' => 5,  'depreciation_method' => 'straight_line'],

            // Herramientas y Equipamiento
            ['investment_category_id' => $herramientas, 'name' => 'Cámara de frío modular 4°C',             'useful_life_years' => 10, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $herramientas, 'name' => 'Cuarto de maduración 10-12°C',           'useful_life_years' => 10, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $herramientas, 'name' => 'Etiquetadora semiautomática + software HACCP', 'useful_life_years' => 8, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $herramientas, 'name' => 'Utensilios inox',                        'useful_life_years' => 5,  'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $herramientas, 'name' => 'Tanque de almacenamiento de leche',      'useful_life_years' => 10, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $herramientas, 'name' => 'Equipo de limpieza CIP',                 'useful_life_years' => 8,  'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $herramientas, 'name' => 'Termómetros, pH-metros y lactodensímetros', 'useful_life_years' => 5, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $herramientas, 'name' => 'Indumentaria BPM',                       'useful_life_years' => 2,  'depreciation_method' => 'straight_line'],

            // Muebles y Enseres
            ['investment_category_id' => $muebles,      'name' => 'Escritorio administrativo + silla ergonómica', 'useful_life_years' => 10, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $muebles,      'name' => 'Sillas área de reuniones / capacitación', 'useful_life_years' => 10, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $muebles,      'name' => 'Estantería metálica para almacén',       'useful_life_years' => 10, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $muebles,      'name' => 'Computadora + impresora + UPS',          'useful_life_years' => 4,  'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $muebles,      'name' => 'Botiquín de primeros auxilios + extintor PQS', 'useful_life_years' => 5, 'depreciation_method' => 'straight_line'],
            ['investment_category_id' => $muebles,      'name' => 'Uniformes corporativos con logo',        'useful_life_years' => 2,  'depreciation_method' => 'straight_line'],
        ];

        foreach ($items as $item) {
            AssetCatalog::create($item);
        }
    }

    private function seedPreOperativeCatalog(): void
    {
        $preOp = InvestmentCategory::where('name', 'Gastos Pre-Operativos')->first()?->id;

        $items = [
            [
                'investment_category_id' => $preOp,
                'name'            => 'Licencias municipales',
                'issuing_entity'  => 'Municipalidad',
                'recurrence_type' => 'periodic',
                'validity_years'  => 1,
                'is_public'       => true,
            ],
            [
                'investment_category_id' => $preOp,
                'name'            => 'Constitución de empresa (SUNAT + SUNARP)',
                'issuing_entity'  => 'SUNAT / SUNARP',
                'recurrence_type' => 'one_time',
                'validity_years'  => null,
                'is_public'       => false,
            ],
            [
                'investment_category_id' => $preOp,
                'name'            => 'Registro sanitario DIGESA (RAS)',
                'issuing_entity'  => 'DIGESA',
                'recurrence_type' => 'periodic',
                'validity_years'  => 5,
                'is_public'       => true,
            ],
            [
                'investment_category_id' => $preOp,
                'name'            => 'Certificación SENASA',
                'issuing_entity'  => 'SENASA',
                'recurrence_type' => 'periodic',
                'validity_years'  => 1,
                'is_public'       => true,
            ],
            [
                'investment_category_id' => $preOp,
                'name'            => 'Certificación HACCP',
                'issuing_entity'  => null,
                'recurrence_type' => 'periodic',
                'validity_years'  => 2,
                'is_public'       => false,
            ],
            [
                'investment_category_id' => $preOp,
                'name'            => 'Publicidad de lanzamiento',
                'issuing_entity'  => null,
                'recurrence_type' => 'one_time',
                'validity_years'  => null,
                'is_public'       => false,
            ],
            [
                'investment_category_id' => $preOp,
                'name'            => 'Capacitación BPM del personal',
                'issuing_entity'  => null,
                'recurrence_type' => 'one_time',
                'validity_years'  => null,
                'is_public'       => false,
            ],
        ];

        foreach ($items as $item) {
            PreOperativeCatalog::create($item);
        }
    }

    private function seedWorkingCapitalCatalog(): void
    {
        $costosDir    = InvestmentCategory::where('name', 'Costos Directos')->first()?->id;
        $costosIndir  = InvestmentCategory::where('name', 'Costos Indirectos')->first()?->id;
        $gastosAdmin  = InvestmentCategory::where('name', 'Gastos Administrativos')->first()?->id;
        $gastosVentas = InvestmentCategory::where('name', 'Gastos de Ventas')->first()?->id;

        $litro = UnitMeasure::where('abbreviation', 'L')->first()?->id;

        $items = [
            // Costos Directos: gastos de recorrido del acopiador
            ['investment_category_id' => $costosDir,    'unit_measure_id' => $litro, 'name' => 'Combustible',                  'description' => 'Gasolina o diésel para el vehículo de recorrido', 'is_route_expense' => true],
            ['investment_category_id' => $costosDir,    'unit_measure_id' => null,   'name' => 'Peajes',                       'description' => 'Pago de peajes durante el recorrido',             'is_route_expense' => true],
            ['investment_category_id' => $costosDir,    'unit_measure_id' => null,   'name' => 'Alimentación',                 'description' => 'Gastos de alimentación del acopiador en ruta',     'is_route_expense' => true],
            ['investment_category_id' => $costosDir,    'unit_measure_id' => null,   'name' => 'Mantenimiento de vehículo',    'description' => 'Reparaciones o mantenimiento durante el recorrido', 'is_route_expense' => true],
            ['investment_category_id' => $costosDir,    'unit_measure_id' => null,   'name' => 'Lavado de vehículo',           'description' => 'Limpieza del vehículo de recolección',             'is_route_expense' => true],
            ['investment_category_id' => $costosDir,    'unit_measure_id' => null,   'name' => 'Hielo',                        'description' => 'Hielo para conservación de la leche en ruta',      'is_route_expense' => true],
            ['investment_category_id' => $costosDir,    'unit_measure_id' => null,   'name' => 'Ayudante / estiba',            'description' => 'Pago a ayudante para carga y descarga',           'is_route_expense' => true],
            ['investment_category_id' => $costosDir,    'unit_measure_id' => null,   'name' => 'Otros gastos de ruta',         'description' => 'Gastos varios no clasificados del recorrido',      'is_route_expense' => true],
        ];

        $items = array_merge($items, [
            // Costos Indirectos: servicios comunes
            ['investment_category_id' => $costosIndir,  'unit_measure_id' => null,   'name' => 'Energía eléctrica trifásica',          'description' => 'Consumo eléctrico de la planta procesadora'],
            ['investment_category_id' => $costosIndir,  'unit_measure_id' => null,   'name' => 'Servicio de agua',                     'description' => 'Consumo de agua potable y saneamiento'],
            ['investment_category_id' => $costosIndir,  'unit_measure_id' => null,   'name' => 'Combustible de planta',                'description' => 'Gasolina, GLP o diésel para operación y transporte interno'],
            ['investment_category_id' => $costosIndir,  'unit_measure_id' => null,   'name' => 'Gas para producción',                  'description' => 'Consumo de gas para cocción y procesos térmicos'],
            ['investment_category_id' => $costosIndir,  'unit_measure_id' => null,   'name' => 'Limpieza y desinfección',              'description' => 'Insumos de higiene operativa y sanitaria'],
            ['investment_category_id' => $costosIndir,  'unit_measure_id' => null,   'name' => 'Alquiler de planta procesadora',       'description' => 'Arrendamiento del local industrial'],
            ['investment_category_id' => $costosIndir,  'unit_measure_id' => null,   'name' => 'Internet y telefonía',                 'description' => 'Servicios de comunicación de la empresa'],

            // Gastos Administrativos comunes
            ['investment_category_id' => $gastosAdmin,  'unit_measure_id' => null,   'name' => 'Gastos bancarios',                     'description' => 'Comisiones y mantenimiento de cuenta'],
            ['investment_category_id' => $gastosAdmin,  'unit_measure_id' => null,   'name' => 'Mantenimiento de equipos',             'description' => 'Mantenimiento correctivo/preventivo básico'],

            // Gastos de Ventas comunes
            ['investment_category_id' => $gastosVentas, 'unit_measure_id' => null,   'name' => 'Transporte y distribución',            'description' => 'Gasolina, rutas y peajes para distribución de productos'],
        ]);

        foreach ($items as $item) {
            WorkingCapitalCatalog::create($item);
        }
    }
}