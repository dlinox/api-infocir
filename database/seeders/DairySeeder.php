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
        $items = [
            ['name' => 'Jefe de planta',            'description' => 'Responsable general de la operación de la planta'],
            ['name' => 'Maestro quesero',           'description' => 'Encargado de la elaboración de quesos y derivados lácteos'],
            ['name' => 'Técnico de laboratorio',    'description' => 'Encargado del control de calidad y análisis de muestras'],
            ['name' => 'Operario de producción',    'description' => 'Ejecuta los procesos de transformación de la leche'],
            ['name' => 'Encargado de acopio',       'description' => 'Gestiona la recepción y almacenamiento de leche fresca'],
            ['name' => 'Encargado de ventas',       'description' => 'Gestiona la comercialización de productos lácteos'],
            ['name' => 'Almacenero',                'description' => 'Control de inventarios, insumos y productos terminados'],
            ['name' => 'Asistente administrativo',  'description' => 'Apoyo en gestión administrativa y documentaria'],
        ];

        foreach ($items as $item) {
            Position::create($item);
        }
    }

    private function seedProductTypes(): void
    {
        $items = [
            ['name' => 'Queso fresco',       'description' => 'Queso sin maduración elaborado con leche pasteurizada'],
            ['name' => 'Queso andino',        'description' => 'Queso semi-madurado típico de la sierra peruana'],
            ['name' => 'Queso paria',         'description' => 'Queso semi-duro tradicional de la región altiplánica'],
            ['name' => 'Queso mozzarella',    'description' => 'Queso de pasta hilada para uso gastronómico'],
            ['name' => 'Yogurt natural',      'description' => 'Leche fermentada sin saborizantes artificiales'],
            ['name' => 'Yogurt frutado',      'description' => 'Leche fermentada con pulpa o saborizante de fruta'],
            ['name' => 'Mantequilla',         'description' => 'Grasa láctea obtenida del batido de crema de leche'],
            ['name' => 'Manjar blanco',       'description' => 'Dulce de leche concentrado elaborado artesanalmente'],
            ['name' => 'Leche pasteurizada',  'description' => 'Leche entera sometida a tratamiento térmico'],
            ['name' => 'Crema de leche',      'description' => 'Nata fresca con alto contenido graso'],
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
        $litro = UnitMeasure::where('abbreviation', 'L')->first()?->id;
        $kg    = UnitMeasure::where('abbreviation', 'kg')->first()?->id;
        $g     = UnitMeasure::where('abbreviation', 'g')->first()?->id;
        $ml    = UnitMeasure::where('abbreviation', 'mL')->first()?->id;

        $materiaP     = InvestmentCategory::where('name', 'Materia Prima / Mercadería')->first()?->id;
        $manoObraD    = InvestmentCategory::where('name', 'Mano de Obra Directa')->first()?->id;
        $manoObraI    = InvestmentCategory::where('name', 'Mano de Obra Indirecta')->first()?->id;
        $costosDirect = InvestmentCategory::where('name', 'Costos Directos')->first()?->id;
        $costosIndir  = InvestmentCategory::where('name', 'Costos Indirectos')->first()?->id;
        $gastosAdmin  = InvestmentCategory::where('name', 'Gastos Administrativos')->first()?->id;
        $gastosVentas = InvestmentCategory::where('name', 'Gastos de Ventas')->first()?->id;
        $impuestos    = InvestmentCategory::where('name', 'Impuestos')->first()?->id;
        $depreciacion = InvestmentCategory::where('name', 'Depreciación')->first()?->id;

        $items = [
            // Materia Prima / Mercadería
            ['investment_category_id' => $materiaP,     'unit_measure_id' => $litro, 'name' => 'Leche fresca',                         'description' => 'Leche cruda de acopio en establos'],
            ['investment_category_id' => $materiaP,     'unit_measure_id' => $kg,    'name' => 'Cuajo microbiano',                     'description' => 'Enzima para la coagulación de la leche'],
            ['investment_category_id' => $materiaP,     'unit_measure_id' => $kg,    'name' => 'Sal industrial',                       'description' => 'Cloruro de sodio para salado de quesos'],
            ['investment_category_id' => $materiaP,     'unit_measure_id' => $kg,    'name' => 'Cloruro de calcio',                    'description' => 'Aditivo para mejorar la coagulación de la leche'],
            ['investment_category_id' => $materiaP,     'unit_measure_id' => $g,     'name' => 'Cultivos lácticos (yogurt y queso)',   'description' => 'Bacterias para fermentación de yogurt y queso andino'],
            ['investment_category_id' => $materiaP,     'unit_measure_id' => null,   'name' => 'Envases y empaques',                   'description' => 'Bolsas, potes, tapas y etiquetas para productos terminados'],
            ['investment_category_id' => $materiaP,     'unit_measure_id' => $litro, 'name' => 'Materiales de limpieza y desinfección CIP', 'description' => 'Detergentes y desinfectantes para limpieza de equipos'],
            ['investment_category_id' => $materiaP,     'unit_measure_id' => $kg,    'name' => 'Gas industrial',                       'description' => 'Gas en válvulas para procesos de calor'],
            ['investment_category_id' => $materiaP,     'unit_measure_id' => null,   'name' => 'Insumos de laboratorio',               'description' => 'Reactivos, placas Petri y materiales de análisis'],
            ['investment_category_id' => $materiaP,     'unit_measure_id' => null,   'name' => 'Materiales de oficina y formularios BPM', 'description' => 'Útiles de oficina y formatos SENASA/HACCP'],

            // Mano de Obra Directa
            ['investment_category_id' => $manoObraD,    'unit_measure_id' => null,   'name' => 'Quesero maestro / Jefe de producción', 'description' => 'Responsable del proceso de elaboración de quesos y derivados'],
            ['investment_category_id' => $manoObraD,    'unit_measure_id' => null,   'name' => 'Operario de planta (proceso general)', 'description' => 'Ejecuta los procesos de transformación de la leche'],
            ['investment_category_id' => $manoObraD,    'unit_measure_id' => null,   'name' => 'Operario de planta (empaque/distribución)', 'description' => 'Encargado del empaque y despacho de productos'],
            ['investment_category_id' => $manoObraD,    'unit_measure_id' => null,   'name' => 'Repartidor / conductor',               'description' => 'Distribución y entrega de productos con viáticos'],

            // Mano de Obra Indirecta
            ['investment_category_id' => $manoObraI,    'unit_measure_id' => null,   'name' => 'Jefe de planta / Administrador',       'description' => 'Responsable general de la operación administrativa'],
            ['investment_category_id' => $manoObraI,    'unit_measure_id' => null,   'name' => 'Asistente administrativo y de ventas', 'description' => 'Apoyo en gestión administrativa, documentaria y comercial'],
            ['investment_category_id' => $manoObraI,    'unit_measure_id' => null,   'name' => 'Servicio de contabilidad externo',     'description' => 'Honorarios de contador externo'],
            ['investment_category_id' => $manoObraI,    'unit_measure_id' => null,   'name' => 'Vendedor / promotor',                  'description' => 'Gestión comercial con comisión base fija'],
            ['investment_category_id' => $manoObraI,    'unit_measure_id' => null,   'name' => 'Vigilancia / seguridad',               'description' => 'Servicio externo de vigilancia y seguridad'],

            // Costos Directos
            ['investment_category_id' => $costosDirect, 'unit_measure_id' => null,   'name' => 'Flete recojo de leche',                'description' => 'Acopio en establos, costo por litro recogido'],
            ['investment_category_id' => $costosDirect, 'unit_measure_id' => null,   'name' => 'Análisis de calidad de leche cruda',   'description' => 'Control en punto de compra (acidez, densidad, grasa)'],

            // Costos Indirectos
            ['investment_category_id' => $costosIndir,  'unit_measure_id' => null,   'name' => 'Energía eléctrica trifásica',          'description' => 'Consumo eléctrico de la planta procesadora'],
            ['investment_category_id' => $costosIndir,  'unit_measure_id' => null,   'name' => 'Alquiler de planta procesadora',       'description' => 'Arrendamiento del local industrial'],
            ['investment_category_id' => $costosIndir,  'unit_measure_id' => null,   'name' => 'Telefonía + internet + WhatsApp Business', 'description' => 'Servicios de comunicación de la empresa'],
            ['investment_category_id' => $costosIndir,  'unit_measure_id' => null,   'name' => 'Seguro multirriesgo planta + mercadería', 'description' => 'Prima de seguro anual contra siniestros'],

            // Gastos Administrativos
            ['investment_category_id' => $gastosAdmin,  'unit_measure_id' => null,   'name' => 'Útiles de oficina y tóneres',          'description' => 'Útiles de escritorio, archivadores y consumibles de impresión'],
            ['investment_category_id' => $gastosAdmin,  'unit_measure_id' => null,   'name' => 'Honorarios contador',                  'description' => 'Formalización contable y declaraciones tributarias'],
            ['investment_category_id' => $gastosAdmin,  'unit_measure_id' => null,   'name' => 'Gastos bancarios',                     'description' => 'Apertura de cuenta, chequera y Yape empresarial'],
            ['investment_category_id' => $gastosAdmin,  'unit_measure_id' => null,   'name' => 'Mantenimiento preventivo de equipos',  'description' => 'Primera revisión programada de maquinaria'],

            // Gastos de Ventas
            ['investment_category_id' => $gastosVentas, 'unit_measure_id' => null,   'name' => 'Publicidad mensual (redes + ferias)',  'description' => 'Redes sociales, impulsadoras y participación en ferias'],
            ['investment_category_id' => $gastosVentas, 'unit_measure_id' => null,   'name' => 'Comisiones de ventas',                 'description' => 'Apertura de cuentas con distribuidores'],
            ['investment_category_id' => $gastosVentas, 'unit_measure_id' => null,   'name' => 'Material POP',                         'description' => 'Afiches, banners, viniles y tarjetas de presentación'],
            ['investment_category_id' => $gastosVentas, 'unit_measure_id' => null,   'name' => 'Transporte y distribución',            'description' => 'Gasolina, rutas y peajes para distribución de productos'],

            // Impuestos
            ['investment_category_id' => $impuestos,    'unit_measure_id' => null,   'name' => 'Impuesto de alcabala y arbitrios',     'description' => 'Impuestos municipales primer año'],
            ['investment_category_id' => $impuestos,    'unit_measure_id' => null,   'name' => 'IGV (crédito fiscal inicial)',          'description' => 'Impuesto general a las ventas del primer ciclo'],
            ['investment_category_id' => $impuestos,    'unit_measure_id' => null,   'name' => 'Impuesto a la renta',                  'description' => 'Provisión primera declaración mensual'],

            // Depreciación
            ['investment_category_id' => $depreciacion, 'unit_measure_id' => null,   'name' => 'Depreciación de maquinaria y equipos', 'description' => 'Depreciación año 1 referencial a 10 años'],
            ['investment_category_id' => $depreciacion, 'unit_measure_id' => null,   'name' => 'Depreciación de muebles y equipamiento', 'description' => 'Depreciación año 1 referencial a 5 años'],
        ];

        foreach ($items as $item) {
            WorkingCapitalCatalog::create($item);
        }
    }
}