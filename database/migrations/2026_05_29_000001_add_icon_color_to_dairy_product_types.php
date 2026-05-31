<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dairy_product_types', function (Blueprint $table) {
            $table->string('icon', 20)->nullable()->after('description');
            $table->string('color', 20)->nullable()->after('icon');
        });
    }

    public function down(): void
    {
        Schema::table('dairy_product_types', function (Blueprint $table) {
            $table->dropColumn(['icon', 'color']);
        });
    }
};
