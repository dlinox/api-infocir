<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dairy_orders', function (Blueprint $table) {
            // Stock descontado al cerrar el pedido (evita duplicar al re-cerrar)
            $table->boolean('stock_applied')->default(false)->after('whatsapp_sent_at');
            // Datos del recibo simple (PDF) — preparado para comprobante SUNAT a futuro
            $table->string('receipt_number', 30)->nullable()->after('stock_applied');
            $table->timestamp('receipt_issued_at')->nullable()->after('receipt_number');
        });
    }

    public function down(): void
    {
        Schema::table('dairy_orders', function (Blueprint $table) {
            $table->dropColumn(['stock_applied', 'receipt_number', 'receipt_issued_at']);
        });
    }
};
