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

        Schema::create('dairy_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('name');
            $table->index('is_active');
        });

        Schema::create('dairy_product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('dairy_supplies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->unsignedBigInteger('unit_measure_id')->nullable();
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('unit_measure_id')->references('id')->on('core_unit_measures')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('auth_users')->onDelete('set null');
            $table->index('name');
            $table->index('unit_measure_id');
            $table->index('is_active');
        });

        Schema::create('dairy_plants', function (Blueprint $table) {
            $table->id();
            $table->char('ruc', 11)->unique();
            $table->string('name', 100)->unique();
            $table->string('trade_name', 100)->nullable();
            $table->enum('type', ['A', 'B', 'C'])->default('A');
            $table->string('brand', 100)->nullable();
            $table->char('city', 6)->nullable();
            $table->string('address', 200)->nullable();
            $table->string('cellphone', 9)->unique();
            $table->string('email', 100)->unique()->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->enum('product_quality', ['very_low', 'low', 'medium', 'high', 'excellent'])->default('medium');
            $table->boolean('has_sanitary_registration')->default(false);
            $table->boolean('has_technification')->default(false);
            $table->boolean('has_production_parameters')->default(false);
            $table->boolean('has_digesa_parameters')->default(false);
            $table->boolean('has_tdd_training')->default(false);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('company_type_id')->nullable();
            $table->unsignedBigInteger('training_level_id')->nullable();
            $table->unsignedBigInteger('institution_type_id')->nullable();
            $table->timestamps();

            $table->foreign('company_type_id')->references('id')->on('dairy_company_types')->onDelete('set null');
            $table->foreign('training_level_id')->references('id')->on('dairy_training_levels')->onDelete('set null');
            $table->foreign('institution_type_id')->references('id')->on('dairy_institution_types')->onDelete('set null');
            $table->foreign('city')->references('code')->on('core_cities')->onDelete('set null');

            $table->index('ruc');
            $table->index('name');
            $table->index('type');
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

        Schema::create('dairy_suppliers', function (Blueprint $table) {
            $table->id();
            $table->enum('supplier_type', ['individual', 'company'])->default('individual');
            $table->char('document_type', 1);
            $table->string('document_number', 20);
            $table->string('name', 100)->unique();
            $table->string('trade_name', 100)->nullable();

            $table->string('cellphone', 9)->unique()->nullable();
            $table->string('email', 100)->unique()->nullable();
            $table->string('address', 200)->nullable();
            $table->char('city', 6)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('supplier_type');
            $table->index('name');
            $table->index('document_number');
            $table->index('cellphone');
            $table->index('email');
            $table->index('is_active');
        });

        Schema::create('dairy_products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('description', 255)->nullable();
            $table->unsignedBigInteger('product_type_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('product_type_id')->references('id')->on('dairy_product_types')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('auth_users')->onDelete('set null');
            $table->index('name');
            $table->index('product_type_id');
            $table->index('is_active');
        });

        Schema::create('dairy_plant_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plant_id');
            $table->unsignedBigInteger('product_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('plant_id')->references('id')->on('dairy_plants')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('dairy_products')->onDelete('cascade');
            $table->unique(['plant_id', 'product_id']);
            $table->index('plant_id');
            $table->index('product_id');
            $table->index('is_active');
        });

        Schema::create('dairy_product_presentations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plant_product_id');
            $table->string('sku', 30)->unique();
            $table->string('name', 100);
            $table->unsignedBigInteger('unit_measure_id')->nullable();
            $table->decimal('content', 10, 3);
            $table->string('barcode', 50)->unique()->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('plant_product_id')->references('id')->on('dairy_plant_products')->onDelete('cascade');
            $table->foreign('unit_measure_id')->references('id')->on('core_unit_measures')->onDelete('set null');
            $table->index('plant_product_id');
            $table->index('sku');
            $table->index('unit_measure_id');
            $table->index('is_active');
        });

        Schema::create('dairy_product_formulas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('presentation_id');
            $table->unsignedBigInteger('supply_id');
            $table->decimal('quantity', 10, 3);
            $table->decimal('unit_price', 10, 3);
            $table->unsignedInteger('version')->default(1);
            $table->boolean('is_current')->default(true);
            $table->timestamps();

            $table->foreign('presentation_id')->references('id')->on('dairy_product_presentations')->onDelete('cascade');
            $table->foreign('supply_id')->references('id')->on('dairy_supplies')->onDelete('cascade');
            $table->unique(['presentation_id', 'supply_id', 'version']);
            $table->index('presentation_id');
            $table->index('supply_id');
            $table->index('version');
            $table->index('is_current');
        });

        Schema::create('dairy_product_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('presentation_id');
            $table->decimal('price', 10, 2);
            $table->decimal('cost', 10, 2)->nullable();
            $table->date('effective_from');
            $table->date('effective_until')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('presentation_id')->references('id')->on('dairy_product_presentations')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('auth_users')->onDelete('set null');
            $table->index('presentation_id');
            $table->index('effective_from');
            $table->index('effective_until');
        });

        Schema::create('dairy_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('presentation_id');
            $table->unsignedBigInteger('plant_id');
            $table->enum('type', ['entry', 'exit', 'adjustment', 'loss']);
            $table->integer('quantity');
            $table->string('batch_code', 30)->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('reason', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('presentation_id')->references('id')->on('dairy_product_presentations')->onDelete('cascade');
            $table->foreign('plant_id')->references('id')->on('dairy_plants')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('auth_users')->onDelete('set null');
            $table->index('presentation_id');
            $table->index('plant_id');
            $table->index('type');
            $table->index('batch_code');
            $table->index('expiration_date');
        });

        Schema::create('dairy_plant_galeries', function (Blueprint $table) {
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

        Schema::create('dairy_workers', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id')->primary();
            $table->unsignedBigInteger('entity_id');
            $table->unsignedBigInteger('position_id')->nullable();
            $table->unsignedBigInteger('instruction_degree_id')->nullable();
            $table->unsignedBigInteger('profession_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('person_id')->references('id')->on('core_persons')->onDelete('cascade');
            $table->foreign('entity_id')->references('id')->on('core_entities')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('dairy_positions')->onDelete('set null');
            $table->foreign('instruction_degree_id')->references('id')->on('core_instruction_degrees')->onDelete('set null');
            $table->foreign('profession_id')->references('id')->on('core_professions')->onDelete('set null');
            $table->index('person_id');
            $table->index('entity_id');
            $table->index('position_id');
            $table->index('instruction_degree_id');
            $table->index('profession_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dairy_suppliers');
        Schema::dropIfExists('dairy_workers');
        Schema::dropIfExists('dairy_plant_galeries');
        Schema::dropIfExists('dairy_stock_movements');
        Schema::dropIfExists('dairy_product_prices');
        Schema::dropIfExists('dairy_product_formulas');
        Schema::dropIfExists('dairy_product_presentations');
        Schema::dropIfExists('dairy_plant_products');
        Schema::dropIfExists('dairy_products');
        Schema::dropIfExists('dairy_plants');
        Schema::dropIfExists('dairy_supplies');
        Schema::dropIfExists('dairy_product_types');
        Schema::dropIfExists('dairy_positions');
        Schema::dropIfExists('dairy_institution_types');
        Schema::dropIfExists('dairy_training_levels');
        Schema::dropIfExists('dairy_company_types');
    }
};
