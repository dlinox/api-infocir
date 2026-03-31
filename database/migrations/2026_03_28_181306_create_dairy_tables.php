<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dairy_company_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
            $table->index('is_active');
        });

        Schema::create('dairy_training_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
            $table->index('is_active');
        });

        Schema::create('dairy_institution_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->enum('nature', ['public', 'private', 'mixed'])->default('private');
            $table->enum('level', ['national', 'regional', 'provincial', 'district'])->default('national');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('name');
            $table->index('nature');
            $table->index('level');
            $table->index('is_active');
        });

        //cargos posiciones en la planta
        Schema::create('dairy_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
            $table->index('is_active');
        });

        //tipos de productos
        Schema::create('dairy_product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('dairy_plants', function (Blueprint $table) {
            $table->id();
            $table->char('ruc', 11)->unique();
            $table->string('name', 100)->unique();
            $table->string('trade_name', 100)->nullable();
            $table->enum('type', ['A', 'B', 'C'])->default('A');
            $table->string('brand', 100)->nullable();
            $table->char('country', 2)->nullable();
            $table->char('city', 6)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('cellphone', 9)->unique(); // numero de cellular para contacto por whatsapp
            $table->string('email', 100)->unique()->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            //calidad de producto
            $table->enum('product_quality', ['very_low', 'low', 'medium', 'high', 'excellent'])->default('medium');
            //tiene registro sanitario?
            $table->boolean('has_sanitary_registration')->default(false);
            //tecnificacion ?
            $table->boolean('has_technification')->default(false);
            //parametros de produccion ?
            $table->boolean('has_production_parameters')->default(false);
            //parametros DIGESA ?
            $table->boolean('has_digesa_parameters')->default(false);
            //capacitacion TDD
            $table->boolean('has_tdd_training')->default(false);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            //relaciones
            $table->unsignedBigInteger('company_type_id')->nullable();
            $table->unsignedBigInteger('training_level_id')->nullable();
            $table->unsignedBigInteger('institution_type_id')->nullable();
            $table->timestamps();

            $table->foreign('company_type_id')->references('id')->on('dairy_company_types')->onDelete('set null');
            $table->foreign('training_level_id')->references('id')->on('dairy_training_levels')->onDelete('set null');
            $table->foreign('institution_type_id')->references('id')->on('dairy_institution_types')->onDelete('set null');
            $table->foreign('country')->references('code')->on('core_countries')->onDelete('set null');
            $table->foreign('city')->references('code')->on('core_cities')->onDelete('set null');
            
            $table->index('ruc');
            $table->index('name');
            $table->index('type');
            $table->index('country');
            $table->index('city');
            $table->index('cellphone');
            $table->index('email');
            $table->index('company_type_id');
            $table->index('training_level_id');
            $table->index('institution_type_id');
            $table->index('product_quality');
            $table->index('has_sanitary_registration');
            $table->index('has_technification');
            $table->index('has_production_parameters');
            $table->index('has_digesa_parameters');
            $table->index('has_tdd_training');
            $table->index('is_active');
        });

        Schema::create('dairy_galeries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plant_id');
            $table->string('image_path', 255);
            $table->string('caption', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('plant_id')->references('id')->on('dairy_plants')->onDelete('cascade');
            $table->index('plant_id');
            $table->index('is_active');
        });

        //profile - managers
        Schema::create('dairy_managers', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id')->primary();
            $table->unsignedBigInteger('plant_id');
            $table->string('position', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('person_id')->references('id')->on('core_persons')->onDelete('cascade');
            $table->foreign('plant_id')->references('id')->on('dairy_plants')->onDelete('cascade');
            $table->index('person_id');
            $table->index('plant_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dairy_institution_types');
        Schema::dropIfExists('dairy_training_levels');
        Schema::dropIfExists('dairy_company_types');
        Schema::dropIfExists('dairy_plants');
        Schema::dropIfExists('dairy_galeries');
    }
};
