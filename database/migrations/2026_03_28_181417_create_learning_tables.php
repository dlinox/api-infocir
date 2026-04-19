<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('learning_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();             // Nombre del área (ej: "Seguridad", "Calidad")
            $table->string('description', 255)->nullable();    // Descripción opcional del área
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('auth_users')->onDelete('set null');
            $table->index('name');
            $table->index('is_active');
        });

        Schema::create('learning_training_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();             // Tipo de capacitación (ej: "Taller", "Webinar", "E-learning")
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('auth_users')->onDelete('set null');
            $table->index('name');
            $table->index('is_active');
        });

        Schema::create('learning_instructors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id');           // Persona que actúa como instructor (core_persons)
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('person_id')->references('id')->on('core_persons')->onDelete('cascade');
            $table->index('person_id');
            $table->index('is_active');
        });

        Schema::create('learning_certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);                              // Nombre de la plantilla (ej: "Certificado BPM 2026")
            $table->enum('page_size', ['a4', 'letter', 'a5'])->default('a4');
            $table->enum('orientation', ['portrait', 'landscape'])->default('landscape');
            $table->unsignedBigInteger('background_file_id')->nullable(); // Imagen de fondo del certificado (core_files)
            $table->json('fields')->nullable();                       // Posiciones en % del canvas de: worker_name, certificate_number, qr, issued_at
            $table->unsignedSmallInteger('validity_days')->nullable(); // Días de vigencia del certificado; null = no vence
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('background_file_id')->references('id')->on('core_files')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('auth_users')->onDelete('set null');
            $table->index('name');
            $table->index('is_active');
        });

        Schema::create('learning_certificate_template_signatures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id');                // Plantilla a la que pertenece esta firma
            $table->unsignedBigInteger('signature_file_id')->nullable(); // Imagen de la firma manuscrita (core_files)
            $table->string('title', 150);                             // Nombre del firmante (ej: "Juan Pérez")
            $table->string('subtitle', 150)->nullable();              // Cargo del firmante (ej: "Gerente General")
            $table->decimal('x', 5, 2)->default(0);                  // Posición horizontal en % del canvas
            $table->decimal('y', 5, 2)->default(0);                  // Posición vertical en % del canvas
            $table->decimal('width', 5, 2)->default(10);              // Ancho en % del canvas
            $table->unsignedSmallInteger('order')->default(1);        // Orden de visualización
            $table->timestamps();

            $table->foreign('template_id', 'fk_lct_sigs_template_id')->references('id')->on('learning_certificate_templates')->onDelete('cascade');
            $table->foreign('signature_file_id', 'fk_lct_sigs_sig_file_id')->references('id')->on('core_files')->nullOnDelete();
            $table->index('template_id');
            $table->index('order');
        });

        Schema::create('learning_courses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);                               // Nombre del curso (ej: "Buenas Prácticas de Manufactura")
            $table->text('description')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->decimal('duration_min', 5, 2)->nullable();
            $table->unsignedBigInteger('cover_image')->nullable();
            $table->unsignedBigInteger('certificate_template_id')->nullable(); // Plantilla de certificado asociada al curso
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            //   draft     → en construcción, no visible para capacitaciones
            //   published → publicado, disponible para asignar a trainings
            //   archived  → retirado, conserva historial pero no se puede usar
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('area_id')->references('id')->on('learning_areas')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('auth_users')->onDelete('set null');
            $table->foreign('cover_image')->references('id')->on('core_files')->nullOnDelete();
            $table->foreign('certificate_template_id')->references('id')->on('learning_certificate_templates')->nullOnDelete();
            $table->index('name');
            $table->index('area_id');
        });

        Schema::create('learning_course_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('learning_courses')->onDelete('cascade');
            $table->index('course_id');
            $table->index('order');
        });

        Schema::create('learning_lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id');
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('order')->default(1);
            $table->boolean('has_quiz')->default(false);
            $table->decimal('passing_score', 5, 2)->default(60.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('learning_course_modules')->onDelete('cascade');
            $table->index('module_id');
            $table->index('order');
        });

        Schema::create('learning_lesson_resources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');
            $table->enum('type', ['video', 'youtube', 'pdf', 'image', 'text', 'link']);
            //   video   → archivo de video subido (referenciado por file_id)
            //   youtube → video de YouTube (URL en `body`)
            //   pdf     → archivo PDF subido (referenciado por file_id)
            //   image   → imagen subida (referenciada por file_id)
            //   text    → contenido HTML/Markdown en `body`
            //   link    → URL externa de referencia (en `body`)
            $table->string('title', 150)->nullable();
            $table->unsignedBigInteger('file_id')->nullable();     // para: video, pdf, image
            $table->longText('body')->nullable();                  // para: youtube, link, text
            $table->unsignedSmallInteger('order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('lesson_id')->references('id')->on('learning_lessons')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('core_files')->nullOnDelete();
            $table->index('lesson_id');
            $table->index('type');
            $table->index('order');
        });

        Schema::create('learning_quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');
            $table->text('question');
            $table->string('hint', 255)->nullable();              // Pista o ayuda para responder la pregunta
            $table->unsignedSmallInteger('order')->default(1);
            $table->timestamps();

            $table->foreign('lesson_id')->references('id')->on('learning_lessons')->onDelete('cascade');
            $table->index('lesson_id');
        });

        Schema::create('learning_quiz_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->string('text', 255);
            $table->boolean('is_correct')->default(false);
            $table->string('explanation', 255)->nullable();       // Explicación que se muestra al responder esta opción
            $table->unsignedSmallInteger('order')->default(1);
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('learning_quiz_questions')->onDelete('cascade');
            $table->index('question_id');
        });

        Schema::create('learning_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);                              // Nombre del programa (ej: "Inducción Operarios")
            $table->text('description')->nullable();
            $table->unsignedBigInteger('certificate_template_id')->nullable(); // Plantilla del certificado del programa completo
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('certificate_template_id')->references('id')->on('learning_certificate_templates')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('auth_users')->onDelete('set null');
            $table->index('name');
            $table->index('is_active');
        });

        Schema::create('learning_program_courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id');                 // Programa al que pertenece el curso
            $table->unsignedBigInteger('course_id');                  // Curso dentro del programa
            $table->unsignedSmallInteger('order')->default(1);        // Orden de cursado dentro del programa
            $table->boolean('is_required')->default(true);            // true = debe completarse para avanzar al siguiente curso

            $table->unique(['program_id', 'course_id']);
            $table->foreign('program_id')->references('id')->on('learning_programs')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('learning_courses')->onDelete('cascade');
            $table->index('program_id');
            $table->index('order');
        });

        Schema::create('learning_program_deliveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_id');                 // Programa que se va a dictar
            $table->unsignedBigInteger('instructor_id')->nullable();
            $table->unsignedBigInteger('training_type_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->enum('modality', ['in_person', 'virtual', 'mixed'])->default('in_person');
            $table->string('location', 200)->nullable();
            $table->unsignedSmallInteger('max_participants')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('program_id')->references('id')->on('learning_programs')->onDelete('cascade');
            $table->foreign('instructor_id')->references('id')->on('learning_instructors')->onDelete('set null');
            $table->foreign('training_type_id')->references('id')->on('learning_training_types')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('auth_users')->onDelete('set null');
            $table->index('program_id');
            $table->index('status');
            $table->index('is_active');
        });

        Schema::create('learning_trainings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->nullable();       // null = evento sin contenido digital (solo asistencia)
            $table->unsignedBigInteger('instructor_id')->nullable();
            $table->unsignedBigInteger('training_type_id')->nullable();
            $table->unsignedBigInteger('certificate_template_id')->nullable(); // Sobreescribe la plantilla del curso si se especifica
            $table->boolean('is_event_only')->default(false);          // true = solo asistencia, sin lecciones ni quiz
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            //   scheduled  → programada, aún no iniciada
            //   ongoing    → en curso actualmente
            //   completed  → finalizada con éxito
            //   cancelled  → cancelada
            $table->enum('modality', ['in_person', 'virtual', 'mixed'])->default('in_person');
            //   in_person → 100% presencial
            //   virtual   → 100% online/remota
            //   mixed     → híbrida (presencial + virtual)
            $table->string('location', 200)->nullable();
            $table->unsignedSmallInteger('max_participants')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('learning_courses')->nullOnDelete();
            $table->foreign('instructor_id')->references('id')->on('learning_instructors')->onDelete('set null');
            $table->foreign('training_type_id')->references('id')->on('learning_training_types')->onDelete('set null');
            $table->foreign('certificate_template_id')->references('id')->on('learning_certificate_templates')->nullOnDelete();
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
            $table->string('enrollable_type', 100);                   // Tipo de entidad: 'learning_courses' | 'learning_trainings' | 'learning_program_deliveries'
            $table->unsignedBigInteger('enrollable_id');              // ID del curso/training/delivery
            $table->unsignedBigInteger('worker_id');                  // Trabajador inscrito (dairy_workers)
            $table->enum('status', ['enrolled', 'in_progress', 'completed', 'dropped'])->default('enrolled');
            //   enrolled    → inscrito, no ha iniciado
            //   in_progress → ha visto al menos una lección
            //   completed   → completó todas las lecciones
            //   dropped     → se retiró o fue dado de baja
            $table->decimal('progress', 5, 2)->default(0.00);        // Porcentaje de avance (0.00 – 100.00)
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['enrollable_type', 'enrollable_id', 'worker_id'], 'uq_enrollments_enrollable_worker'); // Un worker no puede inscribirse dos veces al mismo enrollable
            $table->foreign('worker_id')->references('person_id')->on('dairy_workers')->onDelete('cascade');
            $table->index(['enrollable_type', 'enrollable_id']);
            $table->index('worker_id');
            $table->index('status');
        });

        Schema::create('learning_lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');              // Inscripción del trabajador
            $table->unsignedBigInteger('lesson_id');                  // Lección completada
            $table->boolean('completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['enrollment_id', 'lesson_id']);           // Una fila por lección por inscripción
            $table->foreign('enrollment_id')->references('id')->on('learning_enrollments')->onDelete('cascade');
            $table->foreign('lesson_id')->references('id')->on('learning_lessons')->onDelete('cascade');
            $table->index('enrollment_id');
            $table->index('lesson_id');
        });

        Schema::create('learning_quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('lesson_id');
            $table->decimal('score', 5, 2)->default(0.00);            // Puntaje obtenido (0.00 – 100.00)
            $table->boolean('passed')->default(false);                // true si score >= passing_score de la lección
            $table->timestamp('attempted_at')->useCurrent();
            $table->timestamps();

            $table->foreign('enrollment_id')->references('id')->on('learning_enrollments')->onDelete('cascade');
            $table->foreign('lesson_id')->references('id')->on('learning_lessons')->onDelete('cascade');
            $table->index('enrollment_id');
            $table->index('lesson_id');
            $table->index('passed');
        });

        Schema::create('learning_quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attempt_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('option_id');
            $table->timestamps();

            $table->unique(['attempt_id', 'question_id']);            // Una respuesta por pregunta por intento
            $table->foreign('attempt_id')->references('id')->on('learning_quiz_attempts')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('learning_quiz_questions')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('learning_quiz_options')->onDelete('cascade');
            $table->index('attempt_id');
            $table->index('question_id');
        });

        Schema::create('learning_certifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id')->unique();    // Inscripción certificada (1 certificado por inscripción)
            $table->unsignedBigInteger('template_id')->nullable();    // Plantilla usada para generar este certificado
            $table->string('certificate_number', 50)->unique();       // Código único (ej: "CERT-2026-00123")
            $table->date('issued_at');
            $table->date('expires_at')->nullable();                   // null = no vence
            $table->timestamps();

            $table->foreign('enrollment_id')->references('id')->on('learning_enrollments')->onDelete('cascade');
            $table->foreign('template_id')->references('id')->on('learning_certificate_templates')->nullOnDelete();
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
        Schema::dropIfExists('learning_program_deliveries');
        Schema::dropIfExists('learning_program_courses');
        Schema::dropIfExists('learning_programs');
        Schema::dropIfExists('learning_quiz_options');
        Schema::dropIfExists('learning_quiz_questions');
        Schema::dropIfExists('learning_lesson_resources');
        Schema::dropIfExists('learning_lessons');
        Schema::dropIfExists('learning_course_modules');
        Schema::dropIfExists('learning_courses');
        Schema::dropIfExists('learning_certificate_template_signatures');
        Schema::dropIfExists('learning_certificate_templates');
        Schema::dropIfExists('learning_instructors');
        Schema::dropIfExists('learning_training_types');
        Schema::dropIfExists('learning_areas');
    }
};
