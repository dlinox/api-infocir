<?php

use App\Modules\PlantPanel\Dashboard\Http\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/plant-panel/dashboard/summary', [DashboardController::class, 'summary']);
});
