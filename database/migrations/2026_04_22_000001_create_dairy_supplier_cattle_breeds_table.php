<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dairy_supplier_cattle_breeds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->string('breed_name', 100);
            $table->unsignedSmallInteger('count')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('dairy_suppliers')->onDelete('cascade');
            $table->index('supplier_id');
            $table->index('breed_name');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dairy_supplier_cattle_breeds');
    }
};
