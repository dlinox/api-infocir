<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─────────────────────────────────────────────
        // CONFIGURACIÓN BASE
        // ─────────────────────────────────────────────

        Schema::create('learning_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();             // Nombre del área (ej: "Seguridad", "Calidad")
            $table->string('description', 255)->nullable();    // Descripción opcional del área
            $table->boolean('is_active')->default(true);       // Permite activar/desactivar el área
            $table->unsignedBigInteger('created_by')->nullable(); // Usuario que creó el registro
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('auth_users')->onDelete('set null');
            $table->index('name');
            $table->index('is_active');
        });

        Schema::create('learning_training_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();             // Tipo de capacitación (ej: "Taller", "Webinar", "E-learning")
            $table->string('description', 255)->nullable();    // Descripción opcional del tipo
            $table->boolean('is_active')->default(true);       // Permite activar/desactivar el tipo
            $table->unsignedBigInteger('created_by')->nullable(); // Usuario que creó el registro
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('auth_users')->onDelete('set null');
            $table->index('name');
            $table->index('is_active');
        });

        Schema::create('learning_instructors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id');           // Persona que actúa como instructor (core_persons)
            $table->boolean('is_active')->default(true);       // Permite activar/desactivar al instructor
            $table->timestamps();

            $table->foreign('person_id')->references('id')->on('core_persons')->onDelete('cascade');
            $table->index('person_id');
            $table->index('is_active');
        });

        Schema::create('learning_courses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);                           // Nombre del curso (ej: "Buenas Prácticas de Manufactura")
            $table->text('description')->nullable();               // Descripción detallada del curso
            $table->unsignedBigInteger('area_id')->nullable();     // Área a la que pertenece el curso
            $table->decimal('duration_min', 5, 2)->nullable();    // Duración estimada total en minutos
            $table->unsignedBigInteger('cover_image')->nullable();       // Foto de portada del curso
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
                                                                   //   draft     → en construcción, no visible para capacitaciones
                                                                   //   published → publicado, disponible para asignar a trainings
                                                                   //   archived  → retirado, conserva historial pero no se puede usar
            $table->unsignedBigInteger('created_by')->nullable();  // Usuario que creó el curso
            $table->timestamps();

            $table->foreign('area_id')->references('id')->on('learning_areas')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('auth_users')->onDelete('set null');
            $table->foreign('cover_image')->references('id')->on('core_files')->nullOnDelete();

            $table->index('name');
            $table->index('area_id');
        });

        Schema::create('learning_course_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');               // Curso al que pertenece el módulo
            $table->string('title', 150);                          // Título del módulo (ej: "Módulo 1: Introducción")
            $table->text('description')->nullable();               // Descripción del contenido del módulo
            $table->unsignedSmallInteger('order')->default(1);     // Posición del módulo dentro del curso (1, 2, 3...)
            $table->boolean('is_active')->default(true);           // Permite activar/desactivar el módulo
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('learning_courses')->onDelete('cascade');
            $table->index('course_id');
            $table->index('order');
        });

        Schema::create('learning_lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id');               // Módulo al que pertenece la lección
            $table->string('title', 150);                          // Título de la lección (ej: "Lección 3: Higiene personal")
            $table->text('description')->nullable();               // Resumen o introducción de la lección
            $table->unsignedSmallInteger('order')->default(1);     // Posición de la lección dentro del módulo
            $table->boolean('has_quiz')->default(false);           // Indica si esta lección tiene cuestionario al final
            $table->decimal('passing_score', 5, 2)->default(60.00); // Puntaje mínimo para aprobar el quiz (0–100)
            $table->boolean('is_active')->default(true);           // Permite activar/desactivar la lección
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('learning_course_modules')->onDelete('cascade');
            $table->index('module_id');
            $table->index('order');
        });

        Schema::create('learning_lesson_resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');               // Lección a la que pertenece este recurso
            $table->enum('type', ['video', 'pdf', 'image', 'text', 'link']); // Tipo de recurso:
            //   video → URL de video (YouTube, Vimeo, S3)
            //   pdf   → URL del archivo PDF almacenado
            //   image → URL de imagen (S3, CDN)
            //   text  → Contenido HTML/Markdown en `body`
            //   link  → URL externa de referencia
            $table->string('title', 150)->nullable();              // Etiqueta del recurso (ej: "Video introductorio", "Material de apoyo")
            $table->text('url')->nullable();                       // URL del archivo o recurso externo (video, pdf, imagen, link)
            $table->longText('body')->nullable();                  // Contenido HTML o Markdown (solo para type = 'text')
            $table->unsignedSmallInteger('order')->default(1);     // Orden de presentación dentro de la lección
            $table->boolean('is_active')->default(true);           // Permite ocultar un recurso sin eliminarlo
            $table->timestamps();

            $table->foreign('lesson_id')->references('id')->on('learning_lessons')->onDelete('cascade');
            $table->index('lesson_id');
            $table->index('type');
            $table->index('order');
        });

        Schema::create('learning_quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');               // Lección a la que pertenece la pregunta
            $table->text('question');                              // Texto de la pregunta
            $table->unsignedSmallInteger('order')->default(1);     // Orden de la pregunta en el quiz
            $table->timestamps();

            $table->foreign('lesson_id')->references('id')->on('learning_lessons')->onDelete('cascade');
            $table->index('lesson_id');
        });

        Schema::create('learning_quiz_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');             // Pregunta a la que pertenece esta opción
            $table->string('text', 255);                           // Texto de la opción de respuesta
            $table->boolean('is_correct')->default(false);         // Indica si esta opción es la respuesta correcta
            $table->unsignedSmallInteger('order')->default(1);     // Orden de presentación de la opción
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('learning_quiz_questions')->onDelete('cascade');
            $table->index('question_id');
        });

        Schema::create('learning_trainings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');               // Curso que se va a dictar
            $table->unsignedBigInteger('instructor_id')->nullable(); // Instructor asignado (puede cambiar o no tener)
            $table->unsignedBigInteger('training_type_id')->nullable(); // Modalidad: taller, webinar, e-learning, etc.
            $table->date('start_date')->nullable();                 // Fecha de inicio de la capacitación
            $table->date('end_date')->nullable();                   // Fecha estimada de cierre
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            //   scheduled  → programada, aún no iniciada
            //   ongoing    → en curso actualmente
            //   completed  → finalizada con éxito
            //   cancelled  → cancelada
            $table->enum('modality', ['in_person', 'virtual', 'mixed'])->default('in_person');
            //   in_person → 100% presencial
            //   virtual   → 100% online/remota
            //   mixed     → híbrida (presencial + virtual)
            $table->string('location', 200)->nullable();           // Lugar físico o enlace de reunión virtual
            $table->unsignedSmallInteger('max_participants')->nullable(); // Cupo máximo (null = sin límite)
            $table->boolean('is_active')->default(true);           // Permite activar/desactivar la capacitación
            $table->unsignedBigInteger('created_by')->nullable();  // Usuario que creó la capacitación
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('learning_courses')->onDelete('cascade');
            $table->foreign('instructor_id')->references('id')->on('learning_instructors')->onDelete('set null');
            $table->foreign('training_type_id')->references('id')->on('learning_training_types')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('auth_users')->onDelete('set null');
            $table->index('course_id');
            $table->index('instructor_id');
            $table->index('training_type_id');
            $table->index('status');
            $table->index('modality');
            $table->index('is_active');
        });

        Schema::create('learning_enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_id');             // Capacitación en la que está inscrito el trabajador
            $table->unsignedBigInteger('worker_id');               // Trabajador inscrito (dairy_workers)
            $table->enum('status', ['enrolled', 'in_progress', 'completed', 'dropped'])->default('enrolled');
            //   enrolled    → inscrito, no ha iniciado
            //   in_progress → ha visto al menos una lección
            //   completed   → completó todas las lecciones
            //   dropped     → se retiró o fue dado de baja
            $table->decimal('progress', 5, 2)->default(0.00);     // Porcentaje de avance (0.00 – 100.00)
            $table->timestamp('enrolled_at')->useCurrent();        // Fecha y hora de inscripción
            $table->timestamp('completed_at')->nullable();         // Fecha y hora en que completó el curso
            $table->timestamps();

            $table->unique(['training_id', 'worker_id']);          // Un trabajador no puede inscribirse dos veces
            $table->foreign('training_id')->references('id')->on('learning_trainings')->onDelete('cascade');
            $table->foreign('worker_id')->references('person_id')->on('dairy_workers')->onDelete('cascade');
            $table->index('training_id');
            $table->index('worker_id');
            $table->index('status');
        });

        Schema::create('learning_lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');           // Inscripción del trabajador
            $table->unsignedBigInteger('lesson_id');               // Lección que completó
            $table->boolean('completed')->default(false);          // true cuando el trabajador marca la lección como vista
            $table->timestamp('completed_at')->nullable();         // Fecha y hora en que completó la lección
            $table->timestamps();

            $table->unique(['enrollment_id', 'lesson_id']);        // Una fila por lección por inscripción
            $table->foreign('enrollment_id')->references('id')->on('learning_enrollments')->onDelete('cascade');
            $table->foreign('lesson_id')->references('id')->on('learning_lessons')->onDelete('cascade');
            $table->index('enrollment_id');
            $table->index('lesson_id');
        });

        Schema::create('learning_quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');           // Inscripción del trabajador que realiza el intento
            $table->unsignedBigInteger('lesson_id');               // Lección cuyo quiz se está intentando
            $table->decimal('score', 5, 2)->default(0.00);         // Puntaje obtenido en este intento (0.00 – 100.00)
            $table->boolean('passed')->default(false);             // true si score >= passing_score de la lección
            $table->timestamp('attempted_at')->useCurrent();       // Fecha y hora del intento
            $table->timestamps();

            $table->foreign('enrollment_id')->references('id')->on('learning_enrollments')->onDelete('cascade');
            $table->foreign('lesson_id')->references('id')->on('learning_lessons')->onDelete('cascade');
            $table->index('enrollment_id');
            $table->index('lesson_id');
            $table->index('passed');
        });

        Schema::create('learning_quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attempt_id');              // Intento al que pertenece esta respuesta
            $table->unsignedBigInteger('question_id');             // Pregunta respondida
            $table->unsignedBigInteger('option_id');               // Opción seleccionada por el trabajador
            $table->timestamps();

            $table->unique(['attempt_id', 'question_id']);         // Una respuesta por pregunta por intento
            $table->foreign('attempt_id')->references('id')->on('learning_quiz_attempts')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('learning_quiz_questions')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('learning_quiz_options')->onDelete('cascade');
            $table->index('attempt_id');
            $table->index('question_id');
        });

        Schema::create('learning_certifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id')->unique(); // Inscripción certificada (1 certificado por inscripción)
            $table->string('certificate_number', 50)->unique();    // Código único del certificado (ej: "CERT-2026-00123")
            $table->date('issued_at');                             // Fecha de emisión del certificado
            $table->date('expires_at')->nullable();                // Fecha de vencimiento (null = no vence)
            $table->timestamps();

            $table->foreign('enrollment_id')->references('id')->on('learning_enrollments')->onDelete('cascade');
            $table->index('certificate_number');
            $table->index('issued_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_certifications');
        Schema::dropIfExists('learning_quiz_answers');
        Schema::dropIfExists('learning_quiz_attempts');
        Schema::dropIfExists('learning_lesson_progress');
        Schema::dropIfExists('learning_enrollments');
        Schema::dropIfExists('learning_trainings');
        Schema::dropIfExists('learning_quiz_options');
        Schema::dropIfExists('learning_quiz_questions');
        Schema::dropIfExists('learning_lesson_resources');
        Schema::dropIfExists('learning_lessons');
        Schema::dropIfExists('learning_course_modules');
        Schema::dropIfExists('learning_courses');
        Schema::dropIfExists('learning_instructors');
        Schema::dropIfExists('learning_training_types');
        Schema::dropIfExists('learning_areas');
    }
};
