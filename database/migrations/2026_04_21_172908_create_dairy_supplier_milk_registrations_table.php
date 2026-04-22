<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dairy_supplier_milk_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->date('registration_date');
            $table->enum('shift', ['morning', 'afternoon'])->default('morning');
            $table->decimal('quantity_liters', 10, 2);
            $table->unsignedSmallInteger('number_of_cows')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('dairy_suppliers')->onDelete('cascade');
            $table->unique(['supplier_id', 'registration_date', 'shift'], 'dairy_supplier_milk_registrations_unique');
            $table->index('supplier_id');
            $table->index('registration_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dairy_supplier_milk_registrations');
    }
};
