<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dairy_investment_plans', function (Blueprint $table) {
            // Tipo de plan — cada tipo es un registro independiente
            $table->enum('plan_type', ['fixed_assets', 'pre_operative', 'working_capital'])
                  ->default('fixed_assets')
                  ->after('entity_id');

            // Mes del período (solo para working_capital, 1–12)
            $table->unsignedTinyInteger('period_month')
                  ->nullable()
                  ->after('period_year');

            // El nombre se convierte en opcional (se auto-genera)
            $table->string('name', 150)->nullable()->change();
        });

        // Agregar recurrence_type a items para pre_operative
        Schema::table('dairy_investment_items', function (Blueprint $table) {
            $table->enum('recurrence_type', ['one_time', 'monthly', 'annual'])
                  ->default('one_time')
                  ->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('dairy_investment_items', function (Blueprint $table) {
            $table->dropColumn('recurrence_type');
        });

        Schema::table('dairy_investment_plans', function (Blueprint $table) {
            $table->dropColumn(['plan_type', 'period_month']);
            $table->string('name', 150)->nullable(false)->change();
        });
    }
};
