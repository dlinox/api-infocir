<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dairy_positions', function (Blueprint $table) {
            $table->dropIndex(['entity_type']);
            $table->dropColumn('entity_type');
        });

        Schema::table('dairy_positions', function (Blueprint $table) {
            $table->json('entity_type')->after('description');
        });

        DB::table('dairy_positions')->update(['entity_type' => json_encode(['plant'])]);
    }

    public function down(): void
    {
        Schema::table('dairy_positions', function (Blueprint $table) {
            $table->dropColumn('entity_type');
        });

        Schema::table('dairy_positions', function (Blueprint $table) {
            $table->enum('entity_type', ['plant', 'supplier', 'both'])->default('plant')->after('description');
            $table->index('entity_type');
        });
    }
};
