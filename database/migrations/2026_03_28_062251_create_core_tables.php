<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('core_countries', function (Blueprint $table) {
            $table->char('code', 2)->unique();
            $table->string('name', 100)->unique();

            $table->primary('code');
            $table->index('name');
        });

        Schema::create('core_cities', function (Blueprint $table) {
            $table->char('code', 6);
            $table->string('department', 100);
            $table->string('province', 100);
            $table->string('district', 100);
            $table->char('country', 2)->default('PE');

            $table->primary('code');
            $table->index('department');
            $table->index('province');
            $table->index('district');
            $table->index('country');
        });

        //unidad de medida
        Schema::create('core_unit_measures', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('abbreviation', 20)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
            $table->index('abbreviation');
            $table->index('is_active');
        });

        Schema::create('core_document_types', function (Blueprint $table) {
            $table->char('code', 1)->unique();
            $table->string('name', 100)->unique();
            $table->boolean('is_active')->default(true);

            $table->primary('code');
            $table->index('name');
            $table->index('is_active');
        });

        Schema::create('core_genders', function (Blueprint $table) {
            $table->char('code', 1)->unique();
            $table->string('name', 100)->unique();
            $table->boolean('is_active')->default(true);
            $table->primary('code');
            $table->index('name');
            $table->index('is_active');
        });

        Schema::create('core_instruction_degrees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->boolean('is_active')->default(true);
            $table->index('name');
            $table->index('is_active');
        });

        Schema::create('core_professions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->boolean('is_active')->default(true);
            $table->index('name');
            $table->index('is_active');
        });

        Schema::create('core_persons', function (Blueprint $table) {
            $table->id();
            $table->char('document_type', 1);
            $table->string('document_number', 20);
            $table->string('name', 100);
            $table->string('paternal_surname', 80)->nullable();
            $table->string('maternal_surname', 80)->nullable();
            $table->date('date_birth')->nullable();
            $table->char('cellphone', 9)->nullable();
            $table->string('email', 100)->nullable();
            $table->char('gender', 1)->nullable();
            $table->string('address', 255)->nullable();
            $table->char('city', 6)->nullable();
            $table->char('country', 2)->nullable();

            $table->timestamps();

            $table->foreign('document_type')->references('code')->on('core_document_types')->onDelete('restrict');
            $table->foreign('gender')->references('code')->on('core_genders')->nullOnDelete();
            $table->foreign('country')->references('code')->on('core_countries')->nullOnDelete();
            $table->foreign('city')->references('code')->on('core_cities')->nullOnDelete();

            $table->unique(['document_type', 'document_number']);
            $table->index('name');
            $table->index('paternal_surname');
            $table->index('maternal_surname');
            $table->index('gender');
            $table->index('cellphone');
            $table->index('document_type');
            $table->index('document_number');
            $table->index('email');
            $table->index(['name', 'paternal_surname', 'maternal_surname'], 'core_person_full_name_index');
        });

        //en prueba
        Schema::create('core_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('core_persons')->onDelete('restrict');
            $table->string('profileable_type', 50);
            $table->unsignedBigInteger('profileable_id');
            $table->timestamps();

            $table->unique(['person_id', 'profileable_type', 'profileable_id']);
            $table->index('profileable_type');
            $table->index('profileable_id');
            $table->index('person_id');
        });

        Schema::create('core_infrastructures', function (Blueprint $table) {
            $table->id();
            $table->string('infrastructurable_type');
            $table->unsignedBigInteger('infrastructurable_id');
            $table->unsignedBigInteger('infrastructure_id')->nullable();
            $table->timestamps();
            $table->foreign('infrastructure_id')->references('id')->on('core_infrastructures')->nullOnDelete();
            $table->unique(['infrastructurable_type', 'infrastructurable_id'], 'core_infra_type_id_unique');
            $table->index('infrastructurable_type');
        });

        Schema::create('core_admins', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id')->primary();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('person_id')->references('id')->on('core_persons')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('core_unit_measures');
        Schema::dropIfExists('core_admins');
        Schema::dropIfExists('core_infrastructures');
        Schema::dropIfExists('core_profiles');
        Schema::dropIfExists('core_persons');
        Schema::dropIfExists('core_professions');
        Schema::dropIfExists('core_instruction_degrees');
        Schema::dropIfExists('core_genders');
        Schema::dropIfExists('core_document_types');
        Schema::dropIfExists('core_cities');
        Schema::dropIfExists('core_countries');
        
    }
};
