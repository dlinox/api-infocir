<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Defaults editables por admin en categorías de inversión
        Schema::table('dairy_investment_categories', function (Blueprint $table) {
            $table->unsignedSmallInteger('default_useful_life_years')->nullable()->after('group');
            $table->unsignedSmallInteger('default_validity_years')->nullable()->after('default_useful_life_years');
            $table->string('hint', 255)->nullable()->after('default_validity_years');
        });

        // 2) Refactorizar dairy_fixed_assets: hacerlo self-contained
        //    (asset_catalog_id nullable, agregar name/category/useful_life/notes)
        Schema::table('dairy_fixed_assets', function (Blueprint $table) {
            // FK a categoría (si la categoría se borra → SET NULL)
            $table->foreignId('investment_category_id')
                  ->nullable()
                  ->after('entity_id')
                  ->constrained('dairy_investment_categories')
                  ->nullOnDelete();
            $table->string('name', 150)->nullable()->after('investment_category_id');
            $table->unsignedSmallInteger('useful_life_years')->nullable()->after('residual_value');
            $table->text('notes')->nullable()->after('status');
        });

        // Hacer asset_catalog_id nullable (catálogo opcional ahora)
        Schema::table('dairy_fixed_assets', function (Blueprint $table) {
            $table->dropForeign(['asset_catalog_id']);
        });
        Schema::table('dairy_fixed_assets', function (Blueprint $table) {
            $table->foreignId('asset_catalog_id')->nullable()->change();
            $table->foreign('asset_catalog_id')
                  ->references('id')->on('dairy_asset_catalog')
                  ->nullOnDelete();
        });

        // 3) Nueva tabla dairy_pre_operative_expenses (permisos/licencias)
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

        // 4) Limpiar planes "fixed_assets" y "pre_operative" del modelo viejo
        //    (solo dejamos plan_type = 'working_capital')
        if (Schema::hasColumn('dairy_investment_plans', 'plan_type')) {
            DB::table('dairy_investment_plans')
                ->whereIn('plan_type', ['fixed_assets', 'pre_operative'])
                ->delete();
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('dairy_pre_operative_expenses');

        Schema::table('dairy_fixed_assets', function (Blueprint $table) {
            $table->dropForeign(['investment_category_id']);
            $table->dropColumn(['investment_category_id', 'name', 'useful_life_years', 'notes']);
        });

        Schema::table('dairy_investment_categories', function (Blueprint $table) {
            $table->dropColumn(['default_useful_life_years', 'default_validity_years', 'hint']);
        });
    }
};
