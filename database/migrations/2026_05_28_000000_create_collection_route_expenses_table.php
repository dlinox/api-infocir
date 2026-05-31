<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dairy_working_capital_catalog', function (Blueprint $table) {
            $table->boolean('is_route_expense')->default(false)->after('recurrence_every_days');
            $table->index('is_route_expense');
        });

        Schema::create('dairy_collection_route_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_route_id')
                  ->constrained('dairy_collection_routes')
                  ->cascadeOnDelete();
            $table->foreignId('working_capital_catalog_id');
            $table->decimal('amount', 12, 2);
            $table->decimal('quantity', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('collection_route_id');
            $table->index('working_capital_catalog_id');

            $table->foreign('working_capital_catalog_id', 'fk_collection_route_expenses_wcc')
                ->references('id')
                ->on('dairy_working_capital_catalog')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dairy_collection_route_expenses');

        Schema::table('dairy_working_capital_catalog', function (Blueprint $table) {
            $table->dropIndex(['is_route_expense']);
            $table->dropColumn('is_route_expense');
        });
    }
};
