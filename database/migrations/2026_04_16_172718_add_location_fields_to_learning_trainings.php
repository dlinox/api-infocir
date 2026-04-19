<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('learning_trainings', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('location');   // Latitud para presenciales
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');  // Longitud para presenciales
            $table->string('meeting_url', 500)->nullable()->after('longitude');  // URL de reunión para virtuales/mixtas
        });
    }

    public function down(): void
    {
        Schema::table('learning_trainings', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'meeting_url']);
        });
    }
};
