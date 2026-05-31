<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dairy_business_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plant_id')->unique();
            $table->json('data'); // inputs editables del plan (parámetros, inversiones, productos)
            $table->timestamps();

            $table->foreign('plant_id')->references('id')->on('dairy_plants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dairy_business_plans');
    }
};
