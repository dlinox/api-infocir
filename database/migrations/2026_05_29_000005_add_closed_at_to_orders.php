<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dairy_orders', function (Blueprint $table) {
            $table->timestamp('closed_at')->nullable()->after('stock_applied');
            $table->index('closed_at');
        });

        // Backfill: pedidos ya cerrados toman su última actualización como fecha de cierre
        DB::table('dairy_orders')
            ->where('status', 'closed')
            ->update(['closed_at' => DB::raw('updated_at')]);
    }

    public function down(): void
    {
        Schema::table('dairy_orders', function (Blueprint $table) {
            $table->dropColumn('closed_at');
        });
    }
};
