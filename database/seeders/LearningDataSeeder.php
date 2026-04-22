<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use App\Models\Behavior\BehaviorProfile;
use App\Models\Behavior\Role;
use App\Models\Core\Person;
use App\Models\Core\Profile as CoreProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LearningDataSeeder extends Seeder
{
    use WithoutModelEvents;

    private array $instructors    = [];
    private array $areas          = [];
    private array $trainingTypes  = [];
    private array $templates      = [];
    private array $coursesData    = [];

    public function run(): void
    {
        $this->loadReferences();
        $this->seedCertificateTemplates();
        $this->seedInstructors();
        $this->seedCourses();
        $this->seedPrograms();
        $this->seedTrainings();
    }

    // =====================================================================
    // Referencias
    // =====================================================================

    private function loadReferences(): void
    {
        $this->areas         = DB::table('learning_areas')->pluck('id', 'name')->toArray();
        $this->trainingTypes = DB::table('learning_training_types')->pluck('id', 'name')->toArray();
    }

    // =====================================================================
    // Plantillas de certificado
    // =====================================================================

    private function seedCertificateTemplates(): void
    {
        $now = now();
        foreach ([
            ['name' => 'Certificado BPM — Industria Láctea',   'orientation' => 'landscape', 'validity_days' => 730],
            ['name' => 'Certificado Operaciones de Planta',     'orientation' => 'landscape', 'validity_days' => 730],
        ] as $t) {
            $id = DB::table('learning_certificate_templates')->insertGetId([
                'name'               => $t['name'],
                'page_size'          => 'a4',
                'orientation'        => $t['orientation'],
                'background_file_id' => null,
                'fields'             => json_encode([
                    'name' => ['x' => 23.2638, 'y' => 55.4157, 'w' => 53.4759, 'h' => 7.0528],
                    'qr'   => ['x' => 75.6684, 'y' => 79.8489, 'w' => 10.6951, 'h' => 15.1134],
                ]),
                'validity_days' => $t['validity_days'],
                'is_active'     => true,
                'created_by'    => null,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
            $this->templates[$t['name']] = $id;
        }
    }

    // =====================================================================
    // Instructor
    // =====================================================================

    private function seedInstructors(): void
    {
        $role = Role::where('name', 'instructor')->first();

        $person = Person::create([
            'document_type'    => '1',
            'document_number'  => '42188933',
            'name'             => 'Raúl',
            'paternal_surname' => 'Mamani',
            'maternal_surname' => 'Quispe',
            'date_birth'       => '1985-03-15',
            'cellphone'        => '987100001',
            'email'            => 'raul.mamani@infocir.pe',
            'gender'           => '1',
            'country'          => 'PE',
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
            'username'  => '42188933',
            'email'     => 'raul.mamani@infocir.pe',
            'password'  => '42188933',
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

    // =====================================================================
    // Cursos
    // =====================================================================

    private function seedCourses(): void
    {
        foreach ($this->coursesCatalog() as $courseDef) {
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
                    $hasQuiz  = !empty($lesson['quiz']);
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

                    // Solo insertar recurso si la lección tiene contenido
                    if (!empty($lesson['content'])) {
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
                    }

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
    // Programa
    // =====================================================================

    private function seedPrograms(): void
    {
        $coursesByName = collect($this->coursesData)->keyBy('name');

        $programId = DB::table('learning_programs')->insertGetId([
            'name'                    => 'Programa de Producción y BPM Láctea',
            'description'             => 'Formación integral para productores y personal de planta en gestión de producción y buenas prácticas de manufactura.',
            'certificate_template_id' => $this->templates['Certificado Operaciones de Planta'] ?? null,
            'status'                  => 'published',
            'is_active'               => true,
            'created_by'              => null,
            'created_at'              => now(),
            'updated_at'              => now(),
        ]);

        $courseNames = [
            'Gestión de Producción y Procesamiento Lácteo',
            'Introducción a Buenas Prácticas de Manufactura',
        ];

        foreach ($courseNames as $idx => $courseName) {
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

    // =====================================================================
    // Capacitación
    // =====================================================================

    private function seedTrainings(): void
    {
        $coursesByName = collect($this->coursesData)->keyBy('name');
        $ttElearn      = $this->trainingTypes['Curso e-learning'] ?? null;
        $course        = $coursesByName->get('Gestión de Producción y Procesamiento Lácteo');

        DB::table('learning_trainings')->insert([
            'course_id'               => $course['id'] ?? null,
            'instructor_id'           => $this->instructors[0],
            'training_type_id'        => $ttElearn,
            'certificate_template_id' => $course['template_id'] ?? null,
            'is_event_only'           => false,
            'start_date'              => Carbon::now()->addDays(5)->toDateString(),
            'end_date'                => Carbon::now()->addDays(7)->toDateString(),
            'status'                  => 'scheduled',
            'modality'                => 'virtual',
            'location'                => 'Plataforma INFOCIR',
            'max_participants'        => 30,
            'is_active'               => true,
            'created_by'              => null,
            'created_at'              => now(),
            'updated_at'              => now(),
        ]);
    }

    // =====================================================================
    // Catálogo de cursos (2)
    // =====================================================================

    private function coursesCatalog(): array
    {
        $bpm = 'Buenas Prácticas de Manufactura';
        $ope = 'Operaciones de Planta';

        return [

            // ----------------------------------------------------------------
            // Curso 1 — del PDF (sin recursos, solo quizzes)
            // ----------------------------------------------------------------
            [
                'name'         => 'Gestión de Producción y Procesamiento Lácteo',
                'description'  => 'Ruta de aprendizaje técnica para productores y personal de planta. Cubre producción de leche, sanidad animal, ordeño, calidad y procesamiento en planta.',
                'area'         => $ope,
                'template'     => 'Certificado Operaciones de Planta',
                'duration_min' => 300,
                'modules'      => [

                    [
                        'title'       => 'Producción de Leche',
                        'description' => 'Comprender la importancia de la alimentación en la calidad del producto.',
                        'lessons'     => [
                            [
                                'title'       => 'Alimentación y calidad de la leche',
                                'description' => 'Relación entre el pastoreo y la calidad del producto.',
                                'quiz'        => [
                                    [
                                        'question' => 'Según el guion, ¿cuál es el propósito principal de llevar a las vacas a pastar al campo temprano por la mañana?',
                                        'hint'     => 'Piensa en qué beneficio directo obtiene la leche cuando la vaca se alimenta libremente en el campo.',
                                        'options'  => [
                                            ['text' => 'Para que produzcan leche pura y de gran calidad.',                           'correct' => true,  'explanation' => 'El pastoreo temprano permite una alimentación natural adecuada, lo cual impacta directamente en la calidad y pureza de la leche obtenida.'],
                                            ['text' => 'Para que convivan con otras especies del establo.',                          'correct' => false, 'explanation' => 'La convivencia entre especies no es el objetivo del pastoreo temprano.'],
                                            ['text' => 'Para reducir la cantidad de forraje verde que consumen.',                    'correct' => false, 'explanation' => 'El pastoreo temprano busca incrementar el consumo de forraje verde, no reducirlo.'],
                                            ['text' => 'Para que el acopiador pueda recogerlas en el campo.',                       'correct' => false, 'explanation' => 'El acopiador recoge la leche, no las vacas en el campo.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],

                    [
                        'title'       => 'Sanidad Animal',
                        'description' => 'Identificar los pilares de la salud bovina y productividad.',
                        'lessons'     => [
                            [
                                'title'       => 'Cuidados esenciales para la producción estable',
                                'description' => 'Nutrición, hidratación y vacunación como pilares de la productividad.',
                                'quiz'        => [
                                    [
                                        'question' => 'Si la producción de leche es inestable, ¿qué combinación de cuidados es esencial para aumentar la cantidad y mantener sanas a las vacas?',
                                        'hint'     => 'La respuesta incluye tanto la alimentación diaria como la protección preventiva contra enfermedades.',
                                        'options'  => [
                                            ['text' => 'Brindar forraje, agua permanente, alimentos balanceados y llevar un estricto calendario de vacunas.',    'correct' => true,  'explanation' => 'Una adecuada combinación de nutrición y cuidado preventivo (vacunación) asegura el bienestar del animal y estabiliza la producción.'],
                                            ['text' => 'Darles únicamente pasto seco y retirarles el agua por las noches para evitar que se resfríen.',          'correct' => false, 'explanation' => 'Retirar el agua es perjudicial para la salud y producción del animal.'],
                                            ['text' => 'Mantenerlas encerradas en el cobertizo todo el día sin necesidad de vacunarlas.',                        'correct' => false, 'explanation' => 'El confinamiento total y la falta de vacunación ponen en riesgo la salud del animal.'],
                                            ['text' => 'Evitar que las terneras tomen el calostro materno durante los primeros días.',                           'correct' => false, 'explanation' => 'El calostro es esencial para el sistema inmune de las crías y no debe suspenderse.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],

                    [
                        'title'       => 'Equipamiento en Sala de Ordeño',
                        'description' => 'Aplicar prácticas de economía circular y sostenibilidad en la granja.',
                        'lessons'     => [
                            [
                                'title'       => 'Aprovechamiento del estiércol',
                                'description' => 'Economía circular: abono orgánico y biogás.',
                                'quiz'        => [
                                    [
                                        'question' => '¿Qué práctica se recomienda realizar con el estiércol recogido en las instalaciones para sacarle provecho?',
                                        'hint'     => 'El estiércol puede reutilizarse dentro de la propia granja de dos maneras: una beneficia el suelo y la otra genera energía.',
                                        'options'  => [
                                            ['text' => 'Convertirlo en abono orgánico para zonas de cultivo o utilizarlo para producir biogás.',  'correct' => true,  'explanation' => 'El estiércol tratado puede ser reciclado útilmente en la propia granja, mejorando cultivos o generando energía.'],
                                            ['text' => 'Dejarlo secar en el suelo de la sala para mantener el calor durante las épocas de frío.', 'correct' => false, 'explanation' => 'Dejar estiércol en la sala contamina el ambiente de ordeño.'],
                                            ['text' => 'Mezclarlo con el alimento concentrado para mejorar la digestión bovina.',                 'correct' => false, 'explanation' => 'El estiércol no debe mezclarse con el alimento animal.'],
                                            ['text' => 'Desecharlo bloqueando los desagües de las piletas para evitar malos olores.',             'correct' => false, 'explanation' => 'Bloquear desagües genera problemas sanitarios y ambientales.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],

                    [
                        'title'       => 'Buenas Prácticas de Ordeño',
                        'description' => 'Prevenir infecciones intramamarias mediante desinfección.',
                        'lessons'     => [
                            [
                                'title'       => 'Sellado con yodo post-ordeño',
                                'description' => 'Función y técnica del sellado de ubres para prevenir mastitis.',
                                'quiz'        => [
                                    [
                                        'question' => '¿Cuál es la función principal de realizar el "sellado" con yodo en las ubres justo después del ordeño?',
                                        'hint'     => 'Justo después del ordeño el canal del pezón queda abierto unos minutos. ¿Qué riesgo sanitario surge en ese período?',
                                        'options'  => [
                                            ['text' => 'Evitar que microorganismos que causan mastitis ingresen por el conducto de la ubre.',          'correct' => true,  'explanation' => 'El sellado bloquea físicamente la entrada de bacterias patógenas cuando la ubre aún está vulnerable post-ordeño.'],
                                            ['text' => 'Mejorar el sabor de la leche antes de que sea llevada al cuarto de enfriamiento.',             'correct' => false, 'explanation' => 'El yodo no afecta el sabor de la leche; su función es exclusivamente sanitaria.'],
                                            ['text' => 'Cicatrizar heridas profundas causadas por el clima extremadamente frío.',                      'correct' => false, 'explanation' => 'El sellado es para prevenir infecciones, no para cicatrizar heridas.'],
                                            ['text' => 'Marcar visualmente a la vaca para indicar que ya se completó su ordeño diario.',               'correct' => false, 'explanation' => 'El sellado no tiene función de marcado; es una práctica sanitaria.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],

                    [
                        'title'       => 'Calidad Láctea',
                        'description' => 'Dominar el uso de pruebas diagnósticas de campo (CMT).',
                        'lessons'     => [
                            [
                                'title'       => 'Prueba California Mastitis Test (CMT)',
                                'description' => 'Interpretación de resultados del CMT en campo.',
                                'quiz'        => [
                                    [
                                        'question' => 'Al realizar la prueba California Mastitis Test (CMT), ¿qué indica si la leche mezclada con el reactivo comienza a presentar espesamiento?',
                                        'hint'     => 'El reactivo del CMT reacciona con el material celular presente en la leche. Más células → mayor espesamiento.',
                                        'options'  => [
                                            ['text' => 'Que la ubre de la vaca presenta una infección o algún nivel de mastitis.',         'correct' => true,  'explanation' => 'El espesamiento es una reacción química positiva que confirma la presencia de infección celular.'],
                                            ['text' => 'Que la leche ha sido adulterada con exceso de agua.',                              'correct' => false, 'explanation' => 'El exceso de agua produce el efecto contrario: la leche se diluye.'],
                                            ['text' => 'Que la vaca no ha completado su esquema de vacunación contra la Brucelosis.',      'correct' => false, 'explanation' => 'El CMT no mide el estado vacunal del animal.'],
                                            ['text' => 'Que la leche es de excelente calidad y rica en grasas saludables.',               'correct' => false, 'explanation' => 'El espesamiento indica infección, no calidad.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],

                    [
                        'title'       => 'Manejo y Conservación',
                        'description' => 'Mantener la cadena de frío para control microbiológico.',
                        'lessons'     => [
                            [
                                'title'       => 'Enfriamiento de la leche a 4°C',
                                'description' => 'Importancia del control de temperatura post-ordeño.',
                                'quiz'        => [
                                    [
                                        'question' => '¿Por qué es fundamental enfriar la leche a 4°C en un tanque de enfriamiento después del ordeño?',
                                        'hint'     => 'Recuerda que las bacterias se multiplican con mayor velocidad a temperatura ambiente. ¿Qué efecto tiene el frío sobre su actividad?',
                                        'options'  => [
                                            ['text' => 'Para disminuir drásticamente la actividad y multiplicación de bacterias, evitando su fermentación.', 'correct' => true,  'explanation' => 'Las bajas temperaturas ralentizan el crecimiento de microorganismos, prolongando la vida útil e higiene de la leche cruda.'],
                                            ['text' => 'Para aumentar la acidez natural de la leche y facilitar la posterior producción de quesos.',         'correct' => false, 'explanation' => 'Enfriar la leche no aumenta su acidez; al contrario, la preserva.'],
                                            ['text' => 'Para congelar la leche y poder transportarla en forma de bloques sólidos.',                          'correct' => false, 'explanation' => 'A 4°C la leche no se congela; se refrigera, no se solidifica.'],
                                            ['text' => 'Para modificar el color de la leche y volverla más blanca y atractiva.',                             'correct' => false, 'explanation' => 'El enfriamiento no modifica el color de la leche.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],

                    [
                        'title'       => 'Transporte y Centro de Acopio',
                        'description' => 'Detectar inestabilidad térmica en la recepción de leche.',
                        'lessons'     => [
                            [
                                'title'       => 'Prueba del alcohol en el centro de acopio',
                                'description' => 'Diagnóstico de inestabilidad térmica mediante prueba de alcohol al 68%.',
                                'quiz'        => [
                                    [
                                        'question' => 'Si al mezclar leche con alcohol al 68% en el centro de acopio se forman grandes cantidades de cuajada, ¿qué conclusión se obtiene?',
                                        'hint'     => 'La prueba del alcohol evalúa si la leche resistirá el calor de la pasteurización. ¿Qué parámetro fisicoquímico está relacionado con esa resistencia?',
                                        'options'  => [
                                            ['text' => 'Que la acidez de la leche es alta (mayor a 0.20%) y no soportará altas temperaturas de procesamiento.', 'correct' => true,  'explanation' => 'La coagulación ante el alcohol demuestra inestabilidad térmica; la leche se cortaría al intentar pasteurizarla.'],
                                            ['text' => 'Que la leche es perfecta para elaborar queso fresco sin necesidad de cuajo artificial.',                'correct' => false, 'explanation' => 'Una leche con alta acidez no es de calidad óptima para ningún derivado.'],
                                            ['text' => 'Que la densidad es ideal y confirma que no se le ha agregado agua.',                                    'correct' => false, 'explanation' => 'La prueba de alcohol evalúa acidez/estabilidad, no densidad.'],
                                            ['text' => 'Que la leche está libre de residuos de antibióticos veterinarios.',                                     'correct' => false, 'explanation' => 'Para detectar antibióticos se usan pruebas específicas como Delvotest.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],

                    [
                        'title'       => 'Registro de Acopio',
                        'description' => 'Asegurar la trazabilidad sanitaria del ganado.',
                        'lessons'     => [
                            [
                                'title'       => 'Datos sanitarios en el registro diario',
                                'description' => 'Trazabilidad: qué registrar además del volumen de leche.',
                                'quiz'        => [
                                    [
                                        'question' => 'Al llenar la ficha de registro diario, ¿qué datos adicionales sobre las vacas deben documentarse aparte del volumen de leche entregado?',
                                        'hint'     => 'Piensa en toda la información que un inspector necesitaría para verificar que el animal está sano y apto para producir leche.',
                                        'options'  => [
                                            ['text' => 'El control sanitario, incluyendo fechas de vacunas, etapas de celo y enfermedades tratadas.',       'correct' => true,  'explanation' => 'Un registro completo permite asegurar la trazabilidad del producto y confirmar que proviene de animales aptos.'],
                                            ['text' => 'Exclusivamente el nombre de la vaca y su peso corporal al momento del ordeño.',                     'correct' => false, 'explanation' => 'El peso no es el dato clave para la trazabilidad sanitaria.'],
                                            ['text' => 'El color exacto de las manchas de cada animal para facilitar su identificación visual.',            'correct' => false, 'explanation' => 'El color de las manchas no tiene relevancia sanitaria.'],
                                            ['text' => 'La temperatura exacta del ambiente en el establo durante la noche anterior.',                       'correct' => false, 'explanation' => 'La temperatura ambiental no es el dato prioritario en el registro de acopio.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],

                    [
                        'title'       => 'Instalación e Implementación de Planta',
                        'description' => 'Comprender la necesidad de inocuidad industrial.',
                        'lessons'     => [
                            [
                                'title'       => 'Pasteurización: barrera microbiológica industrial',
                                'description' => 'Por qué la pasteurización es indispensable incluso con ordeño higiénico.',
                                'quiz'        => [
                                    [
                                        'question' => 'Incluso si el ordeño fue muy higiénico, ¿cuál es el motivo indispensable para pasar la leche por procesos de pasteurización en la planta?',
                                        'hint'     => 'Incluso con las mejores prácticas de campo, existen agentes microscópicos que solo se eliminan con el calor controlado.',
                                        'options'  => [
                                            ['text' => 'Para eliminar microorganismos patógenos residuales y garantizar un producto final totalmente seguro.',  'correct' => true,  'explanation' => 'La pasteurización aplica calor controlado para asegurar la destrucción de bacterias que los filtros mecánicos no pueden detener.'],
                                            ['text' => 'Para darle a todos los productos lácteos un color blanco estandarizado y uniforme.',                    'correct' => false, 'explanation' => 'La pasteurización no modifica el color de los productos.'],
                                            ['text' => 'Para aumentar artificialmente el volumen añadiendo componentes durante el calor.',                      'correct' => false, 'explanation' => 'La pasteurización no aumenta el volumen ni añade componentes.'],
                                            ['text' => 'Para que los operarios tengan tiempo de limpiar los equipos mientras la leche hierve.',                'correct' => false, 'explanation' => 'La pasteurización es un proceso técnico de inocuidad, no un descanso operativo.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],

                    [
                        'title'       => 'Diseño e Instalación de Planta',
                        'description' => 'Diferenciar procesos de maduración según tipo de producto.',
                        'lessons'     => [
                            [
                                'title'       => 'Queso fresco vs. queso andino: proceso de maduración',
                                'description' => 'Diferencias en el procesamiento post-salado entre variedades de queso.',
                                'quiz'        => [
                                    [
                                        'question' => 'Después de salir del cuarto de salado, ¿en qué se diferencia el procesamiento de los quesos andinos respecto a los frescos?',
                                        'hint'     => 'La diferencia clave está en el contenido de humedad final de cada variedad: uno se consume tierno y el otro requiere tiempo para perder humedad.',
                                        'options'  => [
                                            ['text' => 'Los quesos frescos se envasan rápidamente, mientras que los andinos pasan a una cámara de secado para madurar.',  'correct' => true,  'explanation' => 'Cada variedad requiere un manejo de humedad distinto: el fresco se preserva tierno y el andino necesita reposo para secar y madurar.'],
                                            ['text' => 'Ambos tipos de queso son almacenados inmediatamente al aire libre bajo el sol.',                                   'correct' => false, 'explanation' => 'La exposición directa al sol deteriora ambos tipos de queso.'],
                                            ['text' => 'Los quesos andinos se envasan al vacío de inmediato y los frescos se envían a secar en túneles.',                  'correct' => false, 'explanation' => 'Es al revés: el fresco se envasa pronto y el andino madura en cámara.'],
                                            ['text' => 'Ambos quesos deben lavarse vigorosamente con detergente antes del empaquetado.',                                   'correct' => false, 'explanation' => 'El lavado con detergente de los quesos terminados es incorrecto.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],

                ],
            ],

            // ----------------------------------------------------------------
            // Curso 2 — Introducción a BPM (con recurso de texto)
            // ----------------------------------------------------------------
            [
                'name'         => 'Introducción a Buenas Prácticas de Manufactura',
                'description'  => 'Fundamentos de las BPM aplicadas a la industria de lácteos: conceptos, normativa peruana (DIGESA) y responsabilidades del personal de planta.',
                'area'         => $bpm,
                'template'     => 'Certificado BPM — Industria Láctea',
                'duration_min' => 180,
                'modules'      => [
                    [
                        'title'       => '¿Qué son las BPM?',
                        'description' => 'Definiciones y marco normativo nacional.',
                        'lessons'     => [
                            [
                                'title'       => 'Definición y objetivos de las BPM',
                                'description' => 'Concepto, finalidad y principios de las Buenas Prácticas de Manufactura.',
                                'content'     => "# Buenas Prácticas de Manufactura (BPM)\n\nLas **Buenas Prácticas de Manufactura** son el conjunto de procedimientos, condiciones y controles que se aplican en las plantas de alimentos para asegurar que los productos sean **inocuos, seguros y de calidad**.\n\n## Objetivos principales\n\n- Proteger la **salud del consumidor**.\n- Evitar la **contaminación cruzada**.\n- Cumplir con los estándares de DIGESA y SENASA.\n- Garantizar la **trazabilidad** en toda la cadena productiva.",
                                'quiz'        => [
                                    [
                                        'question' => '¿Cuál es el objetivo principal de las BPM en una planta láctea?',
                                        'hint'     => 'Las BPM son normas de inocuidad. ¿A quién protege en última instancia un alimento inocuo y seguro?',
                                        'options'  => [
                                            ['text' => 'Aumentar la producción diaria',        'correct' => false, 'explanation' => 'La producción es una consecuencia, no el objetivo principal.'],
                                            ['text' => 'Proteger la salud del consumidor',     'correct' => true,  'explanation' => 'Exacto: la inocuidad del producto es el fin último de las BPM.'],
                                            ['text' => 'Reducir el costo de la mano de obra',  'correct' => false, 'explanation' => 'Las BPM no buscan reducir mano de obra.'],
                                            ['text' => 'Hacer la planta más grande',            'correct' => false, 'explanation' => 'El tamaño de la planta no es parte de las BPM.'],
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