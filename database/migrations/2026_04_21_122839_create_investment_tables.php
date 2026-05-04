<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── Catálogos (Setting) ──────────────────────────────────────────────

        Schema::create('dairy_investment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->enum('group', ['fixed_asset', 'working_capital', 'pre_operative'])->default('fixed_asset');
            $table->unsignedSmallInteger('default_useful_life_years')->nullable();
            $table->unsignedSmallInteger('default_validity_years')->nullable();
            $table->string('hint', 255)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->index('name');
            $table->index('group');
            $table->index('is_active');
        });

        Schema::create('dairy_fixed_asset_catalog', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investment_category_id')
                  ->constrained('dairy_investment_categories')
                  ->cascadeOnDelete();
            $table->string('name', 100)->unique();
            $table->string('brand', 100)->nullable();
            $table->string('model', 100)->nullable();
            $table->unsignedSmallInteger('useful_life_years')->nullable();
            $table->enum('depreciation_method', ['straight_line', 'declining_balance'])->nullable();
            $table->boolean('is_active')->default(true);

            $table->index('name');
            $table->index('investment_category_id');
            $table->index('is_active');
        });

        Schema::create('dairy_pre_operative_catalog', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investment_category_id')
                  ->constrained('dairy_investment_categories')
                  ->cascadeOnDelete();
            $table->string('name', 100)->unique();
            $table->string('issuing_entity', 100)->nullable();
            $table->enum('recurrence_type', ['one_time', 'periodic'])->nullable();
            $table->unsignedSmallInteger('validity_years')->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('is_active')->default(true);

            $table->index('name');
            $table->index('investment_category_id');
            $table->index('is_active');
        });

        Schema::create('dairy_working_capital_catalog', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investment_category_id')
                  ->constrained('dairy_investment_categories')
                  ->cascadeOnDelete();
            $table->foreignId('unit_measure_id')
                  ->nullable()
                  ->constrained('core_unit_measures')
                  ->nullOnDelete();
            $table->string('name', 100)->unique();
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);

            $table->index('name');
            $table->index('investment_category_id');
            $table->index('is_active');
        });

        // ─── Tablas operativas ────────────────────────────────────────────────

        Schema::create('dairy_investment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')
                  ->constrained('core_entities')
                  ->cascadeOnDelete();
            $table->enum('plan_type', ['fixed_assets', 'pre_operative', 'working_capital'])
                  ->default('fixed_assets');
            $table->string('name', 150)->nullable();
            $table->unsignedSmallInteger('period_year');
            $table->unsignedTinyInteger('period_month')->nullable();
            $table->enum('status', ['draft', 'approved'])->default('draft');
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('entity_id');
            $table->index('plan_type');
            $table->index('period_year');
            $table->index('status');
        });

        Schema::create('dairy_investment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')
                  ->constrained('dairy_investment_plans')
                  ->cascadeOnDelete();
            $table->foreignId('investment_category_id')
                  ->constrained('dairy_investment_categories')
                  ->cascadeOnDelete();
            $table->string('name', 150);
            $table->enum('recurrence_type', ['one_time', 'monthly', 'annual'])->default('one_time');
            $table->decimal('unit_value', 14, 2)->default(0);
            $table->decimal('quantity', 10, 4)->default(1);
            $table->decimal('total', 14, 2)->default(0);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('plan_id');
            $table->index('investment_category_id');
        });

        Schema::create('dairy_fixed_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')
                  ->constrained('core_entities')
                  ->cascadeOnDelete();
            $table->foreignId('investment_category_id')
                  ->nullable()
                  ->constrained('dairy_investment_categories')
                  ->nullOnDelete();
            $table->foreignId('asset_catalog_id')
                  ->nullable()
                  ->constrained('dairy_fixed_asset_catalog')
                  ->nullOnDelete();
            $table->foreignId('investment_item_id')
                  ->nullable()
                  ->constrained('dairy_investment_items')
                  ->nullOnDelete();
            $table->string('name', 150)->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->date('purchase_date');
            $table->decimal('purchase_cost', 14, 2);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('residual_value', 14, 2)->default(0);
            $table->unsignedSmallInteger('useful_life_years')->nullable();
            $table->enum('depreciation_method', ['straight_line', 'declining_balance'])
                  ->default('straight_line');
            $table->enum('status', ['active', 'maintenance', 'disposed'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('entity_id');
            $table->index('asset_catalog_id');
            $table->index('investment_category_id');
            $table->index('status');
        });

        Schema::create('dairy_asset_depreciations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')
                  ->constrained('dairy_fixed_assets')
                  ->cascadeOnDelete();
            $table->unsignedTinyInteger('period_month');
            $table->unsignedSmallInteger('period_year');
            $table->decimal('depreciation_amount', 14, 2);
            $table->decimal('accumulated_depreciation', 14, 2);
            $table->decimal('book_value', 14, 2);
            $table->timestamps();

            $table->unique(['asset_id', 'period_month', 'period_year'], 'asset_depreciation_period_unique');
            $table->index('asset_id');
        });

        Schema::create('dairy_pre_operative_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')
                  ->constrained('core_entities')
                  ->cascadeOnDelete();
            $table->foreignId('investment_category_id')
                  ->nullable()
                  ->constrained('dairy_investment_categories')
                  ->nullOnDelete();
            $table->string('name', 150);
            $table->date('payment_date');
            $table->decimal('amount', 14, 2)->default(0);
            $table->enum('recurrence_type', ['one_time', 'periodic'])->default('one_time');
            $table->unsignedSmallInteger('validity_years')->nullable();
            $table->date('expiration_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('entity_id');
            $table->index('investment_category_id');
            $table->index('expiration_date');
        });

        // ─── FK de categoría de inversión en posiciones ───────────────────────
        Schema::table('dairy_positions', function (Blueprint $table) {
            $table->foreignId('investment_category_id')
                  ->nullable()
                  ->constrained('dairy_investment_categories')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('dairy_positions', function (Blueprint $table) {
            $table->dropForeign(['investment_category_id']);
            $table->dropColumn('investment_category_id');
        });

        Schema::dropIfExists('dairy_pre_operative_expenses');
        Schema::dropIfExists('dairy_asset_depreciations');
        Schema::dropIfExists('dairy_fixed_assets');
        Schema::dropIfExists('dairy_investment_items');
        Schema::dropIfExists('dairy_investment_plans');
        Schema::dropIfExists('dairy_working_capital_catalog');
        Schema::dropIfExists('dairy_pre_operative_catalog');
        Schema::dropIfExists('dairy_fixed_asset_catalog');
        Schema::dropIfExists('dairy_investment_categories');
    }
};