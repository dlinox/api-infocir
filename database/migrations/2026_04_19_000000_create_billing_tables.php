<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_series', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('issuer_entity_id');
            $table->string('voucher_type', 2);
            $table->string('serie', 4);
            $table->unsignedInteger('current_correlative')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('issuer_entity_id')->references('id')->on('core_entities')->onDelete('cascade');
            $table->unique(['issuer_entity_id', 'voucher_type', 'serie']);
            $table->index('issuer_entity_id');
            $table->index('voucher_type');
            $table->index('is_active');
        });

        Schema::create('billing_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_type', 2);
            $table->string('serie', 4);
            $table->unsignedInteger('correlative');
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->char('currency', 3)->default('PEN');
            $table->string('operation_type', 4)->nullable();

            $table->unsignedBigInteger('issuer_entity_id');
            $table->char('issuer_ruc', 11);
            $table->string('issuer_name', 200);
            $table->string('issuer_address', 200)->nullable();

            $table->char('client_document_type', 1);
            $table->string('client_document_number', 20);
            $table->string('client_name', 200);
            $table->string('client_address', 200)->nullable();

            $table->decimal('mto_oper_gravadas', 12, 2)->nullable();
            $table->decimal('mto_oper_inafectas', 12, 2)->nullable();
            $table->decimal('mto_oper_exoneradas', 12, 2)->nullable();
            $table->decimal('mto_oper_gratuitas', 12, 2)->nullable();
            $table->decimal('mto_igv', 12, 2)->nullable();
            $table->decimal('mto_isc', 12, 2)->nullable();
            $table->decimal('mto_otros_tributos', 12, 2)->nullable();
            $table->decimal('icbper', 12, 2)->nullable();
            $table->decimal('total_impuestos', 12, 2)->nullable();
            $table->decimal('valor_venta', 12, 2)->nullable();
            $table->decimal('sub_total', 12, 2)->nullable();
            $table->decimal('total', 12, 2);
            $table->decimal('redondeo', 8, 2)->nullable();
            $table->decimal('descuento_global', 12, 2)->nullable();

            $table->string('affected_voucher_type', 2)->nullable();
            $table->string('affected_voucher_number', 20)->nullable();
            $table->string('note_reason_code', 2)->nullable();
            $table->string('note_reason_description', 200)->nullable();

            $table->string('xml_path', 255)->nullable();
            $table->string('cdr_path', 255)->nullable();
            $table->string('hash', 100)->nullable();
            $table->enum('sunat_status', ['pending', 'accepted', 'accepted_observations', 'rejected', 'exception'])->nullable();
            $table->string('sunat_code', 10)->nullable();
            $table->text('sunat_description')->nullable();
            $table->json('sunat_notes')->nullable();
            $table->timestamp('sent_at')->nullable();

            $table->string('voucherable_type', 50)->nullable();
            $table->unsignedBigInteger('voucherable_id')->nullable();

            $table->enum('status', ['draft', 'issued', 'sent', 'voided'])->default('draft');
            $table->text('observations')->nullable();
            $table->text('legend_text')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('issuer_entity_id')->references('id')->on('core_entities')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('auth_users')->onDelete('set null');
            $table->unique(['issuer_entity_id', 'serie', 'correlative']);
            $table->index('issuer_entity_id');
            $table->index('voucher_type');
            $table->index('issue_date');
            $table->index('client_document_number');
            $table->index(['voucherable_type', 'voucherable_id']);
            $table->index('status');
            $table->index('sunat_status');
        });

        Schema::create('billing_voucher_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voucher_id');
            $table->unsignedSmallInteger('item_order')->default(1);
            $table->string('product_code', 30)->nullable();
            $table->string('product_code_sunat', 20)->nullable();
            $table->string('description', 500);
            $table->string('unit', 5);
            $table->decimal('quantity', 12, 3);
            $table->decimal('unit_value', 12, 4);
            $table->decimal('unit_price', 12, 4)->nullable();
            $table->decimal('discount', 12, 2)->nullable();
            $table->decimal('mto_base_igv', 12, 2)->nullable();
            $table->decimal('percentage_igv', 5, 2)->nullable();
            $table->decimal('igv', 12, 2)->nullable();
            $table->string('tip_afe_igv', 2)->nullable();
            $table->decimal('total_impuestos', 12, 2)->nullable();
            $table->decimal('valor_venta', 12, 2);
            $table->timestamps();

            $table->foreign('voucher_id')->references('id')->on('billing_vouchers')->onDelete('cascade');
            $table->index('voucher_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_voucher_items');
        Schema::dropIfExists('billing_vouchers');
        Schema::dropIfExists('billing_series');
    }
};
