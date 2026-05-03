<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dairy_products', function (Blueprint $table) {
            $table->boolean('contains_milk')->default(false)->after('is_active');
            $table->decimal('milk_liters_per_unit', 8, 3)->nullable()->after('contains_milk');
        });
    }

    public function down(): void
    {
        Schema::table('dairy_products', function (Blueprint $table) {
            $table->dropColumn(['contains_milk', 'milk_liters_per_unit']);
        });
    }
};
