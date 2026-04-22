<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PlantPanel\PlantSettings\Http\Controllers\PlantSettingsController;

Route::middleware(['auth:api'])->prefix('/plant-settings')->group(function () {
    Route::get('/me',     [PlantSettingsController::class, 'get']);
    Route::put('/update', [PlantSettingsController::class, 'update']);
});
