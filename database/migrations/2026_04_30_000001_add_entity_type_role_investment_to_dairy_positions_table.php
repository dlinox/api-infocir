<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dairy_positions', function (Blueprint $table) {
            $table->enum('entity_type', ['plant', 'supplier', 'both'])->default('plant')->after('description');
            $table->foreignId('role_id')->nullable()->constrained('behavior_roles')->nullOnDelete()->after('entity_type');
            $table->foreignId('investment_category_id')->nullable()->constrained('dairy_investment_categories')->nullOnDelete()->after('role_id');

            $table->index('entity_type');
        });
    }

    public function down(): void
    {
        Schema::table('dairy_positions', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['investment_category_id']);
            $table->dropIndex(['entity_type']);
            $table->dropColumn(['entity_type', 'role_id', 'investment_category_id']);
        });
    }
};
