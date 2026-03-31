<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_instructors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id');
            $table->string('specialty', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('person_id')->references('id')->on('core_persons')->onDelete('cascade');
            $table->index('person_id');
            $table->index('is_active');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('learning_instructors');
    }
};
