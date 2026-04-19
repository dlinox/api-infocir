<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use App\Models\Behavior\BehaviorProfile;
use App\Models\Behavior\Role;
use App\Models\Core\Person;
use App\Models\Core\Profile as CoreProfile;
use App\Models\Dairy\Plant;
use App\Models\Dairy\Position;
use App\Models\Dairy\Worker;
use App\Models\Core\InstructionDegree;
use App\Models\Core\Profession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LearningDataSeeder extends Seeder
{
    use WithoutModelEvents;

    /** @var array<int, int> */
    private array $plantEntityIds = [];
    /** @var array<int, array{person_id:int, plant_id:int}> */
    private array $workers = [];
    /** @var array<int, int> */
    private array $instructors = [];
    /** @var array<string, int> */
    private array $areas = [];
    /** @var array<string, int> */
    private array $trainingTypes = [];
    /** @var array<string, int> */
    private array $templates = [];
    /** @var array<int, array<string, mixed>> */
    private array $coursesData = [];

    public function run(): void
    {
        $this->loadReferences();
        $this->seedCertificateTemplates();
        $this->seedInstructors();
        $this->seedWorkers();
        $this->seedCourses();
        $this->seedPrograms();
        $this->seedTrainings();
        $this->seedProgramDeliveries();
        $this->seedEnrollmentsAndProgress();
    }

    // =====================================================================
    // Referencias previas
    // =====================================================================

    private function loadReferences(): void
    {
        $this->areas = DB::table('learning_areas')->pluck('id', 'name')->toArray();
        $this->trainingTypes = DB::table('learning_training_types')->pluck('id', 'name')->toArray();

        $this->plantEntityIds = DB::table('core_entities')
            ->where('entityable_type', 'dairy_plants')
            ->pluck('id', 'entityable_id')
            ->toArray();
    }

    // =====================================================================
    // Plantillas de certificado
    // =====================================================================

    private function seedCertificateTemplates(): void
    {
        $templates = [
            ['name' => 'Certificado BPM — Industria Láctea',            'orientation' => 'landscape', 'validity_days' => 730],
            ['name' => 'Certificado Inocuidad y Calidad',                'orientation' => 'landscape', 'validity_days' => 730],
            ['name' => 'Certificado HACCP Lácteos',                       'orientation' => 'landscape', 'validity_days' => 1095],
            ['name' => 'Certificado Seguridad y Salud Ocupacional',       'orientation' => 'landscape', 'validity_days' => 365],
            ['name' => 'Certificado Operaciones de Planta',               'orientation' => 'landscape', 'validity_days' => 730],
            ['name' => 'Certificado Gestión Administrativa',              'orientation' => 'landscape', 'validity_days' => null],
            ['name' => 'Certificado Programa de Inducción',               'orientation' => 'portrait',  'validity_days' => null],
            ['name' => 'Certificado de Aprovechamiento General',          'orientation' => 'landscape', 'validity_days' => 365],
        ];

        $now = now();
        foreach ($templates as $t) {
            $id = DB::table('learning_certificate_templates')->insertGetId([
                'name'               => $t['name'],
                'page_size'          => 'a4',
                'orientation'        => $t['orientation'],
                'background_file_id' => null,
                'fields'             => json_encode([
                    'worker_name'        => ['x' => 50, 'y' => 45],
                    'certificate_number' => ['x' => 50, 'y' => 75],
                    'qr'                 => ['x' => 90, 'y' => 85],
                    'issued_at'          => ['x' => 50, 'y' => 80],
                ]),
                'validity_days'      => $t['validity_days'],
                'is_active'          => true,
                'created_by'         => null,
                'created_at'         => $now,
                'updated_at'         => $now,
            ]);
            $this->templates[$t['name']] = $id;
        }
    }

    // =====================================================================
    // Instructores
    // =====================================================================

    private function seedInstructors(): void
    {
        $role = Role::where('name', 'instructor')->first();

        $profesionAlim   = Profession::where('name', 'Ingeniero en Industrias Alimentarias')->value('id');
        $profesionVet    = Profession::where('name', 'Médico Veterinario')->value('id');
        $profesionZoot   = Profession::where('name', 'Ingeniero Zootecnista')->value('id');
        $profesionAgro   = Profession::where('name', 'Ingeniero Agroindustrial')->value('id');
        $profesionTecAli = Profession::where('name', 'Técnico en Industrias Alimentarias')->value('id');

        $people = [
            ['dni' => '42188933', 'name' => 'Raúl',    'paternal' => 'Mamani',   'maternal' => 'Quispe',  'gender' => '1', 'cellphone' => '987100001', 'email' => 'raul.mamani@infocir.pe',    'profesion' => $profesionAlim],
            ['dni' => '43221055', 'name' => 'Carmen',  'paternal' => 'Huamán',   'maternal' => 'Flores',  'gender' => '2', 'cellphone' => '987100002', 'email' => 'carmen.huaman@infocir.pe',  'profesion' => $profesionVet],
            ['dni' => '44302711', 'name' => 'Jorge',   'paternal' => 'Apaza',    'maternal' => 'Choque',  'gender' => '1', 'cellphone' => '987100003', 'email' => 'jorge.apaza@infocir.pe',    'profesion' => $profesionZoot],
            ['dni' => '45118720', 'name' => 'Elena',   'paternal' => 'Condori',  'maternal' => 'Mamani',  'gender' => '2', 'cellphone' => '987100004', 'email' => 'elena.condori@infocir.pe',  'profesion' => $profesionAgro],
            ['dni' => '46201884', 'name' => 'Víctor',  'paternal' => 'Cárdenas', 'maternal' => 'Pari',    'gender' => '1', 'cellphone' => '987100005', 'email' => 'victor.cardenas@infocir.pe', 'profesion' => $profesionTecAli],
        ];

        foreach ($people as $i => $p) {
            $person = Person::create([
                'document_type'     => '1',
                'document_number'   => $p['dni'],
                'name'              => $p['name'],
                'paternal_surname'  => $p['paternal'],
                'maternal_surname'  => $p['maternal'],
                'date_birth'        => Carbon::parse('1985-01-01')->addDays($i * 847)->toDateString(),
                'cellphone'         => $p['cellphone'],
                'email'             => $p['email'],
                'gender'            => $p['gender'],
                'country'           => 'PE',
            ]);

            $instructorId = DB::table('learning_instructors')->insertGetId([
                'person_id'  => $person->id,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $coreProfile = CoreProfile::create([
                'person_id'        => $person->id,
                'profileable_type' => 'learning_instructors',
                'profileable_id'   => $instructorId,
            ]);

            $user = User::create([
                'username'  => strtolower(substr($p['name'], 0, 1) . $p['paternal']),
                'email'     => $p['email'],
                'password'  => 'Password123',
                'is_active' => true,
            ]);

            BehaviorProfile::create([
                'user_id'         => $user->id,
                'role_id'         => $role->id,
                'core_profile_id' => $coreProfile->id,
                'is_active'       => true,
            ]);

            $this->instructors[] = $instructorId;
        }
    }

    // =====================================================================
    // Trabajadores (workers)
    // =====================================================================

    private function seedWorkers(): void
    {
        $role = Role::where('name', 'worker')->first();
        $plants = Plant::orderBy('id')->get();

        $positions = Position::pluck('id', 'name')->toArray();
        $instructionDegrees = InstructionDegree::pluck('id', 'name')->toArray();

        // Datos de 30 trabajadores, nombres típicos de la región andina peruana
        $workersData = [
            ['dni' => '70123456', 'name' => 'Juan Carlos',    'paternal' => 'Quispe',   'maternal' => 'Mamani',   'gender' => '1', 'cellphone' => '951000001', 'position' => 'Jefe de planta',        'instruccion' => 'Universitario completo'],
            ['dni' => '70123457', 'name' => 'María Elena',    'paternal' => 'Huamán',   'maternal' => 'Ccama',    'gender' => '2', 'cellphone' => '951000002', 'position' => 'Maestro quesero',       'instruccion' => 'Técnico completo'],
            ['dni' => '70123458', 'name' => 'Pedro Antonio',  'paternal' => 'Flores',   'maternal' => 'Condori',  'gender' => '1', 'cellphone' => '951000003', 'position' => 'Operario de producción','instruccion' => 'Secundaria completa'],
            ['dni' => '70123459', 'name' => 'Rosa Luz',       'paternal' => 'Ccori',    'maternal' => 'Apaza',    'gender' => '2', 'cellphone' => '951000004', 'position' => 'Técnico de laboratorio','instruccion' => 'Técnico completo'],
            ['dni' => '70123460', 'name' => 'Luis Alberto',   'paternal' => 'Ticona',   'maternal' => 'Vilca',    'gender' => '1', 'cellphone' => '951000005', 'position' => 'Encargado de acopio',   'instruccion' => 'Secundaria completa'],

            ['dni' => '70123461', 'name' => 'Ana Sofía',      'paternal' => 'Mamani',   'maternal' => 'Quispe',   'gender' => '2', 'cellphone' => '951000006', 'position' => 'Operario de producción','instruccion' => 'Secundaria completa'],
            ['dni' => '70123462', 'name' => 'Carlos Eduardo', 'paternal' => 'Cutipa',   'maternal' => 'Sucaticona','gender' => '1', 'cellphone' => '951000007', 'position' => 'Almacenero',           'instruccion' => 'Secundaria completa'],
            ['dni' => '70123463', 'name' => 'Gloria Sara',    'paternal' => 'Aruquipa', 'maternal' => 'Choque',   'gender' => '2', 'cellphone' => '951000008', 'position' => 'Maestro quesero',       'instruccion' => 'Técnico incompleto'],
            ['dni' => '70123464', 'name' => 'Miguel Ángel',   'paternal' => 'Puma',     'maternal' => 'Yanque',   'gender' => '1', 'cellphone' => '951000009', 'position' => 'Operario de producción','instruccion' => 'Secundaria incompleta'],
            ['dni' => '70123465', 'name' => 'Juana Ines',     'paternal' => 'Calsín',   'maternal' => 'Callata',  'gender' => '2', 'cellphone' => '951000010', 'position' => 'Encargado de ventas',   'instruccion' => 'Técnico completo'],

            ['dni' => '70123466', 'name' => 'José Luis',      'paternal' => 'Choquehuanca','maternal' => 'Pacompia','gender' => '1', 'cellphone' => '951000011', 'position' => 'Jefe de planta',       'instruccion' => 'Universitario completo'],
            ['dni' => '70123467', 'name' => 'Marcelina',      'paternal' => 'Coaquira', 'maternal' => 'Cahuana',  'gender' => '2', 'cellphone' => '951000012', 'position' => 'Operario de producción','instruccion' => 'Primaria completa'],
            ['dni' => '70123468', 'name' => 'Roberto Carlos', 'paternal' => 'Chura',    'maternal' => 'Charca',   'gender' => '1', 'cellphone' => '951000013', 'position' => 'Encargado de acopio',   'instruccion' => 'Secundaria completa'],
            ['dni' => '70123469', 'name' => 'Elvira Rosa',    'paternal' => 'Sarmiento','maternal' => 'Limache',  'gender' => '2', 'cellphone' => '951000014', 'position' => 'Asistente administrativo','instruccion' => 'Técnico completo'],
            ['dni' => '70123470', 'name' => 'Franklin',       'paternal' => 'Mamani',   'maternal' => 'Larico',   'gender' => '1', 'cellphone' => '951000015', 'position' => 'Operario de producción','instruccion' => 'Secundaria completa'],

            ['dni' => '70123471', 'name' => 'Doris Elizabeth','paternal' => 'Quispe',   'maternal' => 'Flores',   'gender' => '2', 'cellphone' => '951000016', 'position' => 'Técnico de laboratorio','instruccion' => 'Universitario incompleto'],
            ['dni' => '70123472', 'name' => 'Edwin',          'paternal' => 'Velásquez','maternal' => 'Condori',  'gender' => '1', 'cellphone' => '951000017', 'position' => 'Almacenero',           'instruccion' => 'Secundaria completa'],
            ['dni' => '70123473', 'name' => 'Yesenia',        'paternal' => 'Huanca',   'maternal' => 'Jilaja',   'gender' => '2', 'cellphone' => '951000018', 'position' => 'Maestro quesero',       'instruccion' => 'Técnico completo'],
            ['dni' => '70123474', 'name' => 'Wilder',         'paternal' => 'Ccopa',    'maternal' => 'Zapana',   'gender' => '1', 'cellphone' => '951000019', 'position' => 'Operario de producción','instruccion' => 'Secundaria completa'],
            ['dni' => '70123475', 'name' => 'Hilda',          'paternal' => 'Pari',     'maternal' => 'Machaca',  'gender' => '2', 'cellphone' => '951000020', 'position' => 'Encargado de ventas',   'instruccion' => 'Secundaria completa'],

            ['dni' => '70123476', 'name' => 'Nicolás',        'paternal' => 'Apaza',    'maternal' => 'Huallpa',  'gender' => '1', 'cellphone' => '951000021', 'position' => 'Jefe de planta',       'instruccion' => 'Universitario completo'],
            ['dni' => '70123477', 'name' => 'Rocío',          'paternal' => 'Nina',     'maternal' => 'Patana',   'gender' => '2', 'cellphone' => '951000022', 'position' => 'Operario de producción','instruccion' => 'Secundaria completa'],
            ['dni' => '70123478', 'name' => 'Saúl',           'paternal' => 'Condori',  'maternal' => 'Turpo',    'gender' => '1', 'cellphone' => '951000023', 'position' => 'Encargado de acopio',   'instruccion' => 'Secundaria completa'],
            ['dni' => '70123479', 'name' => 'Vilma',          'paternal' => 'Rojas',    'maternal' => 'Chuquimia','gender' => '2', 'cellphone' => '951000024', 'position' => 'Técnico de laboratorio','instruccion' => 'Técnico completo'],
            ['dni' => '70123480', 'name' => 'Ernesto',        'paternal' => 'Canaza',   'maternal' => 'Quispe',   'gender' => '1', 'cellphone' => '951000025', 'position' => 'Operario de producción','instruccion' => 'Secundaria completa'],

            ['dni' => '70123481', 'name' => 'Felicitas',      'paternal' => 'Soncco',   'maternal' => 'Arce',     'gender' => '2', 'cellphone' => '951000026', 'position' => 'Asistente administrativo','instruccion' => 'Técnico completo'],
            ['dni' => '70123482', 'name' => 'Ronald',         'paternal' => 'Hancco',   'maternal' => 'Mullisaca','gender' => '1', 'cellphone' => '951000027', 'position' => 'Almacenero',           'instruccion' => 'Secundaria completa'],
            ['dni' => '70123483', 'name' => 'Bertha',         'paternal' => 'Yupanqui', 'maternal' => 'Huaraya',  'gender' => '2', 'cellphone' => '951000028', 'position' => 'Maestro quesero',       'instruccion' => 'Técnico completo'],
            ['dni' => '70123484', 'name' => 'Dionisio',       'paternal' => 'Chambilla','maternal' => 'Quispe',   'gender' => '1', 'cellphone' => '951000029', 'position' => 'Operario de producción','instruccion' => 'Primaria completa'],
            ['dni' => '70123485', 'name' => 'Guadalupe',      'paternal' => 'Quenta',   'maternal' => 'Luque',    'gender' => '2', 'cellphone' => '951000030', 'position' => 'Encargado de ventas',   'instruccion' => 'Secundaria completa'],
        ];

        $plantIds = $plants->pluck('id')->toArray();

        foreach ($workersData as $i => $w) {
            $plantId = $plantIds[$i % count($plantIds)];
            $entityId = $this->plantEntityIds[$plantId] ?? null;
            if ($entityId === null) {
                continue;
            }

            $person = Person::create([
                'document_type'    => '1',
                'document_number'  => $w['dni'],
                'name'             => $w['name'],
                'paternal_surname' => $w['paternal'],
                'maternal_surname' => $w['maternal'],
                'date_birth'       => Carbon::parse('1980-01-01')->addDays($i * 423)->toDateString(),
                'cellphone'        => $w['cellphone'],
                'email'            => null,
                'gender'           => $w['gender'],
                'country'          => 'PE',
            ]);

            $worker = Worker::create([
                'person_id'             => $person->id,
                'entity_id'             => $entityId,
                'position_id'           => $positions[$w['position']] ?? null,
                'instruction_degree_id' => $instructionDegrees[$w['instruccion']] ?? null,
                'profession_id'         => null,
                'is_active'             => true,
            ]);

            $coreProfile = CoreProfile::create([
                'person_id'        => $person->id,
                'profileable_type' => 'dairy_workers',
                'profileable_id'   => $worker->person_id,
            ]);

            $username = 'tr' . str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT);
            $user = User::create([
                'username'  => $username,
                'email'     => $username . '@infocir.pe',
                'password'  => 'Password123',
                'is_active' => true,
            ]);

            BehaviorProfile::create([
                'user_id'         => $user->id,
                'role_id'         => $role->id,
                'core_profile_id' => $coreProfile->id,
                'is_active'       => true,
            ]);

            $this->workers[] = ['person_id' => $person->id, 'plant_id' => $plantId];
        }
    }

    // =====================================================================
    // Cursos, módulos, lecciones, recursos y quizzes
    // =====================================================================

    private function seedCourses(): void
    {
        $courses = $this->coursesCatalog();

        foreach ($courses as $idx => $courseDef) {
            $courseId = DB::table('learning_courses')->insertGetId([
                'name'                    => $courseDef['name'],
                'description'             => $courseDef['description'],
                'area_id'                 => $this->areas[$courseDef['area']] ?? null,
                'duration_min'            => $courseDef['duration_min'],
                'cover_image'             => null,
                'certificate_template_id' => $this->templates[$courseDef['template']] ?? null,
                'status'                  => 'published',
                'created_by'              => null,
                'created_at'              => now(),
                'updated_at'              => now(),
            ]);

            $lessonIds = [];

            foreach ($courseDef['modules'] as $mIdx => $module) {
                $moduleId = DB::table('learning_course_modules')->insertGetId([
                    'course_id'   => $courseId,
                    'title'       => $module['title'],
                    'description' => $module['description'] ?? null,
                    'order'       => $mIdx + 1,
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);

                foreach ($module['lessons'] as $lIdx => $lesson) {
                    $hasQuiz = !empty($lesson['quiz']);
                    $lessonId = DB::table('learning_lessons')->insertGetId([
                        'module_id'     => $moduleId,
                        'title'         => $lesson['title'],
                        'description'   => $lesson['description'] ?? null,
                        'order'         => $lIdx + 1,
                        'has_quiz'      => $hasQuiz,
                        'passing_score' => 70.00,
                        'is_active'     => true,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);

                    $lessonIds[] = $lessonId;

                    // Recurso markdown principal
                    DB::table('learning_lesson_resources')->insert([
                        'lesson_id'  => $lessonId,
                        'type'       => 'text',
                        'title'      => $lesson['title'],
                        'file_id'    => null,
                        'body'       => $lesson['content'],
                        'order'      => 1,
                        'is_active'  => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    if ($hasQuiz) {
                        foreach ($lesson['quiz'] as $qIdx => $q) {
                            $questionId = DB::table('learning_quiz_questions')->insertGetId([
                                'lesson_id'  => $lessonId,
                                'question'   => $q['question'],
                                'hint'       => $q['hint'] ?? null,
                                'order'      => $qIdx + 1,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);

                            foreach ($q['options'] as $oIdx => $opt) {
                                DB::table('learning_quiz_options')->insert([
                                    'question_id' => $questionId,
                                    'text'        => $opt['text'],
                                    'is_correct'  => $opt['correct'] ?? false,
                                    'explanation' => $opt['explanation'] ?? null,
                                    'order'       => $oIdx + 1,
                                    'created_at'  => now(),
                                    'updated_at'  => now(),
                                ]);
                            }
                        }
                    }
                }
            }

            $this->coursesData[] = [
                'id'          => $courseId,
                'name'        => $courseDef['name'],
                'lesson_ids'  => $lessonIds,
                'template_id' => $this->templates[$courseDef['template']] ?? null,
            ];
        }
    }

    // =====================================================================
    // Programas
    // =====================================================================

    private function seedPrograms(): void
    {
        $coursesByName = collect($this->coursesData)->keyBy('name');

        $programs = [
            [
                'name'        => 'Programa de Inducción a Operarios Nuevos',
                'description' => 'Formación inicial obligatoria para todo operario que ingresa a la planta láctea. Cubre higiene, uso de EPP y recepción básica de leche.',
                'template'    => 'Certificado Programa de Inducción',
                'courses'     => [
                    'Introducción a Buenas Prácticas de Manufactura',
                    'Higiene Personal del Operario Lácteo',
                    'Uso Correcto de EPP en Planta',
                    'Recepción y Acopio de Leche Fresca',
                ],
            ],
            [
                'name'        => 'Programa Supervisores de Calidad',
                'description' => 'Formación técnica para personal responsable del control de calidad en planta láctea.',
                'template'    => 'Certificado HACCP Lácteos',
                'courses'     => [
                    'Análisis Fisicoquímico de Leche Cruda',
                    'Análisis Microbiológico Básico en Planta',
                    'HACCP aplicado a Productos Lácteos',
                    'Trazabilidad y Control de Lote',
                ],
            ],
            [
                'name'        => 'Programa Maestros Queseros',
                'description' => 'Especialización en elaboración de quesos tradicionales y modernos. Dirigido a operarios con experiencia.',
                'template'    => 'Certificado Operaciones de Planta',
                'courses'     => [
                    'Pasteurización de Leche para Lácteos',
                    'Elaboración de Queso Fresco Artesanal',
                    'Elaboración de Yogurt Natural',
                ],
            ],
        ];

        foreach ($programs as $p) {
            $programId = DB::table('learning_programs')->insertGetId([
                'name'                    => $p['name'],
                'description'             => $p['description'],
                'certificate_template_id' => $this->templates[$p['template']] ?? null,
                'status'                  => 'published',
                'is_active'               => true,
                'created_by'              => null,
                'created_at'              => now(),
                'updated_at'              => now(),
            ]);

            foreach ($p['courses'] as $idx => $courseName) {
                $course = $coursesByName->get($courseName);
                if (!$course) {
                    continue;
                }
                DB::table('learning_program_courses')->insert([
                    'program_id'  => $programId,
                    'course_id'   => $course['id'],
                    'order'       => $idx + 1,
                    'is_required' => true,
                ]);
            }
        }
    }

    // =====================================================================
    // Trainings (capacitaciones puntuales)
    // =====================================================================

    private function seedTrainings(): void
    {
        $coursesByName = collect($this->coursesData)->keyBy('name');
        $instructorCount = count($this->instructors);
        $ttTaller = $this->trainingTypes['Taller práctico'] ?? null;
        $ttCharla = $this->trainingTypes['Charla informativa'] ?? null;
        $ttElearn = $this->trainingTypes['Curso e-learning'] ?? null;
        $ttSim    = $this->trainingTypes['Simulacro'] ?? null;

        $trainings = [
            ['course' => 'Introducción a Buenas Prácticas de Manufactura',  'type' => $ttElearn, 'modality' => 'virtual',   'status' => 'ongoing',   'date_offset' => -15, 'location' => 'Plataforma INFOCIR'],
            ['course' => 'Higiene Personal del Operario Lácteo',             'type' => $ttTaller, 'modality' => 'in_person', 'status' => 'completed', 'date_offset' => -60, 'location' => 'Planta Ecolácteos Huata'],
            ['course' => 'Limpieza y Desinfección de Equipos',               'type' => $ttTaller, 'modality' => 'mixed',     'status' => 'completed', 'date_offset' => -45, 'location' => 'Planta San Francisco'],
            ['course' => 'Uso Correcto de EPP en Planta',                    'type' => $ttCharla, 'modality' => 'in_person', 'status' => 'scheduled', 'date_offset' => 10,  'location' => 'Sala de capacitación - Grupo Yaguno'],
            ['course' => 'Análisis Fisicoquímico de Leche Cruda',            'type' => $ttTaller, 'modality' => 'in_person', 'status' => 'ongoing',   'date_offset' => -7,  'location' => 'Laboratorio Cooperativa Copagro'],
            ['course' => 'HACCP aplicado a Productos Lácteos',               'type' => $ttElearn, 'modality' => 'virtual',   'status' => 'scheduled', 'date_offset' => 20,  'location' => 'Plataforma INFOCIR'],
            ['course' => 'Pasteurización de Leche para Lácteos',             'type' => $ttTaller, 'modality' => 'in_person', 'status' => 'completed', 'date_offset' => -90, 'location' => 'Planta Chrysef'],
            ['course' => 'Elaboración de Queso Fresco Artesanal',            'type' => $ttTaller, 'modality' => 'in_person', 'status' => 'scheduled', 'date_offset' => 30,  'location' => 'Planta Quesera Florentina'],
            ['course' => 'Prevención de Accidentes Laborales',               'type' => $ttCharla, 'modality' => 'in_person', 'status' => 'completed', 'date_offset' => -30, 'location' => 'Sala común San Santiago'],
            ['course' => null,                                               'type' => $ttSim,    'modality' => 'in_person', 'status' => 'completed', 'date_offset' => -20, 'location' => 'Patio de planta - San Francisco', 'event_only' => true,  'is_event_only_title' => 'Simulacro de evacuación por sismo'],
            ['course' => null,                                               'type' => $ttCharla, 'modality' => 'in_person', 'status' => 'scheduled', 'date_offset' => 45,  'location' => 'Auditorio municipal', 'event_only' => true, 'is_event_only_title' => 'Charla SENASA: actualización normativa láctea'],
            ['course' => 'Gestión de Proveedores de Leche Fresca',           'type' => $ttElearn, 'modality' => 'virtual',   'status' => 'ongoing',   'date_offset' => -10, 'location' => 'Plataforma INFOCIR'],
        ];

        foreach ($trainings as $i => $t) {
            $courseId = null;
            $templateId = null;
            if (!empty($t['course']) && $coursesByName->has($t['course'])) {
                $course = $coursesByName->get($t['course']);
                $courseId = $course['id'];
                $templateId = $course['template_id'];
            }

            $startDate = Carbon::now()->addDays($t['date_offset'])->toDateString();
            $endDate = Carbon::now()->addDays($t['date_offset'] + 2)->toDateString();

            DB::table('learning_trainings')->insert([
                'course_id'               => $courseId,
                'instructor_id'           => $this->instructors[$i % $instructorCount],
                'training_type_id'        => $t['type'],
                'certificate_template_id' => $templateId,
                'is_event_only'           => $t['event_only'] ?? false,
                'start_date'              => $startDate,
                'end_date'                => $endDate,
                'status'                  => $t['status'],
                'modality'                => $t['modality'],
                'location'                => $t['location'],
                'max_participants'        => 30,
                'is_active'               => true,
                'created_by'              => null,
                'created_at'              => now(),
                'updated_at'              => now(),
            ]);
        }
    }

    // =====================================================================
    // Program deliveries (dictados de programa)
    // =====================================================================

    private function seedProgramDeliveries(): void
    {
        $programs = DB::table('learning_programs')->get();
        $instructorCount = count($this->instructors);
        $ttInd = $this->trainingTypes['Inducción'] ?? null;
        $ttTaller = $this->trainingTypes['Taller práctico'] ?? null;

        $deliveries = [
            ['program_idx' => 0, 'type' => $ttInd,    'modality' => 'mixed',     'status' => 'ongoing',   'offset' => -20, 'location' => 'Planta Ecolácteos Huata'],
            ['program_idx' => 0, 'type' => $ttInd,    'modality' => 'in_person', 'status' => 'scheduled', 'offset' => 15,  'location' => 'Planta San Francisco'],
            ['program_idx' => 1, 'type' => $ttTaller, 'modality' => 'virtual',   'status' => 'ongoing',   'offset' => -10, 'location' => 'Plataforma INFOCIR'],
            ['program_idx' => 1, 'type' => $ttTaller, 'modality' => 'in_person', 'status' => 'completed', 'offset' => -120, 'location' => 'Planta Chrysef'],
            ['program_idx' => 2, 'type' => $ttTaller, 'modality' => 'in_person', 'status' => 'scheduled', 'offset' => 30,  'location' => 'Planta Quesera Florentina'],
        ];

        $programList = $programs->values();

        foreach ($deliveries as $i => $d) {
            $program = $programList[$d['program_idx']] ?? null;
            if (!$program) {
                continue;
            }
            DB::table('learning_program_deliveries')->insert([
                'program_id'       => $program->id,
                'instructor_id'    => $this->instructors[$i % $instructorCount],
                'training_type_id' => $d['type'],
                'start_date'       => Carbon::now()->addDays($d['offset'])->toDateString(),
                'end_date'         => Carbon::now()->addDays($d['offset'] + 60)->toDateString(),
                'status'           => $d['status'],
                'modality'         => $d['modality'],
                'location'         => $d['location'],
                'max_participants' => 20,
                'is_active'        => true,
                'created_by'       => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }

    // =====================================================================
    // Inscripciones y progreso
    // =====================================================================

    private function seedEnrollmentsAndProgress(): void
    {
        if (count($this->workers) === 0 || count($this->coursesData) === 0) {
            return;
        }

        $now = now();
        $certCounter = 1;

        foreach ($this->workers as $wIdx => $worker) {
            // Cada trabajador se inscribe a 4-6 cursos distintos (rotación)
            $courseCount = 4 + ($wIdx % 3);
            $pickedIndexes = [];
            for ($i = 0; $i < $courseCount; $i++) {
                $pickedIndexes[] = ($wIdx + $i * 3) % count($this->coursesData);
            }
            $pickedIndexes = array_values(array_unique($pickedIndexes));

            foreach ($pickedIndexes as $pos => $cIdx) {
                $course = $this->coursesData[$cIdx];
                $totalLessons = count($course['lesson_ids']);
                if ($totalLessons === 0) {
                    continue;
                }

                // Distribución realista de estados
                // pos 0,1 -> completado ; pos 2,3 -> en progreso ; resto -> enrolled
                $state = $pos < 2 ? 'completed' : ($pos < 4 ? 'in_progress' : 'enrolled');
                // pequeña variación: algunos dropped
                if ($wIdx % 11 === 0 && $pos === 4) {
                    $state = 'dropped';
                }

                $completedLessons = match ($state) {
                    'completed'   => $totalLessons,
                    'in_progress' => max(1, intdiv($totalLessons, 2)),
                    'dropped'     => max(1, intdiv($totalLessons, 3)),
                    default       => 0,
                };
                $progress = round(($completedLessons / $totalLessons) * 100, 2);

                $enrolledAt = $now->copy()->subDays(60 - $pos * 10 - ($wIdx % 7));
                $completedAt = $state === 'completed' ? $enrolledAt->copy()->addDays(14 + ($wIdx % 7)) : null;

                $enrollmentId = DB::table('learning_enrollments')->insertGetId([
                    'enrollable_type' => 'learning_courses',
                    'enrollable_id'   => $course['id'],
                    'worker_id'       => $worker['person_id'],
                    'status'          => $state,
                    'progress'        => $progress,
                    'enrolled_at'     => $enrolledAt,
                    'completed_at'    => $completedAt,
                    'created_at'      => $enrolledAt,
                    'updated_at'      => $completedAt ?? $enrolledAt,
                ]);

                // lesson_progress
                for ($li = 0; $li < $completedLessons; $li++) {
                    $lessonId = $course['lesson_ids'][$li];
                    DB::table('learning_lesson_progress')->insert([
                        'enrollment_id' => $enrollmentId,
                        'lesson_id'     => $lessonId,
                        'completed'     => true,
                        'completed_at'  => $enrolledAt->copy()->addDays($li + 1),
                        'created_at'    => $enrolledAt->copy()->addDays($li + 1),
                        'updated_at'    => $enrolledAt->copy()->addDays($li + 1),
                    ]);

                    // quiz attempts para lecciones con quiz (asumimos todas tienen quiz en seed)
                    $questions = DB::table('learning_quiz_questions')->where('lesson_id', $lessonId)->get();
                    if ($questions->count() > 0) {
                        $score = $state === 'completed' ? (80 + ($wIdx % 20)) : (60 + ($wIdx % 30));
                        $passed = $score >= 70;
                        $attemptId = DB::table('learning_quiz_attempts')->insertGetId([
                            'enrollment_id' => $enrollmentId,
                            'lesson_id'     => $lessonId,
                            'score'         => $score,
                            'passed'        => $passed,
                            'attempted_at'  => $enrolledAt->copy()->addDays($li + 1),
                            'created_at'    => $enrolledAt->copy()->addDays($li + 1),
                            'updated_at'    => $enrolledAt->copy()->addDays($li + 1),
                        ]);

                        foreach ($questions as $qi => $q) {
                            // tomar una opción: si passed/completed, la correcta; si no, a veces incorrecta
                            $optionsQ = DB::table('learning_quiz_options')->where('question_id', $q->id)->get();
                            if ($optionsQ->isEmpty()) {
                                continue;
                            }
                            $correctOption = $optionsQ->firstWhere('is_correct', 1) ?? $optionsQ->first();
                            $chosen = $passed ? $correctOption : ($qi % 2 === 0 ? $correctOption : $optionsQ->first());
                            DB::table('learning_quiz_answers')->insert([
                                'attempt_id'  => $attemptId,
                                'question_id' => $q->id,
                                'option_id'   => $chosen->id,
                                'created_at'  => $enrolledAt->copy()->addDays($li + 1),
                                'updated_at'  => $enrolledAt->copy()->addDays($li + 1),
                            ]);
                        }
                    }
                }

                // Certificación si completó y el curso tiene plantilla
                if ($state === 'completed' && $course['template_id'] !== null) {
                    $template = DB::table('learning_certificate_templates')->find($course['template_id']);
                    $validityDays = $template->validity_days ?? null;
                    $issuedAt = $completedAt->toDateString();
                    $expiresAt = $validityDays ? $completedAt->copy()->addDays($validityDays)->toDateString() : null;
                    $year = $completedAt->format('Y');

                    DB::table('learning_certifications')->insert([
                        'enrollment_id'      => $enrollmentId,
                        'template_id'        => $course['template_id'],
                        'certificate_number' => sprintf('CERT-%s-%05d', $year, $certCounter++),
                        'issued_at'          => $issuedAt,
                        'expires_at'         => $expiresAt,
                        'created_at'         => $completedAt,
                        'updated_at'         => $completedAt,
                    ]);
                }
            }
        }
    }

    // =====================================================================
    // Catálogo de cursos con contenido real (17 cursos)
    // =====================================================================

    /**
     * @return array<int, array<string, mixed>>
     */
    private function coursesCatalog(): array
    {
        $bpm    = 'Buenas Prácticas de Manufactura';
        $ino    = 'Inocuidad y Calidad';
        $seg    = 'Seguridad y Salud en el Trabajo';
        $ope    = 'Operaciones de Planta';
        $adm    = 'Gestión Administrativa';

        $tplBpm   = 'Certificado BPM — Industria Láctea';
        $tplIno   = 'Certificado Inocuidad y Calidad';
        $tplHaccp = 'Certificado HACCP Lácteos';
        $tplSeg   = 'Certificado Seguridad y Salud Ocupacional';
        $tplOpe   = 'Certificado Operaciones de Planta';
        $tplAdm   = 'Certificado Gestión Administrativa';
        $tplGen   = 'Certificado de Aprovechamiento General';

        return [
            // ---------------- BPM (4) ----------------
            [
                'name' => 'Introducción a Buenas Prácticas de Manufactura',
                'description' => 'Fundamentos de las BPM aplicadas a la industria de lácteos: conceptos, normativa peruana (DIGESA) y responsabilidades del personal de planta.',
                'area' => $bpm, 'template' => $tplBpm, 'duration_min' => 180,
                'modules' => [
                    [
                        'title' => '¿Qué son las BPM?',
                        'description' => 'Definiciones y marco normativo nacional.',
                        'lessons' => [
                            [
                                'title' => 'Definición y objetivos de las BPM',
                                'description' => 'Concepto, finalidad y principios de las Buenas Prácticas de Manufactura.',
                                'content' => "# Buenas Prácticas de Manufactura (BPM)\n\nLas **Buenas Prácticas de Manufactura** son el conjunto de procedimientos, condiciones y controles que se aplican en las plantas de alimentos para asegurar que los productos que salen al mercado sean **inocuos, seguros y de calidad**.\n\n## Objetivos principales\n\n- Proteger la **salud del consumidor**.\n- Evitar la **contaminación cruzada** entre productos.\n- Cumplir con los **estándares nacionales** (DIGESA, SENASA) e internacionales (Codex Alimentarius).\n- Garantizar la **trazabilidad** desde la recepción de la leche hasta el producto final.\n\n## En la planta láctea\n\nEn una planta láctea las BPM se aplican en **todas las etapas**: recepción de leche, almacenamiento, pasteurización, elaboración, envasado, etiquetado y despacho.\n\n> **Recuerda**: sin BPM no hay inocuidad, y sin inocuidad ponemos en riesgo al consumidor final.",
                                'quiz' => [
                                    [
                                        'question' => '¿Cuál es el objetivo principal de las BPM en una planta láctea?',
                                        'hint' => 'Piensa en quién consume los productos.',
                                        'options' => [
                                            ['text' => 'Aumentar la producción diaria',           'correct' => false, 'explanation' => 'La producción es una consecuencia, no el objetivo principal.'],
                                            ['text' => 'Proteger la salud del consumidor',        'correct' => true,  'explanation' => 'Exacto: la inocuidad del producto es el fin último de las BPM.'],
                                            ['text' => 'Reducir el costo de la mano de obra',     'correct' => false, 'explanation' => 'Las BPM no buscan reducir mano de obra.'],
                                            ['text' => 'Hacer la planta más grande',               'correct' => false, 'explanation' => 'El tamaño de la planta no es parte de las BPM.'],
                                        ],
                                    ],
                                    [
                                        'question' => 'En Perú, ¿qué institución regula las BPM en alimentos?',
                                        'options' => [
                                            ['text' => 'DIGESA', 'correct' => true,  'explanation' => 'Correcto: DIGESA es la autoridad sanitaria en alimentos.'],
                                            ['text' => 'SUNAT',  'correct' => false, 'explanation' => 'SUNAT se encarga de tributos, no de alimentos.'],
                                            ['text' => 'INDECI', 'correct' => false, 'explanation' => 'INDECI se encarga de defensa civil.'],
                                            ['text' => 'MINEDU', 'correct' => false, 'explanation' => 'MINEDU es educación.'],
                                        ],
                                    ],
                                    [
                                        'question' => '¿Las BPM se aplican solo al operario?',
                                        'options' => [
                                            ['text' => 'Sí, solo al personal de producción',   'correct' => false, 'explanation' => 'Incorrecto: las BPM involucran a todo el personal.'],
                                            ['text' => 'No, a todo el personal de la planta',   'correct' => true,  'explanation' => 'Correcto: incluye operarios, supervisores, limpieza y administración.'],
                                            ['text' => 'Solo al jefe de planta',                 'correct' => false, 'explanation' => 'Todos son responsables.'],
                                            ['text' => 'Solo cuando viene SENASA',               'correct' => false, 'explanation' => 'Se aplican siempre, no solo ante inspecciones.'],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Normativa peruana aplicable',
                                'description' => 'Principales normas: DS 007-98-SA, RM 449-2006-MINSA.',
                                'content' => "# Normativa peruana en BPM\n\nEn el Perú la industria de alimentos se rige principalmente por:\n\n## Normas clave\n\n- **DS 007-98-SA** — Reglamento sobre Vigilancia y Control Sanitario de Alimentos y Bebidas.\n- **RM 449-2006/MINSA** — Norma Sanitaria para la aplicación del sistema HACCP en la fabricación de alimentos y bebidas.\n- **NTS 071-MINSA/DIGESA** — Criterios microbiológicos para alimentos.\n\n## Autorización sanitaria\n\nToda planta láctea debe contar con **Registro Sanitario** vigente emitido por DIGESA para cada uno de sus productos.\n\n## Control oficial\n\n- **DIGESA** supervisa la inocuidad en alimentos procesados.\n- **SENASA** tiene competencia sobre la inocuidad de productos de origen animal (incluye leche cruda).\n\n> ⚠️ No contar con Registro Sanitario puede derivar en el **cierre de la planta** y decomiso de productos.",
                                'quiz' => [
                                    [
                                        'question' => '¿Qué documento obligatorio debe tener cada producto lácteo procesado?',
                                        'options' => [
                                            ['text' => 'Registro Sanitario DIGESA',     'correct' => true,  'explanation' => 'Exacto: es obligatorio para la comercialización.'],
                                            ['text' => 'Certificado ISO 9001',           'correct' => false, 'explanation' => 'Es voluntario, no obligatorio.'],
                                            ['text' => 'Licencia de funcionamiento',      'correct' => false, 'explanation' => 'Es municipal, pero no reemplaza al sanitario.'],
                                            ['text' => 'Permiso ambiental',               'correct' => false, 'explanation' => 'No aplica a inocuidad alimentaria.'],
                                        ],
                                    ],
                                    [
                                        'question' => '¿Qué institución supervisa la inocuidad de la leche cruda?',
                                        'options' => [
                                            ['text' => 'SENASA',  'correct' => true,  'explanation' => 'Correcto: tiene competencia sobre productos de origen animal.'],
                                            ['text' => 'SUNASS',  'correct' => false, 'explanation' => 'SUNASS regula saneamiento.'],
                                            ['text' => 'OSINERG', 'correct' => false, 'explanation' => 'Regula energía.'],
                                            ['text' => 'ANA',     'correct' => false, 'explanation' => 'Gestión del agua.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Pilares de las BPM en planta láctea',
                        'lessons' => [
                            [
                                'title' => 'Instalaciones, equipos y personal',
                                'content' => "# Los tres pilares\n\nLas BPM en planta láctea se apoyan en tres pilares:\n\n## 1. Instalaciones\n\n- Pisos y paredes de **material lavable** (cerámico, acero inoxidable).\n- Ventilación adecuada para evitar condensación.\n- Separación clara entre áreas **sucias** (recepción de leche) y **limpias** (envasado).\n- Agua potable certificada.\n\n## 2. Equipos\n\n- De **acero inoxidable AISI 304/316** (resistente, no oxida).\n- Sin ranuras ni esquinas donde se acumule leche.\n- Calibración periódica de termómetros y pHmetros.\n\n## 3. Personal\n\n- Salud certificada (**carnet sanitario** vigente).\n- Capacitación continua en BPM.\n- Uniforme limpio, botas, cofia y mascarilla.\n\n> Un solo pilar débil **compromete toda la inocuidad** del producto final.",
                                'quiz' => [
                                    [
                                        'question' => '¿Por qué los equipos en planta láctea deben ser de acero inoxidable?',
                                        'options' => [
                                            ['text' => 'Porque es más barato',                        'correct' => false, 'explanation' => 'En realidad es más caro, pero justificado por inocuidad.'],
                                            ['text' => 'Porque no se oxida y es fácil de limpiar',    'correct' => true,  'explanation' => 'Exacto: evita contaminación química y microbiológica.'],
                                            ['text' => 'Porque pesa menos que el hierro',              'correct' => false, 'explanation' => 'El peso no es el criterio principal.'],
                                            ['text' => 'Porque es de color brillante',                 'correct' => false, 'explanation' => 'La estética no es el motivo.'],
                                        ],
                                    ],
                                    [
                                        'question' => '¿Qué documento de salud debe tener todo manipulador de alimentos?',
                                        'options' => [
                                            ['text' => 'Carnet sanitario vigente',     'correct' => true,  'explanation' => 'Obligatorio según normativa DIGESA.'],
                                            ['text' => 'Carnet del sindicato',          'correct' => false, 'explanation' => 'No es un requisito sanitario.'],
                                            ['text' => 'Carnet de biblioteca',          'correct' => false, 'explanation' => 'No aplica.'],
                                            ['text' => 'Licencia de conducir',          'correct' => false, 'explanation' => 'No es un documento sanitario.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Tu rol en las BPM',
                        'lessons' => [
                            [
                                'title' => 'Responsabilidades del operario',
                                'content' => "# Eres parte clave de la inocuidad\n\nCada operario, desde el que recibe la leche hasta el que etiqueta el envase, es **responsable de la inocuidad** del producto.\n\n## Tus responsabilidades\n\n- Asistir con tu uniforme **limpio** y completo.\n- Lavar tus manos **antes y después** de cada operación.\n- Informar si tienes síntomas de enfermedad (tos, diarrea, heridas abiertas).\n- Respetar las áreas: no pasar del área sucia a la limpia sin cambio de botas.\n- Reportar cualquier irregularidad: un equipo mal lavado, una plaga, una fuga.\n\n## Lo que NO debes hacer\n\n- ❌ Comer, fumar o masticar goma dentro de planta.\n- ❌ Usar anillos, pulseras, reloj o joyas.\n- ❌ Toser o estornudar sobre el producto.\n- ❌ Ocultar una mala práctica para no ser llamado la atención.\n\n> Si ves algo mal, **reportarlo no es una falla, es proteger a nuestros consumidores**.",
                                'quiz' => [
                                    [
                                        'question' => 'Si un compañero manipula leche sin haberse lavado las manos, ¿qué debes hacer?',
                                        'options' => [
                                            ['text' => 'No decir nada para no crear problemas',   'correct' => false, 'explanation' => 'Ocultarlo pone en riesgo al consumidor.'],
                                            ['text' => 'Reportarlo al supervisor',                 'correct' => true,  'explanation' => 'Correcto: la inocuidad es responsabilidad de todos.'],
                                            ['text' => 'Hacer lo mismo para no destacar',           'correct' => false, 'explanation' => 'Repetir el error no es solución.'],
                                            ['text' => 'Grabarlo con el celular',                   'correct' => false, 'explanation' => 'Además, los celulares no deben estar en planta.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Higiene Personal del Operario Lácteo',
                'description' => 'Prácticas de higiene personal, lavado de manos, uniforme y salud del trabajador que manipula leche y derivados.',
                'area' => $bpm, 'template' => $tplBpm, 'duration_min' => 120,
                'modules' => [
                    [
                        'title' => 'Higiene corporal',
                        'lessons' => [
                            [
                                'title' => 'Lavado correcto de manos',
                                'content' => "# Lavado de manos paso a paso\n\nLas manos son la **principal vía de contaminación** en planta láctea. Un lavado correcto toma **al menos 40 segundos**.\n\n## Pasos obligatorios\n\n1. Mojar las manos con **agua tibia**.\n2. Aplicar jabón desinfectante (antibacterial).\n3. Frotar palmas, dorsos, entre los dedos y bajo las uñas durante 20 segundos.\n4. Enjuagar con abundante agua.\n5. Secar con **toalla desechable o aire**, nunca con trapo reutilizable.\n6. Desinfectar con solución de alcohol al 70% si corresponde.\n\n## ¿Cuándo lavarse?\n\n- Al ingresar a planta.\n- Después de ir al baño.\n- Después de toser, estornudar o sonarse.\n- Después de tocar superficies no limpias.\n- Entre cambio de producto.\n- Cada 2 horas como mínimo.\n\n> Las manos lavadas **son la primera barrera** contra la contaminación microbiana.",
                                'quiz' => [
                                    [
                                        'question' => '¿Cuánto tiempo debe durar el lavado de manos?',
                                        'options' => [
                                            ['text' => 'Menos de 10 segundos',         'correct' => false, 'explanation' => 'Muy poco, no elimina bacterias.'],
                                            ['text' => 'Al menos 40 segundos',          'correct' => true,  'explanation' => 'Correcto, tiempo mínimo efectivo.'],
                                            ['text' => '5 minutos',                     'correct' => false, 'explanation' => 'Excesivo, desperdicia agua.'],
                                            ['text' => 'Solo mojar y listo',            'correct' => false, 'explanation' => 'No remueve microorganismos.'],
                                        ],
                                    ],
                                    [
                                        'question' => '¿Con qué se deben secar las manos en planta láctea?',
                                        'options' => [
                                            ['text' => 'Toalla de tela reutilizable',   'correct' => false, 'explanation' => 'Acumula bacterias entre usos.'],
                                            ['text' => 'Aire o toalla desechable',       'correct' => true,  'explanation' => 'Correcto: evita recontaminación.'],
                                            ['text' => 'Trapo industrial',               'correct' => false, 'explanation' => 'No es higiénico.'],
                                            ['text' => 'Sacudiéndolas al aire',          'correct' => false, 'explanation' => 'Dispersa microorganismos en el ambiente.'],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Uniforme y barreras sanitarias',
                                'content' => "# Uniforme correcto del operario\n\nEl uniforme es la **segunda barrera** de inocuidad.\n\n## Elementos obligatorios\n\n| Elemento | Función |\n|----------|---------|\n| **Cofia o gorro** | Evita caída de cabellos al producto |\n| **Mascarilla** | Evita contaminación por saliva |\n| **Mandil o chaqueta blanca** | Reduce contaminación por ropa de calle |\n| **Botas de PVC blancas** | Evitan arrastrar suciedad del exterior |\n| **Guantes (cuando aplica)** | Contacto directo con producto |\n\n## Reglas\n\n- ❌ No ingresar a planta con **ropa de calle**.\n- ❌ No salir a la calle con uniforme.\n- ❌ No usar **joyas, relojes, uñas largas o pintadas**.\n- ✅ El uniforme se cambia **todos los días** y se lava en lavandería de planta, no en casa.\n\n> Tu uniforme **habla de la seriedad** con que tomas tu trabajo y la inocuidad del producto.",
                                'quiz' => [
                                    [
                                        'question' => '¿Se puede salir a la calle con el uniforme de planta?',
                                        'options' => [
                                            ['text' => 'Sí, solo un ratito',                    'correct' => false, 'explanation' => 'Contamina el uniforme con agentes externos.'],
                                            ['text' => 'No, nunca',                              'correct' => true,  'explanation' => 'Correcto: el uniforme solo debe usarse dentro de planta.'],
                                            ['text' => 'Sí, si está limpio',                     'correct' => false, 'explanation' => 'Al salir se contamina igual.'],
                                            ['text' => 'Solo al comedor externo',                'correct' => false, 'explanation' => 'El comedor debe ser parte del área autorizada.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Salud y hábitos',
                        'lessons' => [
                            [
                                'title' => 'Cuándo no debes manipular alimentos',
                                'content' => "# Tu salud importa (y la del consumidor)\n\nHay condiciones de salud en las que **no puedes manipular alimentos**. Reportarlas **no es debilidad**, es profesionalismo.\n\n## No debes entrar a planta si tienes:\n\n- 🤒 Fiebre, gripe fuerte, tos persistente.\n- 💧 Diarrea o vómitos en las últimas 24 horas.\n- 👁️ Conjuntivitis (ojo rojo).\n- 🩹 Heridas abiertas en manos o brazos sin cubrir.\n- 👄 Llagas visibles, lesiones en la piel.\n\n## ¿Qué hacer?\n\n1. **Avisa a tu supervisor** antes de entrar.\n2. Se te asignarán tareas sin contacto con producto, o descanso.\n3. Presenta tu **certificado médico** cuando sea necesario.\n\n## Heridas menores\n\nUn corte pequeño en la mano debe cubrirse con **curita impermeable de color (azul preferible)** y encima un guante. El azul se distingue fácilmente si cae al producto.\n\n> **Trabajar enfermo puede costar una contaminación masiva.**",
                                'quiz' => [
                                    [
                                        'question' => 'Si tuviste diarrea anoche, ¿puedes venir a trabajar hoy a planta?',
                                        'options' => [
                                            ['text' => 'Sí, si ya me siento mejor',             'correct' => false, 'explanation' => 'Puedes seguir eliminando bacterias peligrosas.'],
                                            ['text' => 'Debo avisar al supervisor y no manipular alimentos',   'correct' => true,  'explanation' => 'Correcto: debes esperar al menos 48h sin síntomas.'],
                                            ['text' => 'Sí, pero con doble mascarilla',          'correct' => false, 'explanation' => 'La mascarilla no detiene bacterias intestinales.'],
                                            ['text' => 'Sí, tomando pastillas',                   'correct' => false, 'explanation' => 'No es seguro para el producto.'],
                                        ],
                                    ],
                                    [
                                        'question' => '¿De qué color se recomienda una curita para manipulador?',
                                        'options' => [
                                            ['text' => 'Color piel',     'correct' => false, 'explanation' => 'Se camufla con el producto si se cae.'],
                                            ['text' => 'Azul',            'correct' => true,  'explanation' => 'Correcto: color que no existe en alimentos naturales.'],
                                            ['text' => 'Blanco',          'correct' => false, 'explanation' => 'Se confunde con los productos lácteos.'],
                                            ['text' => 'Transparente',    'correct' => false, 'explanation' => 'No se detecta si cae.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Limpieza y Desinfección de Equipos',
                'description' => 'Procedimientos CIP y SIP, elección de detergentes y desinfectantes, verificación de limpieza en planta láctea.',
                'area' => $bpm, 'template' => $tplBpm, 'duration_min' => 150,
                'modules' => [
                    [
                        'title' => 'Fundamentos de limpieza',
                        'lessons' => [
                            [
                                'title' => 'Diferencia entre limpieza y desinfección',
                                'content' => "# Limpiar no es lo mismo que desinfectar\n\nSon **dos pasos distintos y complementarios** en planta láctea.\n\n## Limpieza\n\nEs la **remoción física** de residuos visibles (grasa, proteína de leche, suero, restos de producto).\n\nSe realiza con **detergentes alcalinos** (remueven grasa) o **ácidos** (remueven sales minerales del agua y lactatos).\n\n## Desinfección\n\nEs la **reducción de microorganismos** a niveles seguros. Solo funciona sobre superficie **previamente limpia**.\n\nSe usan: ácido peracético, cloro, amonios cuaternarios, agua a 85°C.\n\n## Secuencia correcta (5 pasos)\n\n1. **Pre-enjuague** con agua (retira residuos gruesos).\n2. **Lavado** con detergente.\n3. **Enjuague intermedio**.\n4. **Desinfección**.\n5. **Enjuague final** (si el desinfectante lo requiere).\n\n> Desinfectar una superficie sucia **no sirve para nada**.",
                                'quiz' => [
                                    [
                                        'question' => '¿En qué orden se limpia y desinfecta un equipo?',
                                        'options' => [
                                            ['text' => 'Primero desinfectar, luego limpiar',        'correct' => false, 'explanation' => 'El desinfectante no penetra la suciedad.'],
                                            ['text' => 'Primero limpiar, luego desinfectar',        'correct' => true,  'explanation' => 'Correcto: la desinfección solo es efectiva en superficie limpia.'],
                                            ['text' => 'Son lo mismo, da igual el orden',            'correct' => false, 'explanation' => 'Son procesos distintos.'],
                                            ['text' => 'Solo desinfectar, no hace falta limpiar',    'correct' => false, 'explanation' => 'Insuficiente, los residuos protegen a las bacterias.'],
                                        ],
                                    ],
                                    [
                                        'question' => '¿Qué tipo de detergente remueve la grasa de la leche?',
                                        'options' => [
                                            ['text' => 'Ácido', 'correct' => false, 'explanation' => 'Los ácidos disuelven sales y lactatos.'],
                                            ['text' => 'Alcalino',  'correct' => true,  'explanation' => 'Correcto: los alcalinos (soda cáustica) remueven grasa.'],
                                            ['text' => 'Neutro',    'correct' => false, 'explanation' => 'No tiene poder de disolución fuerte.'],
                                            ['text' => 'Perfumado', 'correct' => false, 'explanation' => 'El aroma no limpia.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'CIP: limpieza sin desmontar equipos',
                        'lessons' => [
                            [
                                'title' => 'El sistema CIP en planta láctea',
                                'content' => "# Clean In Place (CIP)\n\nCIP significa **Clean In Place**: limpieza del equipo **sin desmontarlo**. Es el método estándar en tanques, tuberías y pasteurizadores.\n\n## Etapas típicas CIP\n\n1. **Pre-enjuague con agua tibia (40°C)** durante 5 min — arrastra leche residual.\n2. **Circulación de soda cáustica al 1-2% a 75-85°C** durante 15-20 min — remueve grasa y proteína.\n3. **Enjuague** con agua potable.\n4. **Circulación de ácido nítrico al 0.5-1% a 60°C** durante 10 min — elimina depósitos minerales.\n5. **Enjuague final** con agua potable.\n6. **Desinfección** con agua caliente (>85°C) o ácido peracético.\n\n## Parámetros críticos (las 4 T)\n\n- **Tiempo** de contacto.\n- **Temperatura** de la solución.\n- **Turbulencia** (velocidad del flujo: ≥1.5 m/s).\n- **Titulación** (concentración del producto químico).\n\n> Si falla uno de los 4, la limpieza **no es efectiva**.",
                                'quiz' => [
                                    [
                                        'question' => '¿Qué significan las 4 T del CIP?',
                                        'options' => [
                                            ['text' => 'Tiempo, Temperatura, Turbulencia, Titulación',  'correct' => true,  'explanation' => 'Correcto.'],
                                            ['text' => 'Tanque, Tubo, Trapo, Toalla',                    'correct' => false, 'explanation' => 'No tiene relación.'],
                                            ['text' => 'Tarde, Temprano, Total, Tranquilo',              'correct' => false, 'explanation' => 'No aplica.'],
                                            ['text' => 'Técnico, Taller, Teoría, Teoría',                'correct' => false, 'explanation' => 'No es el concepto.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Control de Plagas en Planta',
                'description' => 'Identificación de plagas comunes, programa MIP (Manejo Integrado de Plagas), prevención y uso responsable de cebos.',
                'area' => $bpm, 'template' => $tplBpm, 'duration_min' => 90,
                'modules' => [
                    [
                        'title' => 'Identificación de plagas',
                        'lessons' => [
                            [
                                'title' => 'Principales plagas en planta láctea',
                                'content' => "# Conoce tu enemigo\n\nLas plagas más comunes en planta láctea son:\n\n## Roedores\n\n- **Ratas noruega** (alcantarilla) — grandes, viven en sótanos y canaletas.\n- **Ratas de techo** — trepan, viven en cielos rasos.\n- **Ratones domésticos** — pequeños, se esconden en almacenes.\n\n**Señales**: excrementos, roeduras, huellas en harina, olor amoniacal.\n\n## Insectos voladores\n\n- Moscas (transmiten bacterias entre basura y producto).\n- Polillas (dañan almacenamiento de leche en polvo, azúcar).\n\n## Insectos rastreros\n\n- Cucarachas alemanas (ocultas en motores, grietas calientes).\n- Hormigas (buscan azúcar).\n\n## ¿Qué hacer si ves una?\n\n1. **No la elimines con insecticida en planta** — eso lo hace el operador especializado.\n2. Reporta al supervisor con detalle: hora, lugar, tipo.\n3. Registra el hallazgo en el **libro de reportes MIP**.",
                                'quiz' => [
                                    [
                                        'question' => 'Si encuentras excrementos de ratón cerca del almacén, ¿qué haces?',
                                        'options' => [
                                            ['text' => 'Los limpio y no digo nada',                  'correct' => false, 'explanation' => 'Ocultar una señal de plaga es grave.'],
                                            ['text' => 'Reporto al supervisor y dejo la evidencia',  'correct' => true,  'explanation' => 'Correcto: permite investigar ruta de la plaga.'],
                                            ['text' => 'Pongo veneno yo mismo',                       'correct' => false, 'explanation' => 'Solo el personal autorizado aplica plaguicidas.'],
                                            ['text' => 'Le tomo foto y la subo a redes',              'correct' => false, 'explanation' => 'No corresponde, perjudica a la empresa.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Prevención',
                        'lessons' => [
                            [
                                'title' => 'Las 3 barreras del MIP',
                                'content' => "# Manejo Integrado de Plagas\n\nEl MIP busca **prevenir antes que matar**. Se basa en 3 barreras:\n\n## 1. Barrera física\n\n- Mallas mosquiteras en ventanas.\n- Puertas con cortinas plásticas.\n- Sello de grietas en paredes y pisos.\n- Rejillas en desagües.\n\n## 2. Barrera operacional\n\n- Eliminar basura diariamente.\n- No dejar agua estancada.\n- Ordenar almacén en paletas (a 15 cm del piso y 50 cm de la pared).\n- Rotar stock (FIFO).\n\n## 3. Barrera química\n\n- **Solo por operador certificado MIP**.\n- Cebos en estaciones numeradas (fuera del área de producción).\n- Registro de cada aplicación.\n\n> La mejor plaga es la que **nunca entra**.",
                                'quiz' => [
                                    [
                                        'question' => '¿A qué distancia del piso y pared debe estar la mercadería almacenada?',
                                        'options' => [
                                            ['text' => '5 cm del piso y pegada a la pared',   'correct' => false, 'explanation' => 'Favorece anidamiento de plagas.'],
                                            ['text' => '15 cm del piso y 50 cm de la pared',   'correct' => true,  'explanation' => 'Correcto: permite inspección y limpieza.'],
                                            ['text' => 'Directamente en el piso',              'correct' => false, 'explanation' => 'Facilita humedad y plagas.'],
                                            ['text' => 'No importa la distancia',              'correct' => false, 'explanation' => 'La distancia es crítica.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // ---------------- Inocuidad y Calidad (4) ----------------
            [
                'name' => 'Análisis Fisicoquímico de Leche Cruda',
                'description' => 'Pruebas de acidez, densidad, grasa, pH y temperatura para determinar la aceptabilidad de la leche en recepción.',
                'area' => $ino, 'template' => $tplIno, 'duration_min' => 120,
                'modules' => [
                    [
                        'title' => 'Parámetros de calidad',
                        'lessons' => [
                            [
                                'title' => 'Acidez titulable de la leche',
                                'content' => "# Acidez en leche cruda\n\nLa **acidez titulable** indica qué tan fresca está la leche. Se mide en **grados Dornic (°D)** en el Perú.\n\n## Valores de referencia\n\n| Acidez (°D) | Interpretación |\n|-------------|----------------|\n| 14 – 18     | Leche fresca (aceptable) |\n| 19 – 20     | Leche al límite |\n| > 20        | Leche alterada (rechazar) |\n\n## ¿Cómo se mide?\n\n1. Tomar **10 mL de leche** en vaso de vidrio.\n2. Agregar **3 gotas de fenolftaleína**.\n3. Titular con **solución de NaOH 0.1 N** gota a gota agitando suavemente.\n4. Detener cuando vire a **rosa pálido persistente** por 10 segundos.\n5. Leer el volumen gastado (mL) × 10 = °Dornic.\n\n## Causas de acidez alta\n\n- Leche con muchas horas sin enfriar.\n- Mastitis en el ganado.\n- Mala higiene en ordeño y transporte.\n\n> Nunca mezcles una leche alta en acidez con una sana: se arruina todo el lote.",
                                'quiz' => [
                                    [
                                        'question' => '¿Cuál es el rango aceptable de acidez (°D) en leche cruda fresca?',
                                        'options' => [
                                            ['text' => '14 a 18 °D',   'correct' => true,  'explanation' => 'Correcto, rango de leche fresca.'],
                                            ['text' => '25 a 30 °D',    'correct' => false, 'explanation' => 'Leche alterada.'],
                                            ['text' => '5 a 10 °D',     'correct' => false, 'explanation' => 'Valor imposible en leche natural.'],
                                            ['text' => '40 a 50 °D',    'correct' => false, 'explanation' => 'Leche ya cortada.'],
                                        ],
                                    ],
                                    [
                                        'question' => '¿Qué indicador se usa al titular la acidez?',
                                        'options' => [
                                            ['text' => 'Fenolftaleína',    'correct' => true,  'explanation' => 'Vira a rosa en medio básico.'],
                                            ['text' => 'Yodo',              'correct' => false, 'explanation' => 'Se usa para almidón.'],
                                            ['text' => 'Anaranjado de metilo','correct' => false, 'explanation' => 'No aplica al rango de pH.'],
                                            ['text' => 'Tornasol',           'correct' => false, 'explanation' => 'No es preciso para titulación.'],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Densidad y grasa',
                                'content' => "# Densidad y grasa de la leche\n\n## Densidad\n\nSe mide con **lactodensímetro** a **15 °C**. Valores normales: **1.028 – 1.034 g/mL**.\n\n- Densidad **baja** → posible aguado.\n- Densidad **alta** → posible descremado.\n\n## Grasa\n\nLa leche de vaca tiene **3.0 – 4.5% de grasa**. Se mide con el **método Gerber** (butirómetro) usando ácido sulfúrico y alcohol isoamílico.\n\n## Uso combinado\n\nLa combinación de densidad + grasa + proteína permite detectar fraudes como **adulteración con agua o descremado**.\n\n> Una leche \"aguada\" pierde valor y no cuaja bien para queso.",
                                'quiz' => [
                                    [
                                        'question' => '¿Qué instrumento se usa para medir la densidad de la leche?',
                                        'options' => [
                                            ['text' => 'Lactodensímetro', 'correct' => true,  'explanation' => 'Correcto, es un densímetro calibrado para leche.'],
                                            ['text' => 'pH-metro',         'correct' => false, 'explanation' => 'Mide acidez, no densidad.'],
                                            ['text' => 'Termómetro',        'correct' => false, 'explanation' => 'Mide temperatura.'],
                                            ['text' => 'Balanza',           'correct' => false, 'explanation' => 'Mide masa, no densidad directamente.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Análisis Microbiológico Básico en Planta',
                'description' => 'Pruebas rápidas de calidad microbiológica: TRAM, conteo por placa, detección de antibióticos.',
                'area' => $ino, 'template' => $tplIno, 'duration_min' => 100,
                'modules' => [
                    [
                        'title' => 'Pruebas rápidas',
                        'lessons' => [
                            [
                                'title' => 'Prueba de reductasa (TRAM)',
                                'content' => "# Tiempo de Reducción del Azul de Metileno (TRAM)\n\nLa prueba TRAM estima la **carga microbiana** de la leche cruda de forma rápida y barata.\n\n## Fundamento\n\nLas bacterias consumen oxígeno y reducen el azul de metileno → se decolora.\n\n**Mientras más rápido se decolora, peor es la calidad microbiana.**\n\n## Protocolo\n\n1. 10 mL de leche + 1 mL de solución de azul de metileno.\n2. Incubar a **37 °C** en baño maría.\n3. Observar cada 30 minutos.\n\n## Interpretación\n\n| Tiempo de decoloración | Calidad |\n|-----------------------|---------|\n| > 5 horas             | Muy buena |\n| 3 – 5 horas           | Buena |\n| 1 – 3 horas           | Regular |\n| < 1 hora              | Mala (rechazar) |\n\n> Esta prueba es **solo orientativa**; para certificación se usa conteo en placa.",
                                'quiz' => [
                                    [
                                        'question' => '¿Qué significa que el azul de metileno se decolore en menos de 1 hora?',
                                        'options' => [
                                            ['text' => 'Leche de muy buena calidad',    'correct' => false, 'explanation' => 'Al revés: indica alta carga bacteriana.'],
                                            ['text' => 'Leche de mala calidad',          'correct' => true,  'explanation' => 'Correcto: muchas bacterias reducen rápido el colorante.'],
                                            ['text' => 'Leche adulterada con agua',      'correct' => false, 'explanation' => 'No es lo que detecta esta prueba.'],
                                            ['text' => 'Leche muy grasa',                'correct' => false, 'explanation' => 'La grasa no afecta esta prueba.'],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Detección de antibióticos (inhibidores)',
                                'content' => "# Antibióticos en la leche\n\nLa leche con **residuos de antibióticos** (por vacas tratadas por mastitis) **no debe ingresar a planta**: inhibe la fermentación y pone en riesgo al consumidor.\n\n## Prueba rápida\n\nSe usan **kits tipo Delvotest, Beta-Star o Copan**. Funcionan con bacterias sensibles: si no crecen, hay antibióticos.\n\n## ¿Qué hacer si sale positivo?\n\n1. **Rechazar el lote completo** del proveedor.\n2. Registrar en hoja de recepción con observación.\n3. Comunicar al proveedor para retroalimentación.\n4. No mezclar bajo ninguna circunstancia con leche sana.\n\n## ¿Por qué es tan grave?\n\n- Consumidores alérgicos pueden sufrir **reacciones severas**.\n- Arruina fermentos para yogurt y queso.\n- **DIGESA sanciona** con cierres y multas.\n\n> Una sola leche con antibiótico **arruina un tanque entero**.",
                                'quiz' => [
                                    [
                                        'question' => 'Si la leche de un proveedor da positivo a antibióticos, ¿qué haces?',
                                        'options' => [
                                            ['text' => 'La mezclo para diluir',              'correct' => false, 'explanation' => 'Diluir no elimina antibióticos.'],
                                            ['text' => 'Rechazo el lote completo',           'correct' => true,  'explanation' => 'Correcto: no hay forma segura de procesarla.'],
                                            ['text' => 'La envío a yogurt',                   'correct' => false, 'explanation' => 'Inhibe el fermento del yogurt.'],
                                            ['text' => 'La dejo reposar y la uso mañana',     'correct' => false, 'explanation' => 'El antibiótico no desaparece con tiempo.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            [
                'name' => 'HACCP aplicado a Productos Lácteos',
                'description' => 'Sistema HACCP: análisis de peligros, puntos críticos de control, límites y monitoreo aplicado a queso y yogurt.',
                'area' => $ino, 'template' => $tplHaccp, 'duration_min' => 240,
                'modules' => [
                    [
                        'title' => 'Fundamentos HACCP',
                        'lessons' => [
                            [
                                'title' => 'Los 7 principios HACCP',
                                'content' => "# Hazard Analysis and Critical Control Points\n\nEl sistema HACCP es un enfoque **preventivo** para asegurar inocuidad. Consta de **7 principios**:\n\n1. **Análisis de peligros**: identificar todo peligro biológico, químico o físico.\n2. **Determinar los PCC** (Puntos Críticos de Control).\n3. **Establecer límites críticos** (ej. temperatura 72°C por 15 s en pasteurización).\n4. **Monitoreo** continuo de cada PCC.\n5. **Acciones correctivas** cuando se desvía un límite.\n6. **Verificación** del sistema.\n7. **Documentación y registros**.\n\n## Aplicación en lácteos\n\nLos **PCC más comunes** en planta de queso/yogurt son:\n\n- **Pasteurización** (temperatura y tiempo).\n- **Enfriamiento** (< 4 °C en < 2 horas).\n- **Detección de metales** en envasado (si hay detector).\n\n> Un **PCC perdido** = producto inseguro que **no debe salir** al mercado.",
                                'quiz' => [
                                    [
                                        'question' => '¿Qué significa PCC en HACCP?',
                                        'options' => [
                                            ['text' => 'Punto Crítico de Control',      'correct' => true,  'explanation' => 'Correcto.'],
                                            ['text' => 'Proceso Continuo de Control',    'correct' => false, 'explanation' => 'No es el concepto.'],
                                            ['text' => 'Personal Calificado en Calidad','correct' => false, 'explanation' => 'No aplica.'],
                                            ['text' => 'Paquete Completo de Control',    'correct' => false, 'explanation' => 'No aplica.'],
                                        ],
                                    ],
                                    [
                                        'question' => '¿Cuál es el límite crítico típico de pasteurización HTST?',
                                        'options' => [
                                            ['text' => '63°C por 30 min',    'correct' => false, 'explanation' => 'Ese es LTLT (baja temperatura).'],
                                            ['text' => '72°C por 15 segundos','correct' => true,  'explanation' => 'Correcto: HTST estándar.'],
                                            ['text' => '100°C por 1 minuto',  'correct' => false, 'explanation' => 'Esterilización, no pasteurización.'],
                                            ['text' => '40°C por 1 hora',     'correct' => false, 'explanation' => 'Insuficiente.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Peligros en lácteos',
                        'lessons' => [
                            [
                                'title' => 'Peligros biológicos, químicos y físicos',
                                'content' => "# Tres tipos de peligros\n\n## Biológicos\n\n- **Salmonella spp.** — cuadros gastrointestinales graves.\n- **Listeria monocytogenes** — aborto en gestantes, mortal en inmunosuprimidos.\n- **E. coli O157:H7** — síndrome urémico hemolítico.\n- **Staphylococcus aureus** — toxina termoestable.\n\n## Químicos\n\n- Residuos de **antibióticos**.\n- **Detergentes o desinfectantes** mal enjuagados.\n- **Aflatoxinas M1** (por alimentación del ganado con maíz contaminado).\n\n## Físicos\n\n- Fragmentos de vidrio, metal, plástico.\n- Pelos, uñas, joyas.\n- Tierra o piedras.\n\n## Barreras clave\n\n| Peligro | Barrera principal |\n|---------|-------------------|\n| Biológico | Pasteurización + enfriamiento |\n| Químico | Control de proveedores + enjuague post-CIP |\n| Físico | Tamices, imanes, detectores de metales |\n\n> Cada producto terminado **pasa por las tres barreras** antes de salir.",
                                'quiz' => [
                                    [
                                        'question' => 'La Listeria monocytogenes es especialmente peligrosa en:',
                                        'options' => [
                                            ['text' => 'Adultos sanos',                              'correct' => false, 'explanation' => 'Les causa síntomas leves.'],
                                            ['text' => 'Gestantes e inmunosuprimidos',               'correct' => true,  'explanation' => 'Correcto: puede causar aborto y meningitis.'],
                                            ['text' => 'Solo adultos mayores sin riesgo',            'correct' => false, 'explanation' => 'Todos son susceptibles pero unos más que otros.'],
                                            ['text' => 'Animales solamente',                          'correct' => false, 'explanation' => 'También afecta a humanos.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Trazabilidad y Control de Lote',
                'description' => 'Cómo armar y documentar un lote desde la leche cruda hasta el producto terminado. Sistema de trazabilidad hacia adelante y hacia atrás.',
                'area' => $ino, 'template' => $tplHaccp, 'duration_min' => 90,
                'modules' => [
                    [
                        'title' => 'Qué es la trazabilidad',
                        'lessons' => [
                            [
                                'title' => 'Trazabilidad hacia adelante y hacia atrás',
                                'content' => "# Trazabilidad\n\nEs la capacidad de **seguir un producto** desde el origen (leche de un proveedor) hasta el consumidor final, y viceversa.\n\n## Tipos\n\n### Hacia atrás (backward)\n\nDado un **lote terminado**, debemos saber:\n- Qué leche se usó (proveedores y día de recepción).\n- Qué insumos (cuajo, sal, cultivos, fecha de vencimiento).\n- Qué operarios y equipos.\n\n### Hacia adelante (forward)\n\nDado un **lote**, debemos saber:\n- A qué clientes se vendió.\n- En qué cantidades y fechas.\n- Cómo contactarlos en caso de retiro (recall).\n\n## ¿Para qué sirve?\n\n- **Recall** (retiro del mercado) en caso de contaminación.\n- Reclamos de consumidores.\n- Auditorías de DIGESA.\n- Análisis de causa raíz en fallas.\n\n> Sin trazabilidad, **un problema pequeño se vuelve un desastre grande**.",
                                'quiz' => [
                                    [
                                        'question' => 'Un cliente reporta enfermedad tras consumir nuestro queso. ¿Qué trazabilidad usamos?',
                                        'options' => [
                                            ['text' => 'Hacia adelante (forward)',   'correct' => false, 'explanation' => 'Eso localiza a otros clientes.'],
                                            ['text' => 'Hacia atrás (backward)',      'correct' => true,  'explanation' => 'Correcto: busca causa en leche/insumos/operario.'],
                                            ['text' => 'Ninguna',                      'correct' => false, 'explanation' => 'Es la peor respuesta ante un problema.'],
                                            ['text' => 'Solo preguntar al cliente',   'correct' => false, 'explanation' => 'Insuficiente como respuesta empresarial.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // ---------------- Seguridad y Salud (3) ----------------
            [
                'name' => 'Uso Correcto de EPP en Planta',
                'description' => 'Selección, uso y cuidado de los Equipos de Protección Personal en planta láctea.',
                'area' => $seg, 'template' => $tplSeg, 'duration_min' => 60,
                'modules' => [
                    [
                        'title' => 'EPP básico',
                        'lessons' => [
                            [
                                'title' => 'Elementos obligatorios y su uso',
                                'content' => "# Equipos de Protección Personal (EPP)\n\nTe protegen **a ti primero**. Sin EPP no deberías ingresar a zona de producción.\n\n## EPP mínimo\n\n| Zona | EPP requerido |\n|------|---------------|\n| Recepción de leche | Botas PVC, mandil, cofia |\n| Pasteurización | + Guantes térmicos |\n| Envasado | + Mascarilla |\n| Limpieza CIP | + Gafas, guantes químicos, respirador |\n| Almacén frío | Chaqueta térmica |\n\n## Reglas\n\n- El EPP **debe estar limpio** y en buen estado.\n- Si está dañado, **reporta y cambia**, no lo uses a medias.\n- Las **gafas rayadas** o mascarillas sucias **NO protegen**.\n- El EPP personal **no se presta**.\n\n## Cuidado\n\nEl EPP lo entrega la empresa. Tú eres responsable de:\n- Usarlo correctamente.\n- Mantenerlo limpio.\n- Reportar desgaste.\n- Devolverlo al retirarse.\n\n> Un EPP **bien usado** es barato. Un accidente **es carísimo**.",
                                'quiz' => [
                                    [
                                        'question' => '¿Qué EPP adicional se requiere para hacer CIP con soda cáustica?',
                                        'options' => [
                                            ['text' => 'Solo mandil',                         'correct' => false, 'explanation' => 'Insuficiente ante químicos corrosivos.'],
                                            ['text' => 'Gafas, guantes químicos y respirador','correct' => true,  'explanation' => 'Correcto: soda cáustica es corrosiva.'],
                                            ['text' => 'Cascos',                              'correct' => false, 'explanation' => 'No protege contra químicos.'],
                                            ['text' => 'Ninguno, soy cuidadoso',              'correct' => false, 'explanation' => 'Un descuido puede quemar piel y ojos.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Prevención de Accidentes Laborales',
                'description' => 'Identificación de riesgos, uso seguro de máquinas, ergonomía y levantamiento de cargas en planta láctea.',
                'area' => $seg, 'template' => $tplSeg, 'duration_min' => 90,
                'modules' => [
                    [
                        'title' => 'Riesgos comunes',
                        'lessons' => [
                            [
                                'title' => 'Riesgos por quemaduras y caídas',
                                'content' => "# Riesgos típicos en planta láctea\n\n## Quemaduras\n\n- Vapor en pasteurizador (>90°C).\n- Agua caliente en CIP.\n- Piso caliente junto a calderas.\n\n**Prevención**: guantes térmicos, avisos visuales, no abrir válvulas de vapor bruscamente.\n\n## Caídas y resbalones\n\n- Pisos mojados con leche o detergente.\n- Escaleras de acceso a tanques.\n\n**Prevención**:\n- Botas con **suela antideslizante**.\n- Limpiar derrames **inmediatamente**.\n- Señalizar con conos \"Piso mojado\".\n- No correr en planta.\n\n## Atrapamiento\n\n- Máquinas con bandas (llenadoras, etiquetadoras).\n- Agitadores de tanques.\n\n**Prevención**: nunca introducir mano en máquina en marcha, usar **paro de emergencia** al limpiar, respetar bloqueos (lockout).\n\n> Los accidentes **no son mala suerte**, son falta de prevención.",
                                'quiz' => [
                                    [
                                        'question' => 'Ves leche derramada en el piso. ¿Qué haces?',
                                        'options' => [
                                            ['text' => 'Paso con cuidado y sigo trabajando',      'correct' => false, 'explanation' => 'Riesgo de caída para ti y los demás.'],
                                            ['text' => 'Limpio de inmediato y coloco señal',      'correct' => true,  'explanation' => 'Correcto: prevenir antes que lamentar.'],
                                            ['text' => 'Espero al personal de limpieza',           'correct' => false, 'explanation' => 'Mientras tanto alguien se puede caer.'],
                                            ['text' => 'Le tomo foto para reportar',               'correct' => false, 'explanation' => 'Primero se resuelve.'],
                                        ],
                                    ],
                                ],
                            ],
                            [
                                'title' => 'Levantamiento seguro de cargas',
                                'content' => "# Levanta con las piernas, no con la espalda\n\nBaldes de leche, bolsas de leche en polvo, sacos de azúcar o bidones de insumos son **cargas pesadas**.\n\n## Técnica correcta\n\n1. **Acércate** al objeto.\n2. Abre los pies **a la altura de los hombros**.\n3. **Flexiona las rodillas**, mantén la espalda recta.\n4. **Agarra firme** con ambas manos.\n5. Levanta usando la **fuerza de las piernas**, no la espalda.\n6. Mantén la carga **cerca del cuerpo**.\n7. **No gires** con la carga: mueve primero los pies.\n\n## Límites orientativos\n\n- Hombres adultos: **25 kg** máximo individual.\n- Mujeres adultas: **15 kg** máximo individual.\n- Cargas mayores → **dos personas o montacargas**.\n\n## Lesiones comunes\n\n- **Lumbalgias** (dolor de espalda baja).\n- **Hernia discal** (grave, cirugía).\n- Rotura muscular.\n\n> Tu espalda es tu herramienta más importante: **cuídala hoy para caminar mañana**.",
                                'quiz' => [
                                    [
                                        'question' => '¿Cómo se levanta correctamente una carga pesada?',
                                        'options' => [
                                            ['text' => 'Con las piernas y espalda recta',    'correct' => true,  'explanation' => 'Correcto: protege la columna.'],
                                            ['text' => 'Doblando la cintura',                 'correct' => false, 'explanation' => 'Genera lesiones lumbares.'],
                                            ['text' => 'Con un brazo solo',                   'correct' => false, 'explanation' => 'Desbalancea y causa lesiones.'],
                                            ['text' => 'Lo más rápido posible',                'correct' => false, 'explanation' => 'La prisa causa accidentes.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Primeros Auxilios en Planta',
                'description' => 'Respuesta inmediata ante quemaduras, cortes, asfixia y emergencias comunes en planta láctea.',
                'area' => $seg, 'template' => $tplSeg, 'duration_min' => 75,
                'modules' => [
                    [
                        'title' => 'Respuesta inmediata',
                        'lessons' => [
                            [
                                'title' => 'Quemaduras por vapor y agua caliente',
                                'content' => "# Quemaduras en planta\n\nLas más frecuentes vienen de vapor, agua caliente o tuberías del pasteurizador.\n\n## Acción inmediata (regla de los 3 C)\n\n1. **Cortar** la fuente de calor.\n2. **Colocar agua fría** (no helada) en la zona durante **10-20 minutos**.\n3. **Cubrir** con gasa estéril o paño limpio.\n\n## NO hacer\n\n- ❌ No aplicar hielo directo (agrava).\n- ❌ No romper ampollas.\n- ❌ No aplicar pasta de dientes, aceite ni pomadas caseras.\n- ❌ No quitar ropa pegada a la quemadura.\n\n## Cuándo llamar a emergencias\n\n- Quemadura más grande que la palma de la mano.\n- Quemadura en cara, manos, genitales o articulaciones.\n- Persona confundida, con dificultad para respirar.\n\n> Toda quemadura debe **reportarse** aunque parezca leve.",
                                'quiz' => [
                                    [
                                        'question' => 'Te quemaste la mano con vapor. ¿Qué haces primero?',
                                        'options' => [
                                            ['text' => 'Pongo hielo',                       'correct' => false, 'explanation' => 'El hielo agrava el daño tisular.'],
                                            ['text' => 'Agua fría corriente 10-20 minutos', 'correct' => true,  'explanation' => 'Correcto: reduce daño térmico.'],
                                            ['text' => 'Pasta de dientes',                   'correct' => false, 'explanation' => 'Mito, puede infectar.'],
                                            ['text' => 'Reviento las ampollas',              'correct' => false, 'explanation' => 'Expone a infección.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // ---------------- Operaciones de Planta (4) ----------------
            [
                'name' => 'Recepción y Acopio de Leche Fresca',
                'description' => 'Recepción correcta de leche cruda: inspección sensorial, temperaturas, muestreo y registro.',
                'area' => $ope, 'template' => $tplOpe, 'duration_min' => 120,
                'modules' => [
                    [
                        'title' => 'Protocolo de recepción',
                        'lessons' => [
                            [
                                'title' => 'Pasos obligatorios al recibir leche',
                                'content' => "# Protocolo de recepción\n\n## Paso 1 — Inspección sensorial\n\n- **Color**: blanco a marfil.\n- **Olor**: ligeramente dulce, sin olor a establo ni rancio.\n- **Aspecto**: líquido homogéneo sin grumos.\n\n## Paso 2 — Medición de temperatura\n\nLeche aceptable: **≤ 6 °C** (ideal ≤ 4 °C).\n\nSi viene a **>10 °C**, evalúa acidez y reporta al supervisor.\n\n## Paso 3 — Pruebas rápidas\n\n- Acidez titulable (< 18 °D).\n- Prueba del **alcohol al 68%** → negativa (no cuajar).\n- TRAM o antibióticos según protocolo.\n\n## Paso 4 — Medir volumen\n\nCon **medidor volumétrico calibrado** o pesando el bidón (tara previa).\n\n## Paso 5 — Registrar en hoja de recepción\n\n- Proveedor, fecha, hora.\n- Volumen aceptado / rechazado.\n- Resultados de pruebas.\n- Tu firma como operario.\n\n> El registro es la base de la **trazabilidad**.",
                                'quiz' => [
                                    [
                                        'question' => '¿Cuál es la temperatura máxima aceptable para leche cruda en recepción?',
                                        'options' => [
                                            ['text' => '15 °C',    'correct' => false, 'explanation' => 'Muy alto, hay proliferación bacteriana.'],
                                            ['text' => '6 °C',      'correct' => true,  'explanation' => 'Correcto: ideal ≤4°C, aceptable ≤6°C.'],
                                            ['text' => '20 °C',     'correct' => false, 'explanation' => 'Leche alterada, rechazar.'],
                                            ['text' => '30 °C',     'correct' => false, 'explanation' => 'Temperatura ambiente, totalmente inaceptable.'],
                                        ],
                                    ],
                                    [
                                        'question' => 'La prueba del alcohol al 68% sale positiva (cuaja). ¿Qué significa?',
                                        'options' => [
                                            ['text' => 'La leche es buena',                'correct' => false, 'explanation' => 'Al revés: ya tiene acidez elevada.'],
                                            ['text' => 'La leche es inestable/mal estado','correct' => true,  'explanation' => 'Correcto, no resiste pasteurización.'],
                                            ['text' => 'No significa nada',                 'correct' => false, 'explanation' => 'Es indicativo de estabilidad.'],
                                            ['text' => 'Tiene mucha grasa',                'correct' => false, 'explanation' => 'No mide grasa.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Pasteurización de Leche para Lácteos',
                'description' => 'Principios de la pasteurización HTST y LTLT, control de temperatura y tiempo, verificación de fallas.',
                'area' => $ope, 'template' => $tplOpe, 'duration_min' => 150,
                'modules' => [
                    [
                        'title' => 'Fundamentos',
                        'lessons' => [
                            [
                                'title' => '¿Qué es pasteurizar?',
                                'content' => "# Pasteurización\n\nEs un **tratamiento térmico** que destruye microorganismos patógenos **sin destruir** las propiedades nutricionales principales.\n\n## Tipos\n\n### LTLT (Low Temperature Long Time)\n\n- **63°C durante 30 minutos**.\n- Usado en plantas pequeñas, artesanales.\n- Equipo: marmita con agitación.\n\n### HTST (High Temperature Short Time)\n\n- **72°C durante 15 segundos**.\n- Continuo, en intercambiador de placas.\n- Más eficiente para plantas medianas/grandes.\n\n### UHT (Ultra High Temperature)\n\n- **135-150 °C durante 2-5 segundos**.\n- Leche de larga duración (6 meses sin refrigeración).\n\n## ¿Qué destruye?\n\n- Mycobacterium bovis (tuberculosis).\n- Brucella.\n- Salmonella.\n- E. coli patógena.\n- Listeria (al límite, por eso refrigerar rápido).\n\n## Lo que NO destruye\n\n- Esporas (por eso pasteurizada no es estéril).\n- Toxinas ya formadas.\n\n> Pasteurizar es **la barrera más importante** en planta láctea.",
                                'quiz' => [
                                    [
                                        'question' => '¿Cuál es el binomio HTST más usado?',
                                        'options' => [
                                            ['text' => '63°C / 30 min',     'correct' => false, 'explanation' => 'Ese es LTLT.'],
                                            ['text' => '72°C / 15 s',        'correct' => true,  'explanation' => 'Correcto: HTST estándar.'],
                                            ['text' => '100°C / 2 s',        'correct' => false, 'explanation' => 'No es pasteurización.'],
                                            ['text' => '150°C / 5 s',        'correct' => false, 'explanation' => 'Ese es UHT.'],
                                        ],
                                    ],
                                    [
                                        'question' => '¿La pasteurización esteriliza la leche?',
                                        'options' => [
                                            ['text' => 'Sí, elimina todo',                      'correct' => false, 'explanation' => 'No destruye esporas.'],
                                            ['text' => 'No, elimina patógenos pero no esporas', 'correct' => true,  'explanation' => 'Correcto, por eso se refrigera después.'],
                                            ['text' => 'Solo destruye virus',                    'correct' => false, 'explanation' => 'Destruye principalmente bacterias patógenas.'],
                                            ['text' => 'Nunca se hace',                          'correct' => false, 'explanation' => 'Es un proceso obligatorio.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'title' => 'Control del proceso',
                        'lessons' => [
                            [
                                'title' => 'Registro y alarmas del pasteurizador',
                                'content' => "# Monitoreo del pasteurizador\n\n## Parámetros a registrar cada turno\n\n- **Temperatura de salida** (debe alcanzar el binomio).\n- **Tiempo de permanencia** en tubo de retención.\n- **Presión** diferencial (producto > agua, para evitar contaminación cruzada).\n- **Desvío automático** (diversion valve) activo.\n\n## Válvula de desvío\n\nSi la temperatura baja del límite, la leche **no pasteurizada regresa** automáticamente al tanque de alimentación. Es una protección crítica.\n\n## ¿Qué hacer si suena la alarma?\n\n1. **No la silencies** sin investigar.\n2. Verifica temperatura y flujo.\n3. Si hay desvío, detén alimentación y revisa sensores.\n4. Reporta al supervisor e inicia **acción correctiva**.\n5. La leche desviada **no es producto terminado**: se re-pasteuriza.\n\n> Ignorar una alarma = **producto inseguro al mercado**.",
                                'quiz' => [
                                    [
                                        'question' => '¿Qué hace la válvula de desvío en un pasteurizador?',
                                        'options' => [
                                            ['text' => 'Desvía leche cruda cuando no alcanza temperatura',  'correct' => true,  'explanation' => 'Correcto: protección automática.'],
                                            ['text' => 'Purga vapor excedente',                             'correct' => false, 'explanation' => 'No es su función.'],
                                            ['text' => 'Mezcla agua y leche',                                'correct' => false, 'explanation' => 'No hace mezclas.'],
                                            ['text' => 'Es solo decorativa',                                 'correct' => false, 'explanation' => 'Es crítica para inocuidad.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Elaboración de Queso Fresco Artesanal',
                'description' => 'Receta y proceso paso a paso de queso fresco: acidificación, coagulación, corte, moldeo y salado.',
                'area' => $ope, 'template' => $tplOpe, 'duration_min' => 180,
                'modules' => [
                    [
                        'title' => 'Proceso paso a paso',
                        'lessons' => [
                            [
                                'title' => 'Coagulación y corte de cuajada',
                                'content' => "# Queso fresco artesanal\n\n## Ingredientes para 100 L de leche\n\n- Leche pasteurizada: 100 L\n- Cloruro de calcio al 40%: 20 mL\n- Cuajo líquido: 15-20 mL (según instrucciones)\n- Sal: al gusto (salmuera o salado en seco)\n\n## Paso 1 — Preparar la leche\n\n1. Pasteurizar a 65°C por 30 min (LTLT) o recibir de HTST.\n2. Enfriar a **35-37°C**.\n3. Agregar **cloruro de calcio** y disolver.\n\n## Paso 2 — Agregar el cuajo\n\n1. Diluir el cuajo en poca agua fría.\n2. Agregar mientras se **agita suavemente** por 1-2 minutos.\n3. Detener agitación y **esperar 30-40 minutos sin mover**.\n\n## Paso 3 — Corte de cuajada\n\nComprueba firmeza con un **dedo limpio o cuchillo**: debe cortar neto y soltar suero limpio.\n\nCorta con lira o cuchillo en **cubos de 1-2 cm**. Deja reposar 5 min.\n\n## Paso 4 — Desuerar\n\nAgita suavemente, retira el **suero** con cedazo o malla. Escurre.\n\n## Paso 5 — Moldear y prensar\n\nColoca la cuajada en moldes con tela. Prensa con peso durante 1-2 horas.\n\n## Paso 6 — Salar\n\n- **Salmuera**: salmuera al 18-20% por 4-6 horas.\n- **En seco**: 2% del peso del queso, restregando.\n\n## Paso 7 — Refrigerar\n\nRefrigerar a **4°C**. Listo para consumo en 24 h, duración 15 días en frío.",
                                'quiz' => [
                                    [
                                        'question' => '¿A qué temperatura se agrega el cuajo?',
                                        'options' => [
                                            ['text' => '35-37 °C',   'correct' => true,  'explanation' => 'Correcto, es el óptimo enzimático.'],
                                            ['text' => '15-20 °C',   'correct' => false, 'explanation' => 'Muy baja, cuajo poco activo.'],
                                            ['text' => '70-80 °C',   'correct' => false, 'explanation' => 'Desnaturaliza la enzima.'],
                                            ['text' => '100 °C',     'correct' => false, 'explanation' => 'Hierve la leche.'],
                                        ],
                                    ],
                                    [
                                        'question' => '¿Qué tamaño de corte se da a la cuajada para queso fresco?',
                                        'options' => [
                                            ['text' => '5 mm',        'correct' => false, 'explanation' => 'Muy fino, pierde rendimiento.'],
                                            ['text' => '1-2 cm',       'correct' => true,  'explanation' => 'Correcto para desuerado moderado.'],
                                            ['text' => '5-10 cm',      'correct' => false, 'explanation' => 'Muy grueso, no desuera bien.'],
                                            ['text' => 'Sin cortar',   'correct' => false, 'explanation' => 'Necesita corte para desuerar.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Elaboración de Yogurt Natural',
                'description' => 'Proceso del yogurt: estandarización, inoculación, incubación y enfriamiento. Control de fermentos.',
                'area' => $ope, 'template' => $tplOpe, 'duration_min' => 120,
                'modules' => [
                    [
                        'title' => 'Proceso del yogurt',
                        'lessons' => [
                            [
                                'title' => 'Inoculación e incubación',
                                'content' => "# Yogurt natural batido\n\n## Fermentos\n\nEl yogurt se obtiene por fermentación con dos bacterias:\n\n- *Lactobacillus delbrueckii subsp. bulgaricus*\n- *Streptococcus thermophilus*\n\nAmbas son **simbióticas**: juntas producen más ácido láctico que por separado.\n\n## Proceso\n\n1. **Estandarización**: llevar la leche a 3% grasa y 12% sólidos (agregar leche en polvo).\n2. **Homogenización** (opcional, evita separación).\n3. **Pasteurización**: 85-90°C por 5-10 minutos (más intenso que HTST).\n4. **Enfriar** a 42-43°C.\n5. **Inocular** con 2-3% de fermento activo.\n6. **Incubar** en reposo a **42-43 °C durante 4-5 horas**.\n7. Verificar **pH entre 4.5 y 4.6** o **acidez 80-85 °D** → cortar fermentación.\n8. **Enfriar rápidamente a <10 °C**.\n9. **Batir** (para yogurt batido) y envasar.\n\n## Controles críticos\n\n- Temperatura de incubación (si baja, no fermenta).\n- Fermento activo (fechas, almacenamiento a 4°C).\n- Enfriamiento rápido (evita sobrefermentación y acidez excesiva).",
                                'quiz' => [
                                    [
                                        'question' => '¿A qué temperatura se incuba el yogurt?',
                                        'options' => [
                                            ['text' => '20-25 °C',  'correct' => false, 'explanation' => 'Muy baja, no fermenta bien.'],
                                            ['text' => '42-43 °C',   'correct' => true,  'explanation' => 'Correcto, óptimo de las bacterias.'],
                                            ['text' => '80 °C',      'correct' => false, 'explanation' => 'Mata las bacterias.'],
                                            ['text' => '10 °C',      'correct' => false, 'explanation' => 'Temperatura de refrigeración, no fermenta.'],
                                        ],
                                    ],
                                    [
                                        'question' => '¿Qué pH final se busca en el yogurt?',
                                        'options' => [
                                            ['text' => 'Entre 6.0 y 6.5',  'correct' => false, 'explanation' => 'Muy alto, aún no cuaja.'],
                                            ['text' => 'Entre 4.5 y 4.6',   'correct' => true,  'explanation' => 'Correcto, el yogurt coagula.'],
                                            ['text' => 'Entre 3.0 y 3.5',   'correct' => false, 'explanation' => 'Demasiado ácido.'],
                                            ['text' => 'Entre 7.0 y 7.5',   'correct' => false, 'explanation' => 'Básico, imposible.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // ---------------- Gestión Administrativa (2) ----------------
            [
                'name' => 'Control de Inventarios en Planta Láctea',
                'description' => 'Manejo de inventarios FIFO, toma física, control de mermas, registro de insumos y productos terminados.',
                'area' => $adm, 'template' => $tplAdm, 'duration_min' => 100,
                'modules' => [
                    [
                        'title' => 'Principios de inventario',
                        'lessons' => [
                            [
                                'title' => 'FIFO, FEFO y kárdex',
                                'content' => "# Control de inventarios\n\n## Reglas de rotación\n\n- **FIFO** (First In, First Out): lo primero que entra, primero que sale. Usado para insumos secos.\n- **FEFO** (First Expired, First Out): lo primero que vence, primero que sale. Usado para **productos perecederos** como leche, fermentos, quesos.\n\nEn planta láctea **el FEFO es prioritario**, porque la fecha de vencimiento manda.\n\n## Kárdex\n\nRegistro de entradas, salidas y saldos de cada insumo o producto. Debe incluir:\n\n- Fecha y hora del movimiento.\n- Cantidad entrante o saliente.\n- Motivo (producción, venta, merma, devolución).\n- Operario responsable.\n- Saldo resultante.\n\n## Toma física (inventario)\n\nSe realiza mensual o trimestralmente. **Conteo real** versus **saldo en sistema**. Las diferencias se llaman **sobrantes** o **faltantes** y deben **justificarse**.\n\n## Mermas\n\nEn lácteos es normal **cierta merma** (evaporación, derrames menores). Si supera el porcentaje histórico esperado (1-3%), investigar causa.",
                                'quiz' => [
                                    [
                                        'question' => 'Para productos con fecha de vencimiento, ¿qué método se usa?',
                                        'options' => [
                                            ['text' => 'FIFO',  'correct' => false, 'explanation' => 'FIFO es para insumos secos sin mucha variación.'],
                                            ['text' => 'FEFO',   'correct' => true,  'explanation' => 'Correcto: lo que vence primero sale primero.'],
                                            ['text' => 'LIFO',   'correct' => false, 'explanation' => 'Dejaría los más viejos y vencería producto.'],
                                            ['text' => 'Aleatorio','correct' => false, 'explanation' => 'Inaceptable.'],
                                        ],
                                    ],
                                    [
                                        'question' => '¿Qué es una merma aceptable en planta láctea?',
                                        'options' => [
                                            ['text' => '10-20%',   'correct' => false, 'explanation' => 'Excesiva, hay un problema grande.'],
                                            ['text' => '1-3%',      'correct' => true,  'explanation' => 'Correcto, rango típico histórico.'],
                                            ['text' => '0%',        'correct' => false, 'explanation' => 'Imposible en la práctica.'],
                                            ['text' => '30-40%',    'correct' => false, 'explanation' => 'Inaceptable.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            [
                'name' => 'Gestión de Proveedores de Leche Fresca',
                'description' => 'Selección, evaluación y seguimiento de proveedores de leche cruda. Homologación y trazabilidad del origen.',
                'area' => $adm, 'template' => $tplAdm, 'duration_min' => 90,
                'modules' => [
                    [
                        'title' => 'Criterios de selección',
                        'lessons' => [
                            [
                                'title' => 'Homologación de proveedores de leche',
                                'content' => "# Proveedores de leche cruda\n\n## Requisitos mínimos de homologación\n\n### Documentales\n\n- DNI o RUC vigente.\n- Declaración jurada de sanidad del ganado.\n- **Carnet de inocuidad** (si aplica en la localidad).\n- Dirección del establo georreferenciada.\n\n### Técnicos\n\n- Ganado con controles veterinarios (brucelosis, tuberculosis).\n- Sistema de ordeño limpio (mecánico o manual higiénico).\n- Cadena de frío hasta recepción (<6 °C).\n- Volumen mínimo confiable.\n\n## Calificación del proveedor\n\nCada mes se le otorga una **categoría** basada en:\n\n| Indicador | Peso |\n|-----------|------|\n| Calidad microbiológica | 30% |\n| Calidad fisicoquímica | 30% |\n| Cumplimiento de volumen pactado | 20% |\n| Puntualidad en entrega | 10% |\n| Documentación al día | 10% |\n\n## Categorías\n\n- **A** (>90 pts): incentivo económico.\n- **B** (70-89): estándar.\n- **C** (50-69): en observación.\n- **D** (<50): **suspensión** hasta plan de mejora.\n\n> Un proveedor bien calificado **produce mejor leche** → mejor producto final.",
                                'quiz' => [
                                    [
                                        'question' => '¿Qué porcentaje pesa la calidad microbiológica en la calificación del proveedor?',
                                        'options' => [
                                            ['text' => '30%',   'correct' => true,  'explanation' => 'Correcto, uno de los dos criterios principales.'],
                                            ['text' => '5%',     'correct' => false, 'explanation' => 'Insuficiente.'],
                                            ['text' => '80%',    'correct' => false, 'explanation' => 'Demasiado, no considera otros aspectos.'],
                                            ['text' => 'No se mide','correct' => false, 'explanation' => 'Es un indicador central.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
