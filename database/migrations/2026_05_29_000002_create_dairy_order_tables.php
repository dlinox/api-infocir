<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dairy_orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->enum('status', ['pending', 'contacted', 'closed', 'cancelled'])->default('pending');
            $table->string('customer_name', 150);
            $table->string('customer_phone', 30);
            $table->string('customer_email', 150)->nullable();
            $table->string('customer_document', 20)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('reference', 255)->nullable();
            $table->text('inquiry')->nullable();
            $table->unsignedBigInteger('plant_id')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamp('whatsapp_sent_at')->nullable();
            $table->timestamps();

            $table->foreign('plant_id')->references('id')->on('dairy_plants')->onDelete('set null');
            $table->index('code');
            $table->index('status');
            $table->index('plant_id');
        });

        Schema::create('dairy_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('presentation_id')->nullable();
            $table->string('product_name', 150);
            $table->string('presentation_name', 150);
            $table->decimal('unit_price', 10, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('dairy_orders')->onDelete('cascade');
            $table->foreign('presentation_id')->references('id')->on('dairy_product_presentations')->onDelete('set null');
            $table->index('order_id');
            $table->index('presentation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dairy_order_items');
        Schema::dropIfExists('dairy_orders');
    }
};
