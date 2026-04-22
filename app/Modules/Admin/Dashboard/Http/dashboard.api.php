<?php

use App\Modules\Admin\Dashboard\Http\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/admin/dashboard/summary', [DashboardController::class, 'summary']);
    Route::get('/admin/dashboard/map-data', [DashboardController::class, 'mapData']);
});
